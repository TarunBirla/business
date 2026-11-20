<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;

class MemberSurveyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get user's active joined communities
        $joinedGroupIds = $user->groups()
            ->wherePivot('status', 'active')
            ->pluck('groups.id');

        $selectedGroupId = $request->get('group_id');
        if ($selectedGroupId && !$joinedGroupIds->contains($selectedGroupId)) {
            $selectedGroupId = null;
        }

        $query = Survey::whereIn('group_id', $joinedGroupIds)
            ->where('status', 'active')
            ->with(['group', 'questions'])
            ->withCount('questions')
            ->latest();

        if ($selectedGroupId) {
            $query->where('group_id', $selectedGroupId);
        }

        $surveys = $query->paginate(12);

        // Map which surveys the user has completed
        $completedSurveyIds = SurveyResponse::where('user_id', $user->id)
            ->whereIn('group_id', $joinedGroupIds)
            ->distinct()
            ->pluck('survey_id')
            ->toArray();

        $joinedGroups = Group::whereIn('id', $joinedGroupIds)->orderBy('name')->get();

        return view('member.surveys.index', [
            'surveys' => $surveys,
            'completedSurveyIds' => $completedSurveyIds,
            'joinedGroups' => $joinedGroups,
            'selectedGroupId' => $selectedGroupId,
        ]);
    }

    public function show(Survey $survey)
    {
        $user = auth()->user();

        // Ensure user belongs to the community
        if (!$user->isMemberOf($survey->group_id) && !$user->isGroupAdmin($survey->group_id) && !$user->isSuperAdmin()) {
            abort(403, 'You must be an approved member of this community to participate in this survey.');
        }

        if ($survey->status !== 'active') {
            return redirect()->route('member.surveys.index')
                ->with('info', 'This survey is currently closed or inactive.');
        }

        $isCompleted = $survey->isCompletedBy($user->id);

        $questions = $survey->questions()->orderBy('sort_order', 'asc')->get();

        // If completed, fetch member's previous answers
        $userAnswers = [];
        if ($isCompleted) {
            $userAnswers = SurveyResponse::where('survey_id', $survey->id)
                ->where('user_id', $user->id)
                ->pluck('answer', 'question_id')
                ->toArray();
        }

        return view('member.surveys.show', [
            'survey' => $survey,
            'questions' => $questions,
            'isCompleted' => $isCompleted,
            'userAnswers' => $userAnswers,
        ]);
    }

    public function submit(Request $request, Survey $survey)
    {
        $user = auth()->user();

        // Ensure user belongs to the community
        if (!$user->isMemberOf($survey->group_id) && !$user->isGroupAdmin($survey->group_id) && !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized.');
        }

        if ($survey->status !== 'active') {
            return redirect()->route('member.surveys.index')
                ->with('error', 'This survey is closed and no longer accepting responses.');
        }

        // Check if already completed
        if ($survey->isCompletedBy($user->id)) {
            return redirect()->route('member.surveys.show', $survey->id)
                ->with('info', 'You have already submitted your response to this survey.');
        }

        $questions = $survey->questions;
        $rules = [];
        $messages = [];

        foreach ($questions as $q) {
            $fieldKey = 'answers.' . $q->id;

            if ($q->is_required) {
                if ($q->question_type === 'multiple_choice') {
                    $rules[$fieldKey] = 'required|array|min:1';
                    $messages[$fieldKey . '.required'] = "Please select at least one option for: \"{$q->question_text}\"";
                } else {
                    $rules[$fieldKey] = 'required';
                    $messages[$fieldKey . '.required'] = "This question is required: \"{$q->question_text}\"";
                }
            } else {
                $rules[$fieldKey] = 'nullable';
            }
        }

        $request->validate($rules, $messages);

        $submittedAnswers = $request->input('answers', []);

        foreach ($questions as $q) {
            $val = $submittedAnswers[$q->id] ?? null;

            if (is_array($val)) {
                $answerString = implode(', ', $val);
            } else {
                $answerString = !is_null($val) ? trim((string)$val) : '';
            }

            SurveyResponse::create([
                'survey_id' => $survey->id,
                'user_id' => $user->id,
                'group_id' => $survey->group_id,
                'question_id' => $q->id,
                'answer' => $answerString,
            ]);
        }

        // Send In-App Notification to Group Admin
        try {
            if ($survey->group) {
                $adminUserIds = $survey->group->members()
                    ->wherePivot('membership_role', 'group_admin')
                    ->pluck('users.id');

                foreach ($adminUserIds as $adminId) {
                    Notification::create([
                        'user_id' => $adminId,
                        'type' => 'survey_response',
                        'title' => 'New Survey Response',
                        'message' => "{$user->name} completed the survey '{$survey->title}' in {$survey->group->name}.",
                        'link' => route('group_admin.surveys.responses', ['group' => $survey->group_id, 'survey' => $survey->id]),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Non-blocking notification
        }

        return redirect()->route('member.surveys.index')
            ->with('success', "Thank you! Your response for '{$survey->title}' has been submitted successfully.");
    }
}

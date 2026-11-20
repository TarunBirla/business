<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GroupAdminSurveyController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);

        $surveys = $group->surveys()
            ->withCount(['questions', 'responses as total_responses_count'])
            ->latest()
            ->paginate(12);

        return view('group_admin.surveys.index', compact('group', 'surveys'));
    }

    public function create(Group $group)
    {
        $this->authorizeAdmin($group);

        $survey = new Survey(['status' => 'active']);
        $initialQuestions = [];

        return view('group_admin.surveys.form', [
            'group' => $group,
            'survey' => $survey,
            'initialQuestions' => $initialQuestions,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:1000',
            'questions.*.type' => 'required|in:text,single_choice,multiple_choice,rating',
            'questions.*.options' => 'nullable|string',
            'questions.*.is_required' => 'nullable|boolean',
        ], [
            'questions.required' => 'Please add at least one question to the survey.',
            'questions.min' => 'Please add at least one question to the survey.',
            'questions.*.text.required' => 'Question text cannot be empty.',
        ]);

        $survey = $group->surveys()->create([
            'created_by' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $sortOrder = 0;
        foreach ($request->questions as $qData) {
            $options = null;
            if (in_array($qData['type'], ['single_choice', 'multiple_choice']) && !empty($qData['options'])) {
                // Split options by newline or comma
                $rawOptions = preg_split('/[\r\n,]+/', $qData['options']);
                $options = array_values(array_filter(array_map('trim', $rawOptions)));
            }

            $survey->questions()->create([
                'question_text' => $qData['text'],
                'question_type' => $qData['type'],
                'options' => $options,
                'is_required' => isset($qData['is_required']) && $qData['is_required'] ? true : false,
                'sort_order' => $sortOrder++,
            ]);
        }

        return redirect()->route('group_admin.surveys.index', $group->id)
            ->with('success', "Survey '{$survey->title}' created successfully.");
    }

    public function edit(Group $group, Survey $survey)
    {
        $this->authorizeAdmin($group);

        if ((int)$survey->group_id !== (int)$group->id) {
            abort(404);
        }

        $initialQuestions = $survey->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'text' => $q->question_text,
                'type' => $q->question_type,
                'options' => is_array($q->options) ? implode("\n", $q->options) : ($q->options ?: ''),
                'is_required' => (bool)$q->is_required,
            ];
        })->values()->toArray();

        return view('group_admin.surveys.form', [
            'group' => $group,
            'survey' => $survey,
            'initialQuestions' => $initialQuestions,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Group $group, Survey $survey)
    {
        $this->authorizeAdmin($group);

        if ((int)$survey->group_id !== (int)$group->id) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:1000',
            'questions.*.type' => 'required|in:text,single_choice,multiple_choice,rating',
            'questions.*.options' => 'nullable|string',
            'questions.*.is_required' => 'nullable|boolean',
        ]);

        $survey->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Keep track of existing question IDs
        $submittedQuestionIds = [];
        $sortOrder = 0;

        foreach ($request->questions as $qData) {
            $options = null;
            if (in_array($qData['type'], ['single_choice', 'multiple_choice']) && !empty($qData['options'])) {
                $rawOptions = preg_split('/[\r\n,]+/', $qData['options']);
                $options = array_values(array_filter(array_map('trim', $rawOptions)));
            }

            $questionId = $qData['id'] ?? null;

            if ($questionId && $survey->questions()->where('id', $questionId)->exists()) {
                $q = SurveyQuestion::find($questionId);
                $q->update([
                    'question_text' => $qData['text'],
                    'question_type' => $qData['type'],
                    'options' => $options,
                    'is_required' => isset($qData['is_required']) && $qData['is_required'] ? true : false,
                    'sort_order' => $sortOrder++,
                ]);
                $submittedQuestionIds[] = $q->id;
            } else {
                $newQ = $survey->questions()->create([
                    'question_text' => $qData['text'],
                    'question_type' => $qData['type'],
                    'options' => $options,
                    'is_required' => isset($qData['is_required']) && $qData['is_required'] ? true : false,
                    'sort_order' => $sortOrder++,
                ]);
                $submittedQuestionIds[] = $newQ->id;
            }
        }

        // Delete questions removed during edit
        $survey->questions()->whereNotIn('id', $submittedQuestionIds)->delete();

        return redirect()->route('group_admin.surveys.index', $group->id)
            ->with('success', "Survey '{$survey->title}' updated successfully.");
    }

    public function toggleStatus(Group $group, Survey $survey)
    {
        $this->authorizeAdmin($group);

        if ((int)$survey->group_id !== (int)$group->id) {
            abort(404);
        }

        $newStatus = $survey->status === 'active' ? 'inactive' : 'active';
        $survey->update(['status' => $newStatus]);

        $statusLabel = ucfirst($newStatus);
        return back()->with('success', "Survey status changed to {$statusLabel}.");
    }

    public function destroy(Group $group, Survey $survey)
    {
        $this->authorizeAdmin($group);

        if ((int)$survey->group_id !== (int)$group->id) {
            abort(404);
        }

        $title = $survey->title;
        $survey->delete();

        return redirect()->route('group_admin.surveys.index', $group->id)
            ->with('success', "Survey '{$title}' and its responses have been deleted.");
    }

    public function responses(Group $group, Survey $survey)
    {
        $this->authorizeAdmin($group);

        if ((int)$survey->group_id !== (int)$group->id) {
            abort(404);
        }

        $survey->load('questions');

        // Group responses by user
        $rawResponses = SurveyResponse::where('survey_id', $survey->id)
            ->with(['user', 'question'])
            ->latest()
            ->get();

        $respondentUsers = $rawResponses->groupBy('user_id');

        return view('group_admin.surveys.responses', [
            'group' => $group,
            'survey' => $survey,
            'respondentUsers' => $respondentUsers,
            'totalParticipants' => $respondentUsers->count(),
        ]);
    }

    public function exportCsv(Group $group, Survey $survey): StreamedResponse
    {
        $this->authorizeAdmin($group);

        if ((int)$survey->group_id !== (int)$group->id) {
            abort(404);
        }

        $questions = $survey->questions()->orderBy('sort_order', 'asc')->get();
        $rawResponses = SurveyResponse::where('survey_id', $survey->id)
            ->with(['user'])
            ->get()
            ->groupBy('user_id');

        $filename = 'survey_' . $survey->id . '_responses_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($questions, $rawResponses) {
            $handle = fopen('php://output', 'w');

            // Header Row
            $headerRow = ['Respondent Name', 'Email', 'Phone', 'Submitted At'];
            foreach ($questions as $q) {
                $headerRow[] = $q->question_text;
            }
            fputcsv($handle, $headerRow);

            // Data Rows
            foreach ($rawResponses as $userId => $userResponses) {
                $user = $userResponses->first()?->user;
                $submittedAt = $userResponses->first()?->created_at?->format('Y-m-d H:i:s') ?? '';

                $row = [
                    $user ? $user->name : 'Anonymous',
                    $user ? $user->email : 'N/A',
                    $user ? ($user->phone ?: 'N/A') : 'N/A',
                    $submittedAt,
                ];

                foreach ($questions as $q) {
                    $ans = $userResponses->firstWhere('question_id', $q->id)?->answer ?? '';
                    $row[] = $ans;
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $headers);
    }

    private function authorizeAdmin(Group $group): void
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access to this community.');
        }
    }
}

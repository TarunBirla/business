<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Theme;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupAdminThemeController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);

        $themes = Theme::orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->paginate(12);

        $user = auth()->user();
        $assignedGroups = $user->isSuperAdmin() ? Group::all() : $user->groups()->wherePivot('membership_role', 'group_admin')->get();

        return view('group_admin.themes.index', compact('group', 'themes', 'assignedGroups'));
    }

    public function create(Group $group)
    {
        $this->authorizeAdmin($group);

        $defaultColors = ThemeService::getDefaultColorPalette();
        return view('group_admin.themes.form', [
            'group' => $group,
            'theme' => new Theme(['colors' => $defaultColors, 'type' => 'light']),
            'isEdit' => false,
            'defaultColors' => $defaultColors,
        ]);
    }

    public function store(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:light,dark',
            'colors' => 'required|array',
        ]);

        $slug = Str::slug($request->name);
        $count = Theme::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $theme = Theme::create([
            'name' => $request->name,
            'slug' => $slug,
            'type' => $request->type,
            'is_default' => false,
            'is_active' => true,
            'colors' => $request->colors,
        ]);

        // Auto-set created theme as community theme
        $group->update(['theme_id' => $theme->id]);

        return redirect()->route('group_admin.themes.index', $group->id)
            ->with('success', "New theme '{$theme->name}' created and set as active theme for {$group->name}!");
    }

    public function edit(Group $group, Theme $theme)
    {
        $this->authorizeAdmin($group);

        $defaultColors = ThemeService::getDefaultColorPalette();
        return view('group_admin.themes.form', [
            'group' => $group,
            'theme' => $theme,
            'isEdit' => true,
            'defaultColors' => $defaultColors,
        ]);
    }

    public function update(Request $request, Group $group, Theme $theme)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:light,dark',
            'colors' => 'required|array',
        ]);

        $theme->update([
            'name' => $request->name,
            'type' => $request->type,
            'colors' => $request->colors,
        ]);

        return redirect()->route('group_admin.themes.index', $group->id)
            ->with('success', "Theme '{$theme->name}' updated successfully!");
    }

    public function setCommunityDefault(Group $group, Theme $theme)
    {
        $this->authorizeAdmin($group);

        $group->update(['theme_id' => $theme->id]);

        return back()->with('success', "'{$theme->name}' is now set as the active theme for {$group->name}.");
    }

    public function duplicate(Group $group, Theme $theme)
    {
        $this->authorizeAdmin($group);

        $newName = $theme->name . ' (Custom)';
        $slug = Str::slug($newName);
        $count = Theme::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $newTheme = Theme::create([
            'name' => $newName,
            'slug' => $slug,
            'type' => $theme->type,
            'is_default' => false,
            'is_active' => true,
            'colors' => $theme->colors,
        ]);

        return redirect()->route('group_admin.themes.edit', [$group->id, $newTheme->id])
            ->with('success', "Duplicated theme created. Customize colors and save!");
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}

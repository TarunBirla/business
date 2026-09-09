<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        return view('super_admin.themes.index', compact('themes'));
    }

    public function create()
    {
        $defaultColors = ThemeService::getDefaultColorPalette();
        return view('super_admin.themes.form', [
            'theme' => new Theme(['colors' => $defaultColors, 'type' => 'light']),
            'isEdit' => false,
            'defaultColors' => $defaultColors,
        ]);
    }

    public function store(Request $request)
    {
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

        return redirect()->route('super_admin.themes.index')->with('success', "Theme '{$theme->name}' created successfully!");
    }

    public function edit(Theme $theme)
    {
        $defaultColors = ThemeService::getDefaultColorPalette();
        return view('super_admin.themes.form', [
            'theme' => $theme,
            'isEdit' => true,
            'defaultColors' => $defaultColors,
        ]);
    }

    public function update(Request $request, Theme $theme)
    {
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

        return redirect()->route('super_admin.themes.index')->with('success', "Theme '{$theme->name}' updated successfully!");
    }

    public function setDefault(Theme $theme)
    {
        Theme::where('is_default', true)->update(['is_default' => false]);
        $theme->update(['is_default' => true, 'is_active' => true]);

        return back()->with('success', "'{$theme->name}' is now the platform default theme.");
    }

    public function duplicate(Theme $theme)
    {
        $newName = $theme->name . ' (Copy)';
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

        return redirect()->route('super_admin.themes.edit', $newTheme->id)
            ->with('success', "Duplicated theme created. You can now customize its colors!");
    }

    public function destroy(Theme $theme)
    {
        if ($theme->is_default) {
            return back()->with('error', 'Cannot delete the theme currently set as default. Mark another theme as default first.');
        }

        $theme->delete();
        return back()->with('success', 'Theme deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class SuperAdminProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) me use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('technologies', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('super_admin.projects.index', compact('projects'));
    }
}

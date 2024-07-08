<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Project::query();
            $sortFields = request('sort_field', 'created_at');
            $sortDirection = request('sort_direction', 'desc');
            if (request('name')) {
                $query->where('name', 'like', '%' . request('name') . '%');
            }
            if (request('status')) {
                $query->where('status', request('status'));
                // dd(request('status'));
            }
            $projects = $query->orderBy($sortFields, $sortDirection)->paginate(10)->oneachside(1);
            return Inertia::render('Project/Index', [
                'projects' => ProjectResource::collection($projects),
                'queryParams' => $request->query() ?? null,
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error($e);

            // Optionally return an error response
            return response()->json(['error' => 'An error occurred while fetching projects'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $query = $project->tasks();
        $sortFields = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        if (request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }
        if (request('status')) {
            $query->where('status', request('status'));
        }
        $tasks = $query->orderBy($sortFields, $sortDirection)->paginate(10);
        return Inertia::render('Project/Show', [
            'project' => new ProjectResource($project),
            'tasks' => TaskResource::collection($tasks),
            'queryParams' => request()->query() ?? null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}

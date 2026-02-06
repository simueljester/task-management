<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ProjectRepository;
use App\Http\Requests\Projects\SaveProjectRequest;

class ProjectController extends Controller
{
    private $projectRepository;

    public function __construct()
    {
        //dependency injection
        $this->projectRepository = app(ProjectRepository::class);
    }

    public function index()
    {
        $projects = $this->projectRepository->getAllProjectsForUser(auth()->id());
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveProjectRequest $request)
    {
        $validated = $request->validated();


        $data = [
            'user_id'       => auth()->id(),
            'name'         => $validated['name'],
        ];

        try {

            $this->projectRepository->save($data);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        
        }
        
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
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
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
       try {
            $this->projectRepository->delete($project->id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}

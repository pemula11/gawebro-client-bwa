<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\StoreToolProjectRequest;
use App\Http\Requests\StoreToolRequest;
use App\Models\ProjectTool;
use App\Models\Tool;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();

        $projectQuery = Project::with(['categories', 'applicants'])->orderByDesc('id');
        
        if ($user->hasRole('project_client')){
            // filter project by client id == user id
            $projectQuery->whereHas('owner', function ($query) use ($user){
                $query->where('client_id', $user->id);
            });
        }
        
        $projects = $projectQuery->paginate(10);
        
        return view('admin.projects.index', compact('projects'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.projects.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
        $user = Auth::user();
        $balance = $user->wallet->balance;

        if ($request->input('budget') > $balance){
            return redirect()->back()->withErrors(['budget' => 'Your balance is not enough']);
        }

        DB::transaction(function () use ($request, $user){
            $user->wallet->decrement('balance', $request->input('budget'));
            $projectWalletTransaction = WalletTransaction::create([
                'type' => 'project Cost',
                'amount' => $request->input('budget'),
                'is_paid' => true,
                'user_id' => $user->id
            ]);

            $validated = $request->validated();

            if ($request->hasFile('thumbnail')){
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $validated['client_id'] = $user->id;
            $validated['has_finished'] = false;
            $validated['has_started'] = false;

            $newProject = Project::create($validated);


        });

        return redirect()->route('admin.projects.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
        return view('admin.projects.show', compact('project'));
    }

    public function tools(Project $project)
    {
        if ($project->client_id != Auth::id()){
            abort(403, 'You are not allowed to view this page');
        }

        $tools = Tool::all();
       
        return view('admin.projects.tools', compact('project', 'tools'));
    }

    public function tools_store(StoreToolProjectRequest $request ,$projectId)
    {
        $project = Project::findOrFail($projectId);
        if ($project->client_id != Auth::id() && $project == null){
            abort(403, 'You are not allowed to view this page');
        }
        DB::transaction(function () use ($project, $request){
            $validated = $request->validated();
            $validated['project_id'] = $project->id;
           
            $toolProject = ProjectTool::firstOrCreate($validated);
        });

        return redirect()->route('admin.projects.tools', $project->id);
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
        //
    }
}

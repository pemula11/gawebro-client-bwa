<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreToolRequest;
use App\Http\Requests\UpdateToolRequest;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tools = Tool::orderByDesc('id')->paginate(5);
        return view('admin.tools.index', compact('tools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.tools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreToolRequest $request)
    {
        //
        DB::transaction(function () use ($request) {
            $validated = $request->validated();
            if ($request->hasFile('icon')){
                $iconPath = $request->file('icon')->store('icons', 'public');
                $validated['icon'] = $iconPath;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $tool = Tool::create($validated);
        });

        return redirect()->route('admin.tools.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tool $tool)
    {
        //
        return view('admin.tools.show', compact('tool'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tool $tool)
    {
        //
        return view('admin.tools.edit', compact('tool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateToolRequest $request, Tool $tool)
    {
        //
        DB::transaction(function () use ($request, $tool) {
            $validated = $request->validated();
            if ($request->hasFile('icon')){
                $iconPath = $request->file('icon')->store('icons', 'public');
                $validated['icon'] = $iconPath;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $tool->update($validated);

        });

        return redirect()->route('admin.tools.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tool $tool)
    {
        //
        try {
            DB::transaction( function () use ($tool) {
                $tool->delete();
                DB::commit();
                
            });
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('admin.tools.index')->withErrors('Failed to delete tool');
        }
        return redirect()->route('admin.tools.index')->with('success', 'Category deleted successfully');
    }
}

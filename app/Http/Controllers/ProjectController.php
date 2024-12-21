<?php

namespace App\Http\Controllers;

use App\Models\Project;  // إضافة هذا السطر لاستيراد كلاس Project
use App\Models\ApprovalChain;  // تأكد من استيراد ApprovalChain
use App\Models\cr;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all(); // استرجاع جميع المشاريع
        return view('projects.index', compact('projects')); // تمرير المشاريع إلى العرض
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createApprovalChain(Request $request, $project_id)
    {
        // Validation: Ensure users are unique
        $request->validate([
            'users' => 'required|array|distinct',  // Ensure no duplicates in user list
            'users.*' => 'exists:users,id'  // Ensure users exist in the system
        ]);

        $project = Project::findOrFail($project_id);

        // Create approval chain (you can define an ApprovalChain model)
        $approvalChain = ApprovalChain::create([
            'project_id' => $project_id,
            'users' => json_encode($request->users),  // Store users as JSON
        ]);

        return response()->json($approvalChain, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */

    public function showApprovalChain($projectId)
    {
        $projects = ApprovalChain::with('project')->with('user')->where('project_id', $projectId)->get();

        if ($projects->isEmpty()) {
            return response()->json(['message' => 'Projects not found'], 404);
        }

        // استرجاع سلسلة الموافقات الخاصة بالمشروع
        // $approvalChain = $project->approvalChain()->with('user')->get(); // تأكد من تحميل المستخدمين

        return view('projects.approval_chain', compact('projects'));
    }



    public function approvalChain(Project $project)
    {
        return view('projects.approval-chain', compact('project'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy(cr $cr)
    {
        //
    }

    public function approveAndForward(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $approvalChain = ApprovalChain::where('project_id', $project_id)->first();

        $users = json_decode($approvalChain->users);

        // Find the current user in the chain
        $currentUserIndex = array_search(auth()->id(), $users);

        if ($currentUserIndex === false) {
            return response()->json(['error' => 'You are not in the approval chain.'], 403);
        }

        // If this user is not the current approver, return error
        if ($currentUserIndex !== 0) {
            return response()->json(['error' => 'You cannot approve this project.'], 403);
        }

        // Approve the project and move to next user in chain
        array_shift($users);  // Remove the current user

        if (empty($users)) {
            $project->status = 'Completed';  // Mark as completed when last user approves
        }

        $approvalChain->users = json_encode($users);
        $approvalChain->save();
        $project->save();

        return response()->json(['message' => 'Project approved and forwarded.']);
    }

    public function getApprovalChain($project_id)
    {
        // dd($project_id);
        $project = Project::all();

        $project = Project::findOrFail($project_id);
        $approvalChain = ApprovalChain::where('project_id', $project_id)->first();

        return response()->json([
            'project' => $project,
            'approval_chain' => json_decode($approvalChain->users),
        ]);
    }

    public function approval() {}
}
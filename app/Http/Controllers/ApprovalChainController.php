<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\ApprovalChain;  // تأكد من استيراد ApprovalChain

use App\Models\cr;
use App\Models\User;
use Illuminate\Http\Request;



class ApprovalChainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Project $project)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'distinct|exists:users,id', // تأكد من أن المستخدمين مميزين
        ]);

        // إضافة سلسلة الموافقات للمشروع
        $project->approval_chain()->create([
            'users' => json_encode($request->users), // حفظ السلسلة في الـ DB
        ]);

        return response()->json(['message' => 'Approval chain created successfully.']);
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

     public function show(Project $project)
     {
         $approvalChain = $project->approvalChain()->with('user')->get();
         return response()->json($approvalChain);
     }
     
    // public function show(Project $project)
    // {
    //     // جلب سلسلة الموافقات للمشروع
    // $approvalChain = $project->approvalChain()->first();
    // $users = json_decode($approvalChain->users);

    // // تحديد المستخدم الحالي
    // $currentUser = auth()->user(); // أو استخدام التوثيق الخاص بك

    // // إرجاع البيانات مع حالة الموافقة
    // $approvalStatus = [];
    // foreach ($users as $userId) {
    //     $user = User::find($userId);
    //     $approvalStatus[] = [
    //         'id' => $user->id,
    //         'name' => $user->name,
    //         'approved' => in_array($user->id, $approvalChain->approved_users),
    //     ];
    // }

    // return response()->json([
    //     'approval_chain' => $approvalStatus,
    //     'current_user' => $currentUser,
    // ]);
    // }

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

    public function approve(Request $request, $projectId)
{
    // تحقق من وجود المشروع
    $project = Project::find($projectId);
    if (!$project) {
        return response()->json(['message' => 'Project not found'], 404);
    }

    // تحقق من وجود سلسلة الموافقة
    $approvalChain = ApprovalChain::where('project_id', $projectId)
        ->where('user_id', auth()->id())
        ->first();

    if (!$approvalChain) {
        return response()->json(['message' => 'You are not in the approval chain'], 403);
    }

    // تحديث حالة الموافقة
    $approvalChain->status = 'Approved';
    $approvalChain->save();

    // تحقق مما إذا كانت هذه هي الموافقة الأخيرة
    if (ApprovalChain::where('project_id', $projectId)->where('status', 'Pending')->count() == 0) {
        $project->status = 'Completed';
        $project->save();
    }

    return response()->json(['message' => 'Project approved successfully']);
    return redirect()->back()->with('success', 'Project approved successfully');
}


public function reject(Request $request, $projectId)
{
    $approvalChain = ApprovalChain::where('project_id', $projectId)
        ->where('user_id', auth()->id())
        ->first();

    if (!$approvalChain) {
        return response()->json(['message' => 'You are not in the approval chain'], 403);
    }

    $approvalChain->status = 'Rejected';
    $approvalChain->save();

    return response()->json(['message' => 'Project rejected successfully']);
    return redirect()->back()->with('success', 'Project rejected successfully');
}


}
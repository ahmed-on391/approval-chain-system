<?php


use App\Http\Controllers\api\ApprovalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalChainController;
use App\Http\Controllers\ProjectController;



/**
 * @OA\Get(
 *     path="/projects/{project}/approval-chain",
 *     summary="Get approval chain for a project",
 *     tags={"Approval Chain"},
 *     @OA\Parameter(
 *         name="project",
 *         in="path",
 *         description="Project ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Approval chain retrieved successfully"
 *     )
 * )
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/projects', [ProjectController::class, 'index'])->name('filament.resources.projects.index');

Route::post('/projects/{projectId}/approval-chain', [ApprovalChainController::class, 'createApprovalChain']);
Route::get('/projects/{projectId}/approval-chain', [ApprovalChainController::class, 'showApprovalChain']);



Route::get('/projects/{project_id}/approval-chain', [ProjectController::class, 'getApprovalChain']);


Route::post('/projects/{project_id}/approve', [ProjectController::class, 'approveAndForward']);

Route::post('/projects/{project}/approval-chain', [ProjectController::class, 'approvalChain']);

Route::get('/projects/{project}/approval-chain', [ProjectController::class, 'showApprovalChain']);


Route::post('/projects/{project_id}/approval-chain', [ProjectController::class, 'createApprovalChain']);

Route::post('/projects/{project}/approval-chain', [ApprovalChainController::class, 'create']);//API لإنشاء Approval Chain

Route::post('/projects/{project}/approve', [ApprovalChainController::class, 'approve']);//إنشاء API للموافقة والتوجيه 

Route::get('/projects/{project}/approval-chain', [ApprovalChainController::class, 'show']); //إضافة API لعرض سلسلة الموافقات

Route::get('/projects/{project}/approval-chain', [ProjectController::class, 'showApprovalChain'])->name('projects.approval-chain');


Route::post('/projects/{project}/approval-chain/approve', [ApprovalChainController::class, 'approve']);
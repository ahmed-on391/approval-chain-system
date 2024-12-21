<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Approval Chain API",
 *     description="API documentation for the project approval chain feature.",
 *     @OA\Contact(
 *         email=""
 *     )
 * )
 */
class ApprovalChainController extends Controller
{
    /**
     * @OA\Post(
     *     path="/projects/{projectId}/approval-chain",
     *     summary="Create approval chain for a project",
     *     tags={"Approval Chain"},
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Approval chain created successfully"
     *     )
     * )
     */
    public function createApprovalChain($projectId, Request $request) {
        // منطق إنشاء سلسلة الموافقة
        return response()->json(['message' => 'Approval chain created successfully.']);
    }

    /**
     * @OA\Get(
     *     path="/projects/{projectId}/approval-chain",
     *     summary="Get approval chain for a project",
     *     tags={"Approval Chain"},
     *     @OA\Parameter(
     *         name="projectId",
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
    public function showApprovalChain($projectId) {
        // منطق عرض سلسلة الموافقة
        return response()->json(['message' => 'Approval chain retrieved successfully.']);
    }
}
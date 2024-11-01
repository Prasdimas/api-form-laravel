<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Tag(
 *     name="Jobs",
 *     description="API Endpoints for Jobs",
 * )
 */
class JobController extends Controller
{
 /**
 * @OA\Get(
 *     path="/api/jobs",
 *     tags={"Jobs"},
 *     summary="Get a list of all jobs",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 {
 *                     "id": 1,
 *                     "name": "Frontend Web Developer",
 *                     "created_by": null,
 *                     "created_at": "2024-02-20T14:52:29.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-20T14:52:29.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null,
 *                     "candidates": {}
 *                 },
 *                 {
 *                     "id": 2,
 *                     "name": "Fullstack Web Developer",
 *                     "created_by": null,
 *                     "created_at": "2024-02-20T14:52:29.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-20T14:52:29.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null,
 *                     "candidates": {}
 *                 },
 *                 {
 *                     "id": 3,
 *                     "name": "Quality Control",
 *                     "created_by": null,
 *                     "created_at": "2024-02-20T14:52:29.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-20T14:52:29.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null,
 *                     "candidates": {}
 *                 }
 *             }
 *         ),
 *     ),
 * )
 */
public function index()
{
    $jobs = Job::with('candidates')->get();
    return response()->json(['jobs' => $jobs], 200);
}

/**
 * @OA\Get(
 *     path="/api/jobs/{id}",
 *     tags={"Jobs"},
 *     summary="Get job details by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Job ID",
 *         @OA\Schema(type="integer"),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "id": 1,
 *                 "name": "Frontend Web Developer",
 *                 "created_by": null,
 *                 "created_at": "2024-02-20T14:52:29.000000Z",
 *                 "updated_by": null,
 *                 "updated_at": "2024-02-20T14:52:29.000000Z",
 *                 "deleted_by": null,
 *                 "deleted_at": null,
 *                 "candidates": {}
 *             }
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Job not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Job not found"),
 *         ),
 *     ),
 * )
 */
public function show($id)
{
    $job = Job::with('candidates')->find($id);

    if (!$job) {
        return response()->json(['message' => 'Job not found'], 404);
    }

    return response()->json(['job' => $job], 200);
}

/**
 * @OA\Post(
 *     path="/api/jobs",
 *     tags={"Jobs"},
 *     summary="Create a new job",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             example={"name": "New Job Title"}
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Job created successfully",
 *         @OA\JsonContent(
 *             example={"job": {"id": 2, "name": "New Job Title"}}
 *         ),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="object", example={"name": {"The name field is required."}})
 *         ),
 *     ),
 * )
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $job = Job::create($request->all());
    return response()->json(['job' => $job], 201);
}

/**
 * @OA\Put(
 *     path="/api/jobs/{id}",
 *     tags={"Jobs"},
 *     summary="Update an existing job",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Job ID",
 *         @OA\Schema(type="integer"),
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             example={"name": "Updated Job Title"}
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Job updated successfully",
 *         @OA\JsonContent(
 *             example={"job": {"id": 2, "name": "Updated Job Title"}}
 *         ),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="object", example={"name": {"The name field must be a string."}})
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Job not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Job not found"),
 *         ),
 *     ),
 * )
 */
public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $job = Job::find($id);

    if (!$job) {
        return response()->json(['message' => 'Job not found'], 404);
    }

    $job->update($request->all());
    return response()->json(['job' => $job], 200);
}

/**
 * @OA\Delete(
 *     path="/api/jobs/{id}",
 *     tags={"Jobs"},
 *     summary="Delete an existing job",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Job ID",
 *         @OA\Schema(type="integer"),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Job deleted successfully",
 *         @OA\JsonContent(
 *             example={"message": "Job deleted successfully"}
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Job not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Job not found"),
 *         ),
 *     ),
 * )
 */
public function destroy($id)
{
    $job = Job::find($id);

    if (!$job) {
        return response()->json(['message' => 'Job not found'], 404);
    }

    $job->delete();
    return response()->json(['message' => 'Job deleted successfully'], 200);
}

}

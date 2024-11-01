<?php

namespace App\Http\Controllers;

use App\Models\SkillSet;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="SkillSets",
 *     description="API Endpoints for SkillSets",
 * )
 */
class SkillSetController extends Controller
{
 /**
 * @OA\Get(
 *     path="/api/skill-sets/",
 *     tags={"SkillSets"},
 *     summary="Get all skill sets",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 {
 *                     "candidate_id": 1,
 *                     "skill_id": 2,
 *                     "created_by": "2024-02-20T17:20:11.000000Z",
 *                     "created_at": null,
 *                     "updated_by": "2024-02-20T17:20:11.000000Z",
 *                     "updated_at": null,
 *                     "deleted_by": "2024-02-20T17:20:11.000000Z",
 *                     "deleted_at": null
 *                 },
 *                 {
 *                     "candidate_id": 2,
 *                     "skill_id": 1,
 *                     "created_by": null,
 *                     "created_at": "2024-02-20T18:31:17.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-20T18:31:17.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null
 *                 },
 *                 {
 *                     "candidate_id": 2,
 *                     "skill_id": 2,
 *                     "created_by": null,
 *                     "created_at": "2024-02-20T18:31:17.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-20T18:31:17.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null
 *                 },
 *                 {
 *                     "candidate_id": 3,
 *                     "skill_id": 1,
 *                     "created_by": null,
 *                     "created_at": "2024-02-21T00:23:05.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-21T00:23:05.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null
 *                 },
 *                 {
 *                     "candidate_id": 3,
 *                     "skill_id": 2,
 *                     "created_by": null,
 *                     "created_at": "2024-02-21T00:23:05.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-21T00:23:05.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null
 *                 }
 *             },
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="SkillSet not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="SkillSet not found"),
 *         ),
 *     ),
 * )
 */
    public function index()
    {
        $skillSets = SkillSet::with(['candidate', 'skill'])->get();
        return response()->json(['skillSets' => $skillSets], 200);
    }


/**
 * @OA\Get(
 *     path="/api/skill-sets/{candidateId}/{skillId}",
 *     tags={"SkillSets"},
 *     summary="Get a skill set by candidate ID and skill ID",
 *     @OA\Parameter(
 *         name="candidateId",
 *         in="path",
 *         required=true,
 *         description="Candidate ID",
 *         @OA\Schema(type="integer"),
 *     ),
 *     @OA\Parameter(
 *         name="skillId",
 *         in="path",
 *         required=true,
 *         description="Skill ID",
 *         @OA\Schema(type="integer"),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "data": {
 *                     "candidate_id": 1,
 *                     "skill_id": 2,
 *                     "created_by": "2024-02-20T17:20:11.000000Z",
 *                     "created_at": null,
 *                     "updated_by": "2024-02-20T17:20:11.000000Z",
 *                     "updated_at": null,
 *                     "deleted_by": "2024-02-20T17:20:11.000000Z",
 *                     "deleted_at": null
 *                 }
 *             },
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="SkillSet not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="SkillSet not found"),
 *         ),
 *     ),
 * )
 */

 public function show($candidateId, $skillId)
 {
     try {
         $skillSet = SkillSet::with(['candidate', 'skill'])
             ->where(['candidate_id' => $candidateId, 'skill_id' => $skillId])
             ->firstOrFail();

         return response()->json(['candidate_id' => $candidateId, 'skill_id' => $skillId, 'skillSet' => $skillSet], 200);
     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
         return response()->json(['message' => 'SkillSet not found'], 404);
     }
 }

 
 

    public function store(Request $request)
    {
        $skillSet = SkillSet::create($request->all());
        return response()->json(['skillSet' => $skillSet], 201);
    }

    public function update(Request $request, $candidateId, $skillId)
    {
        $skillSet = SkillSet::where(['candidate_id' => $candidateId, 'skill_id' => $skillId])->first();

        if (!$skillSet) {
            return response()->json(['message' => 'SkillSet not found'], 404);
        }

        $skillSet->update($request->all());
        return response()->json(['skillSet' => $skillSet], 200);
    }

    public function destroy($candidateId, $skillId)
    {
        $skillSet = SkillSet::where(['candidate_id' => $candidateId, 'skill_id' => $skillId])->first();

        if (!$skillSet) {
            return response()->json(['message' => 'SkillSet not found'], 404);
        }

        $skillSet->delete();
        return response()->json(['message' => 'SkillSet deleted successfully'], 200);
    }
}

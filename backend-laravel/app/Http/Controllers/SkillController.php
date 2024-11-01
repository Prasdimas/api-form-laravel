<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Skills",
 *     description="API Endpoints for Skills",
 * )
 */
class SkillController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/skills",
 *     tags={"Skills"},
 *     summary="Get a list of all skills",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "skills": {
 *                     {
 *                         "id": 1,
 *                         "name": "Programming",
 *                         "created_by": null,
 *                         "created_at": "2024-02-20T14:52:29.000000Z",
 *                         "updated_by": null,
 *                         "updated_at": "2024-02-20T14:52:29.000000Z",
 *                         "deleted_by": null,
 *                         "deleted_at": null,
 *                         "skillSets": {}
 *                     },
 *                     {
 *                         "id": 2,
 *                         "name": "Design",
 *                         "created_by": null,
 *                         "created_at": "2024-02-20T14:52:29.000000Z",
 *                         "updated_by": null,
 *                         "updated_at": "2024-02-20T14:52:29.000000Z",
 *                         "deleted_by": null,
 *                         "deleted_at": null,
 *                         "skillSets": {}
 *                     },
 *                     {
 *                         "id": 3,
 *                         "name": "Testing",
 *                         "created_by": null,
 *                         "created_at": "2024-02-20T14:52:29.000000Z",
 *                         "updated_by": null,
 *                         "updated_at": "2024-02-20T14:52:29.000000Z",
 *                         "deleted_by": null,
 *                         "deleted_at": null,
 *                         "skillSets": {}
 *                     }
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Skills not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Skills not found"),
 *         ),
 *     ),
 * )
 */
public function index()
{
    $skills = Skill::with('skillSets')->get();
    if (!$skills) {
        return response()->json(['message' => 'Skills not found'], 404);
    }
    return response()->json(['skills' => $skills], 200);
}

/**
 * @OA\Get(
 *     path="/api/skills/{id}",
 *     tags={"Skills"},
 *     summary="Get a skill by ID",
 *     @OA\Parameter(
 *         name="id",
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
 *                 "skill": {
 *                     "id": 1,
 *                     "name": "Programming",
 *                     "created_by": null,
 *                     "created_at": "2024-02-20T14:52:29.000000Z",
 *                     "updated_by": null,
 *                     "updated_at": "2024-02-20T14:52:29.000000Z",
 *                     "deleted_by": null,
 *                     "deleted_at": null,
 *                     "skillSets": {}
 *                 }
 *             }
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Skill not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Skill not found"),
 *         ),
 *     ),
 * )
 */
public function show($id)
{
    $skill = Skill::with('skillSets')->find($id);
    if (!$skill) {
        return response()->json(['message' => 'Skill not found'], 404);
    }
    return response()->json(['skill' => $skill], 200);
}


    /**
     * @OA\Post(
     *     path="/api/skills",
     *     tags={"Skills"},
     *     summary="Create a new skill",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="New Skill"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Skill created successfully",
     *         @OA\JsonContent(
     *             example={"skill": {"id": 4, "name": "New Skill", "created_by": null, "created_at": "2024-02-20T14:52:29.000000Z", "updated_by": null, "updated_at": "2024-02-20T14:52:29.000000Z", "deleted_by": null, "deleted_at": null, "skillSets": {}}}
     *         ),
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $skill = Skill::create($request->all());
        return response()->json(['skill' => $skill], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/skills/{id}",
     *     tags={"Skills"},
     *     summary="Update a skill by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Skill ID",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Skill"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skill updated successfully",
     *         @OA\JsonContent(
     *             example={"skill": {"id": 4, "name": "Updated Skill", "created_by": null, "created_at": "2024-02-20T14:52:29.000000Z", "updated_by": null, "updated_at": "2024-02-20T14:52:29.000000Z", "deleted_by": null, "deleted_at": null, "skillSets": {}}}
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Skill not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Skill not found"),
     *         ),
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json(['message' => 'Skill not found'], 404);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $skill->update($request->all());
        return response()->json(['skill' => $skill], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/skills/{id}",
     *     tags={"Skills"},
     *     summary="Delete a skill by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Skill ID",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skill deleted successfully",
     *         @OA\JsonContent(
     *             example={"message": "Skill deleted successfully"}
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Skill not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Skill not found"),
     *         ),
     *     ),
     * )
     */
    public function destroy($id)
    {
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json(['message' => 'Skill not found'], 404);
        }

        $skill->delete();
        return response()->json(['message' => 'Skill deleted successfully'], 200);
    }
}

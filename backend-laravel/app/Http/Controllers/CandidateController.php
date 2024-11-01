<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\SkillSet;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Candidates",
 *     description="API Endpoints for Candidates",
 * )
 */
class CandidateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/candidates",
     *     tags={"Candidates"},
     *     summary="Get all candidates",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 {
     *                     "id": 1,
     *                     "name": "John Doe",
     *                     "email": "john@example.com",
     *                     "phone": "123456789",
     *                     "year": 2022,
     *                     "skillSets": {
     *                         {
     *                             "id": 1,
     *                             "skill_id": 1
     *                         }
     *                     },
     *                     "job": {
     *                         "id": 1,
     *                         "title": "Software Engineer"
     *                     }
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function index()
    {
        $candidates = Candidate::with('job')->get();
        return response()->json(['candidates' => $candidates], 200);
    }

    /**
 * @OA\Get(
 *     path="/api/candidates/{id}",
 *     tags={"Candidates"},
 *     summary="Get a candidate by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Candidate ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "id": 1,
 *                 "name": "John Doe",
 *                 "email": "john@example.com",
 *                 "phone": "123456789",
 *                 "year": 2022,
 *                 "skillSets": {
 *                     {
 *                         "id": 1,
 *                         "skill_id": 1
 *                     }
 *                 },
 *                 "job": {
 *                     "id": 1,
 *                     "title": "Software Engineer"
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Candidate not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Candidate not found")
 *         )
 *     )
 * )
 */
public function show($id)
{
    $candidate = Candidate::with('skillSets.skill', 'job')->find($id);

    if (!$candidate) {
        return response()->json(['message' => 'Candidate not found'], 404);
    }

    return response()->json($candidate);
}


/**
 * @OA\Post(
 *     path="/api/candidates",
 *     tags={"Candidates"},
 *     summary="Create a new candidate",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             example={
 *                 "job_id": 1,
 *                 "name": "John Doe",
 *                 "email": "john@example.com",
 *                 "phone": "123456789",
 *                 "year": 2022,
 *                 "skill_sets": {
 *                     {"skill_id": 1},
 *                     {"skill_id": 2}
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *              @OA\Property(property="message", type="string", example="Email or phone number is already associated with another candidate")
 *         )
 *     )
 * )
 */
public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('candidates', 'email'),
                'regex:/^(.+?)@([a-zA-Z0-9-.]+)\.([a-zA-Z]{2,})$/',
                'regex:/^(?!(disposable.email|guerrillamail.com|mailinator.com)).*$/i',
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique('candidates', 'phone'),
            ],
            'year' => 'required|integer',
            'skill_sets.*.skill_id' => 'required|exists:skills,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $existingCandidateEmail = Candidate::where('email', $request->email)->first();
        $existingCandidatePhone = Candidate::where('phone', $request->phone)->first();

        if ($existingCandidateEmail || $existingCandidatePhone) {
            $errorMessages = [];

            if ($existingCandidateEmail) {
                $errorMessages[] = 'Email is already associated with another candidate';
            }

            if ($existingCandidatePhone) {
                $errorMessages[] = 'Phone number is already associated with another candidate';
            }

            return response()->json(['message' => $errorMessages], 422);
        }

        $candidate = Candidate::create($request->all());

        $this->updateSkillSets($candidate, $request->get('skill_sets'));

        return response()->json($candidate, 201);
    }


/**
 * @OA\Put(
 *     path="/api/candidates/{id}",
 *     tags={"Candidates"},
 *     summary="Update a candidate by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Candidate ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             example={
 *                 "job_id": 1,
 *                 "name": "Updated John Doe",
 *                 "email": "updated-john@example.com",
 *                 "phone": "987654321",
 *                 "year": 2023,
 *                 "skill_sets": {
 *                     {
 *                         "skill_id": 2
 *                     },
 *                     {
 *                         "skill_id": 3
 *                     }
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Candidate updated successfully",
 *         @OA\JsonContent(
 *             example={
 *                 "id": 1,
 *                 "name": "Updated John Doe",
 *                 "email": "updated-john@example.com",
 *                 "phone": "987654321",
 *                 "year": 2023,
 *                 "skillSets": {
 *                     {
 *                         "id": 2,
 *                         "skill_id": 2
 *                     },
 *                     {
 *                         "id": 3,
 *                         "skill_id": 3
 *                     }
 *                 },
 *                 "job": {
 *                     "id": 1,
 *                     "title": "Software Engineer"
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Candidate not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Candidate not found")
 *         )
 *     )
 * )
 */
public function update(Request $request, $id)
{
    $request->validate([
        'job_id' => 'required|exists:jobs,id',
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|numeric',
        'year' => 'required|integer',
    ]);

    $candidate = Candidate::find($id);

    if (!$candidate) {
        return response()->json(['message' => 'Candidate not found'], 404);
    }

    $candidate->update($request->all());

    $this->updateSkillSets($candidate, $request->get('skill_sets'));

    return response()->json($candidate);
}


    /**
     * @OA\Delete(
     *     path="/api/candidates/{id}",
     *     tags={"Candidates"},
     *     summary="Delete a candidate by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Candidate ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidate deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Candidate deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Candidate not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Candidate not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return response()->json(['message' => 'Candidate not found'], 404);
        }
        $candidate->skillSets()->delete();

        $candidate->delete();

        return response()->json(['message' => 'Candidate deleted']);
    }

    /**
     * Update skillSets for a candidate.
     *
     * @param  \App\Models\Candidate  $candidate
     * @param  array  $skillSets
     * @return void
     */
    private function updateSkillSets($candidate, $skillSets)
    {
        $candidate->skillSets()->delete();

        foreach ($skillSets as $skillSet) {
            SkillSet::create([
                'candidate_id' => $candidate->id,
                'skill_id' => $skillSet['skill_id'],
                'created_by' => null,
                'created_at' => now(),
            ]);
        }
    }
}

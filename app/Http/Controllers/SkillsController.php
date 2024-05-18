<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Http\Resources;

class SkillsController extends Controller
{
    /**
     * Display a listing of the skills.
     */
    public function index()
    {
        return SkillsResource::collection(Skill::all());
    }

    /**
     * Store a newly created skill in storage.
     */
    public function store(Request $request)
    {
        DbHelper::store(gettype(new Skill()), ['name']);
    }

    /**
     * Display the specified skill.
     */
    public function show($id)
    {         
        try {
            return new SkillsResource($request, DbHelper::findOrFail($id, gettype(new Skill())))
        } catch (\Exception $e) {
            return response()->json(['error'  => 'The skill with the following id does not exist'], 400);
        }
    }

    /**
     * Update the specified skill in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $skill = DbHelper::findOrFail($id, gettype(new Skill()));
            $skill->update($request->all());
            return $skill;
        } catch (\Exception $e) {
            return response()->json(['error' => 'The skill with the following ID does not exist', 400]);        
        }
    }

    /**
     * Remove the specified skill from storage.
     */
    public function destroy($id)
    {
        try {
            $skill = UserEducation::findOrFail($id, gettype(new Skill()));
            $skill->delete();
            return response()->json(['message' => 'Skill was deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete the skill'], 500);
        }
    }
}

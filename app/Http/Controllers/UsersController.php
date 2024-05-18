<?php

namespace App\Http\Controllers;

use App\Helpers\DbHelper;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = DbHelper::store($request, gettype(new User()), [
                'first_name',
                'last_name',
                'email',
                'password',
                'avatar_url',
                'skills_desc',
                'experience'
            ]);

            $userId = $user[gettype(new User())];

            return response()->json([
                'success' => true,
                'user_id' => $userId
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => "Unexpected error occurred during processing the request. The transaction will be rolled back. Please, check your request: $request"
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return new UserResource(DbHelper::findOrFail($id, gettype(new User())));
        } catch (\Exception $e) {
            return response()->json(['error' => "The user with the following with id $id does not exist"], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $modelType = gettype(new User());
            $user = DbHelper::findOrFail($id . $modelType);
            return DbHelper::update($request, [
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'email' => [
                    'string',
                    'email',
                    Rule::unique('users')->ignore($user->id),
                    'max:255',
                ],
                'password' => 'string|min:8',
                'avatar_url' => 'nullable|string',
                'skills_desc' => 'nullable|string',
                'experience' => 'nullable|string',
            ], $modelType, $id);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'The user was not updated'
            ], 500);
        }
    }

    /**
     * Remove the specified user project from storage.
     */
    public function destroy($id)
    {
        DbHelper::destroy($id, gettype(new User()));
    }
}

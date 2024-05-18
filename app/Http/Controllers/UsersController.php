<?php

namespace App\Http\Controllers;

use App\Helpers\DbHelper;
use App\Http\Resources\UserResource;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers;

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
        DbHelper::store($request, gettype(new User()), [
            'first_name',
            'last_name',
            'email',
            'password',
            'avatar_url',
            'skills_desc',
            'experience'
        ]);

        return response()->json([
            'success'  => true,
            'user_id' => $user->id
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return new UserResource(DbHelper::findOrFail($id, gettype(new User())));
        } catch (/Exception $e) {
            response()->json(['error'  => 'The user with the following id does not exist'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = DbHelper::findOrFail($id. gettype(new User()));

            $validatedData = $request->validate([
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
            ]);

            $user->update($validatedData);

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'The user was not updated'], 500);
        }
    }

    /**
     * Remove the specified user project from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id, DbHelper::findOrFail($id, gettype(new User())));
            $user->delete();
            return response()->json(['message' => 'User project deleted successfully'], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Fail to delete a user'], 500);
        }
    }
}

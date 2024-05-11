<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'password',
            'avatar_url',
            'skills_desc',
            'experience'
        ]);

        try {
            DB::beginTransaction();
            $user = User::create($data);
            DB::commit();
        } catch (QueryException $dbError) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'message' => $dbError->getMessage(),
                'code'    => 'db/error',
            ]);
        } catch (\Exception $error) {
            DB::rollBack();

            throw $error;
        }

        return response()->json([
            'success'  => true,
            'user_id' => $user->id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

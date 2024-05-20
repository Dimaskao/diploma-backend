<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
     /**
     * Register a new user or company ё.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required_if:role,user|string|max:255',
            'last_name' => 'required_if:role,user|string|max:255',
            'name' => 'required_if:role,company|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,company'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        $data['password'] = bcrypt($data['password']);

        $role = Role::where('name', $data['role'])->first();

        if (!$role) {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $data['role_id'] = $role->id;

        try {
            if ($data['role'] === 'company') {
                $company = Company::create($data);
                return response()->json(['company' => $company], 201);
            } else if ($data['role'] === 'user'){
                $user = User::create($data);
                return response()->json(['user' => $user], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user or company'], 500);
        }
    }

    /**
     * Log in user or company
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,company'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = $request->only('email', 'password');
        $role = $request->input('role');

        if ($role === 'user' && Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            $token = $user->createToken('AppName')->accessToken;
            return response()->json(['token' => $token], 200);
        } elseif ($role === 'company' && Auth::guard('company')->attempt($credentials)) {
            $company = Auth::guard('company')->user();
            $token = $company->createToken('AppName')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }

    /**
     * Log out user or company
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to log out user'], 500);
        }
    }

    /**
     * Display the specified user or company profile
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json(['user' => $user], 200);
        }

        $company = Company::find($id);
        if ($company) {
            return response()->json(['company' => $company], 200);
        }

        return response()->json(['error' => 'Profile not found'], 404);
    }

    /**
     * Update the specified user or company profile
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $company = Company::find($id);

        if (!$user && !$company) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'name' => 'string|max:255',
            // Add more fields for profile editing as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            if ($user) {
                $user->update($validator->validated());
                return response()->json(['user' => $user], 200);
            } else {
                $company->update($validator->validated());
                return response()->json(['company' => $company], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update profile'], 500);
        }
    }

    /**
     * Add contact information to user or company profile
     */
    public function addContactInfo(Request $request, $id)
    {
        $user = User::find($id);
        $company = Company::find($id);

        if (!$user && !$company) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'string|email|unique:users|unique:companies|max:255',
            'phone' => 'string|max:20',
            // Add more contact fields as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            if ($user) {
                $user->update($validator->validated());
                return response()->json(['user' => $user], 200);
            } else {
                $company->update($validator->validated());
                return response()->json(['company' => $company], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update contact information'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

// use Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => $user,
        ], 201);
    }

    /**
     * Login the user and create a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::error('Unauthorized login attempt for email: ' . $request->email);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('token-name', ['role:'.$user->role_id])->plainTextToken;

        // Set the token expiration here (e.g., 60 minutes)
        $user->tokens->last()->update(['expires_at' => now()->addMinutes(1440)]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 1440,
            'role_id' => $user->role_id,
        ]);
    }

    public function getUser()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::with('studyClass')->find($user->id);

        return response()->json(['user' => $user]);
    }

    public function updateUser(Request $request, int $id)
    {
        $request->validate([
            'name' => 'nullable',
            'email' => 'nullable',
        ]);

        $user = User::find($id);

        if ($user) {
            // Update the user data
            $user->name = $request->input('name', $user->name);
            $user->email = $request->input('email', $user->email);

            $user->save();

            $updatedUser = User::find($id);

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'User updated successfully',
                    'user' => $updatedUser,
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'No such user found',
                ],
                500
            );
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logged out successfully',
        ], 200);
    }
}

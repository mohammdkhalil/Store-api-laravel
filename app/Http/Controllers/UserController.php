<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers(Request $request)
    {
        // if (!$request->user()->role == RoleEnum::ADMIN) {
        //      return response()->json([
        //          'error' => 'the role must be admin'
        //      ], 403);
        //  }
        // if (!$request->user() || $request->user()->role !== RoleEnum::ADMIN) {
        //     return response()->json([
        //         'error' => 'The role must be admin'
        //     ], 403);
        // }

        try {
            $users = User::all();
            return response()->json([
                'users' => $users,
                'message' => __('Success'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode());
        }

    }
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                response()->json(['error' => 'the user not found'], 400);
            }
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete user'], 500);
        }
    }

}

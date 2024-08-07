<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManajemenUserStoreRequest;
use App\Http\Requests\ManajemenUserUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ManajemenUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Superadmin.Manajemen-User.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('id', 'uuid', 'name', 'username', 'email', 'role', 'status');
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManajemenUserStoreRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
            ]);

            return response()->json(['message' => 'User created successfully.', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create user.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     $loggedInUser = auth()->user();
    //     if ($loggedInUser->id == $id) {
    //         return response()->json(['error' => true, 'message' => 'You cannot edit your own profile.'], 403);
    //     }
    //     $user = User::find($id);
    //     if (!$user) {
    //         return response()->json(['error' => true, 'message' => 'User not found.'], 404);
    //     }
    //     return response()->json(['error' => false, 'data' => $user]);
    // }
    public function edit($uuid)
    {
        $loggedInUser = auth()->user();
        if ($loggedInUser->uuid == $uuid) {
            return response()->json(['error' => true, 'message' => 'You cannot edit your own profile.'], 403);
        }
        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            return response()->json(['error' => true, 'message' => 'User not found.'], 404);
        }
        return response()->json(['error' => false, 'data' => $user]);
    }




    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {

    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json(['message' => 'User not found.'], 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'username' => 'required|string|max:255|unique:users,username,' . $id,
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $id,
    //         'password' => 'nullable|string|min:8',
    //         'role' => 'required|in:superadmin,kadiv,pegawai',
    //         'status' => 'required|in:aktif,nonaktif',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $user->update([
    //         'name' => $request->name,
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'password' => $request->password ? Hash::make($request->password) : $user->password,
    //         'role' => $request->role,
    //         'status' => $request->status,
    //     ]);

    //     return response()->json(['message' => 'User updated successfully.', 'user' => $user], 200);
    // }
    public function update(ManajemenUserUpdateRequest $request, string $uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'role' => $request->role,
                'status' => $request->status,
            ]);

            return response()->json(['message' => 'User updated successfully.', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user.', 'error' => $e->getMessage()], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $loggedInUser = auth()->user();
    //     if ($loggedInUser->id == $id) {
    //         return response()->json(['error' => true, 'message' => 'You cannot edit your own profile.'], 403);
    //     }

    //     $user = User::find($id);
    //     if ($user) {
    //         $user->delete();
    //         return response()->json(['message' => 'User deleted successfully.']);
    //     }
    //     return response()->json(['message' => 'User not found.'], 404);
    // }
    public function destroy($uuid)
    {
        $loggedInUser = auth()->user();
        if ($loggedInUser->uuid == $uuid) {
            return response()->json(['error' => true, 'message' => 'You cannot delete your own profile.'], 403);
        }

        $user = User::where('uuid', $uuid)->first();
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }
}

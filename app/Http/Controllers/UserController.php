<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.manage-user', ['users' => $users]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->number_phone = $request->number_phone;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->state = 1;
        $user->role = $request->role;
        $user->save();
        return response()->json($user, 200);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->number_phone = $request->number_phone;
        $user->email = $request->email;
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }
        $user->role = $request->role;
        $user->save();
        return response()->json($user, 200);
    }

    public function update_status($id, Request $request)
    {
        $user = User::find($id);
        $user->state = $request->state;
        $user->save();
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json($user, 200);
    }
}

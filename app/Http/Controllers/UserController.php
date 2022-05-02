<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('users.manage-user',['users'=> $users]);
    }

    public function show($id){
        $user = User::find($id);
        return response()->json($user, 200);
    }

    public function store(Request $request){
        $user = new User();
        $user->usuario = $request->username;
        $user->nombres = $request->name;
        $user->apellidos = $request->lastname;
        $user->telefono = $request->phone;
        $user->correo = $request->email;
        $user->password = bcrypt($request->password);
        $user->estado = 1;
        $user->rol = $request->rol;
        $user->save();
        return response()->json($user, 200);
    }

    public function update(Request $request){
        $user = User::find($request->id);
        $user->usuario = $request->username;
        $user->nombres = $request->name;
        $user->apellidos = $request->lastname;
        $user->telefono = $request->phone;
        $user->correo = $request->email;
        if($request->password != null){
            $user->password = bcrypt($request->password);
        }
        $user->rol = $request->rol;
        $user->save();
        return response()->json($user, 200);
    }

    public function update_status($id,Request $request){
        $user = User::find($id);
        $user->estado = $request->status;
        $user->save();
        return response()->json($user, 200);
    }

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
        return response()->json($user, 200);
    }
}

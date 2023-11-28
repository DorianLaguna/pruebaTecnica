<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getToken(Request $request){

        $data = $request->validate([
            'user' => 'required',
            'password' => 'required'
        ]);

        if(!auth()->attempt($data)){
            return response()->json(['error' => 'Incorrect credentials'], 401);
        }
        
        $token = $request->user()->createToken('token')->plainTextToken;
        $dateFinish = now()->addMinutes(config('sanctum.expiration'));

        return response()->json(['token' => $token, 'date_finish' => $dateFinish]);
    }

    public function create(Request $request){

        //validate
        $data = Validator::make($request->all(),[
            'user' => 'required|string|unique:users',
            'name' => 'required',
            'phone' => 'required|numeric',
            'password' => 'required',
            'consent_Id1' => 'required|accepted',
            'consent_Id2' => 'required',
            'consent_Id3' => 'required'
        ]);
        if($data->fails()){
            return response()->json(['error' => $data->errors()], 401);
        }
        //password hashed from User model
        $user = User::create([
            'user' => $request->user,
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
            'consent_Id1' => $request->consent_Id1 ? 1 : 0,
            'consent_Id2' => $request->consent_Id2 ? 1 : 0,
            'consent_Id3' => $request->consent_Id3 ? 1 : 0
        ]);

        return response()->json(['message' => 'User created','user_id' => $user->id]);
    }

    public function update(Request $request){

        //validate
        $data = Validator::make($request->all(),[
            'idUser' => 'required|numeric',
            'user' => 'required|string',
            'name' => 'required',
            'phone' => 'required|numeric',
            'password' => 'required',
            'consent_Id1' => 'required|accepted',
            'consent_Id2' => 'required',
            'consent_Id3' => 'required'
        ]);
        if($data->fails()){
            return response()->json(['error' => $data->errors()], 401);
        }


        $user = User::find($request->idUser)->first();
        if(!$user){
            return response()->json(['error' => 'User not found'], 401);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'consent_Id1' => $request->consent_Id1 === 'true' ? 1 : 0,
            'consent_Id2' => $request->consent_Id2 === 'true' ? 1 : 0,
            'consent_Id3' => $request->consent_Id3 === 'true' ? 1 : 0
        ]);

        return response()->json(['user_id' => $user->id]);
    }

    public function delete(Request $request){

        //validate
        $data = Validator::make($request->all(),[
            'idUser' => 'required|numeric'
        ]);
        if($data->fails()){
            return response()->json(['error' => $data->errors()], 401);
        }

        //find user
        $user = User::where('id', $request->idUser)->first();
        if(!$user){
            return response()->json(['error' => 'User not found'], 401);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}

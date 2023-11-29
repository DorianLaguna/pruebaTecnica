<?php

namespace App\Http\Controllers;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Consent;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function create(Request $request){

        //validate
        $validator = Validator::make($request->all(),[
            'user' => 'required|string|unique:users',
            'name' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string',
            'consent_Id1' => 'required|accepted',
            'consent_Id2' => 'required',
            'consent_Id3' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

        //check if user is the same
        // if(auth()->user()->id != $request->idUser){
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }


        //password hashed from User model
        $user = User::create([
            'user' => $request->user,
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
            'consent_Id1' => 1,
            'consent_Id2' => $request->consent_Id2 === 'true' ? 1 : 0,
            'consent_Id3' => $request->consent_Id3 === 'true' ? 1 : 0
        ]);

        //create uniqueId with 30 characters
        $uniqueId2 = substr(Uuid::uuid4()->toString(), 0, 30);
        $uniqueId3 = substr(Uuid::uuid4()->toString(), 0, 30);

        Consent::create([
            'user_id' => $user->id,
            'consent_Id2' => $uniqueId2,
            'consent_Id3' => $uniqueId3,
        ]);

        return response()->json(['message' => 'User created','user_id' => $user->id]);
    }

    public function update(Request $request){

        //validate
        $validator = Validator::make($request->all(),[
            'idUser' => 'required|numeric',
            'user' => 'required|sometimes|string|unique:users',
            'name' => 'required|sometimes',
            'phone' => 'required|sometimes|numeric',
            'password' => 'required|sometimes',
            'consent_Id1' => 'required|sometimes|accepted',
            'consent_Id2' => 'required|sometimes',
            'consent_Id3' => 'required|sometimes'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

         //check if user is the same
        // if(auth()->user()->id != $request->idUser){
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        //find user
        $user = User::where('id',$request->idUser)->first();
        if(!$user){
            return response()->json(['error' => 'User not found'], 401);
        }

        //get fields to update
        $fieldsToUpdate = $request->only([
            'user',
            'name',
            'phone',
            'password',
            'consent_Id2',
            'consent_Id3'
        ]);
        

        //become to boolean
        foreach (['consent_Id2', 'consent_Id3'] as $field) {
            if (isset($fieldsToUpdate[$field])) {
                $fieldsToUpdate[$field] = $fieldsToUpdate[$field] === 'true' ? 1 : 0;

                //check if consent has changed
                if ($user->$field != $fieldsToUpdate[$field]) {
                    History::create([
                        'user_id' => $user->id,
                        'action' => "$field changed to $fieldsToUpdate[$field]",
                        'date' => now(),
                    ]);
                }
            }
        }

        $user->update($fieldsToUpdate);

        return response()->json(['message' => 'User updated']);
    }

    public function delete(Request $request){
        
        //validate
        $validator = Validator::make($request->all(),[
            'idUser' => 'required|numeric'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }
        
        //check if user is the same
        // if(auth()->user()->id != $request->idUser){
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        //find user
        $user = User::where('id', $request->idUser)->first();
        if(!$user){
            return response()->json(['error' => 'User not found'], 401);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}

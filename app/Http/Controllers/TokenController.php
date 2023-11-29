<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TokenController extends Controller
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
        $dateFinish = now()->addMinutes(config('sanctum.expiration'))->format('Y-m-d H:i:s');

        return response()->json(['token' => $token, 'date_finish' => $dateFinish]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    public function createCard(Request $request){

         //check if user is the same
         if(auth()->user()->id != $request->idUser){
            return response()->json(['error' => 'Unauthorized: User diferent'], 401);
        }

        $validator = Validator::make($request->all(),[
            'card_number' => 'required|size:16',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

        $uniqueId1 = substr(Uuid::uuid4()->toString(), 0, 30);

        Card::create([
            'user_id' => auth()->user()->id,
            'consent_Id1' => $uniqueId1,
            'card_number' => $request->card_number,
        ]);

        return response()->json(['message' => 'Card created']);

    }

    public function deleteCard(Request $request){

        $validator = Validator::make($request->all(),[
            'id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

         //check if user is the same
         
         $card = Card::where('id', $request->id)->first();
         
         if(auth()->user()->id != $card->user_id){
             return response()->json(['error' => 'Unauthorized: User diferent'], 401);
         }
         
        if($card){
            $card->delete();
            return response()->json(['message' => 'Card deleted']);
        }else{
            return response()->json(['error' => 'Card not found'], 401);
        }

    }
}

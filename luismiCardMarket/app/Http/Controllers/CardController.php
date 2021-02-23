<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\User;
use App\Models\Collection;

class CardController extends Controller
{
    public function createCard(Request $request){

        $response = "";

        $data = $request->getContent();

        $data = json_decode($data);

        if($data!=""){


            if($data){

                $card = new Card();

                $card->cardname = $data->cardname;
                $card->description = $data->description;
               

                try{
                    $card->save();
                    $response = "Datos guardados";

                }catch(\Exception $e){
                    $response = $e->getMessage();
                }
            
            }
        }else{
            $response = "Error";
        }

        return response($response);
    
    }   
}
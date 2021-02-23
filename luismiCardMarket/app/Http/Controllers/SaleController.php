<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Card;
use App\Models\Collection;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function createSale(Request $request){

        $response = "";
    
        //Getting data from request
        $data = $request->getContent();
    
        
        $data = json_decode($data);
    
        if($data!=""){
        
            if($data){
    
                //Create user
                $sale = new Sale();
    
    
                //Required data
                $sale->card_id = $data->card;
                $sale->amount =$data->amount;
                $sale->price = $data->price;
                $sale->user_id = $data->user;

                //Save sale
                try{
    
                    $sale->save();
    
                    $response = "OK. Data saved";
                }catch(\Exception $e){
                    $response = $e->getMessage();
                }
    
            }
    
        }else{
            $response = "Wrong data";
        }
        
    
    
        return response($response);
    }



    //BUSCADOR DE CARTAS POR NOMBRE O ID
    public function searchCard(Request $request){

        Log::info("Entra a la funcion de busqueda");

    $response = "";
    $busqueda = [];
    $price = 0;
    $amount = 0;

    $data = $request->getContent();

    $data = json_decode($data);
    
   // Log::info("Request data: ".print_r($data));


if(empty($data->name)){
    Log::debug("Solicitud de busqueda filtro vacio");
    Log::error("Parámetros vacios");
    
    return "El parámetro de busqueda está vacío";

    
}if(!Card::where('cardname','=',$data->name)->first()){

    Log::debug("Nombre de la carta: ".$data->name);
    Log::error("Nombre de la carta incorrecto");
    return "Nombre incorrecto";
    
}elseif(Card::where('cardname','=',$data->name)->first()){
    Log::debug("Nombre de la carta: ".$data->name);

        $cardID = Card::where('cardname',$data->name)->value('id');
        Log::debug("ID carta introducida: ".$cardID);

        Log::debug("Filtrando resultado");

        $search = [];


        $cards = Card::where('cardname', $data->name)->get()->toArray();
        $sale = Sale::orderByRaw('price','ASC')->get();
          
        $users = User::all();

        

        if($cards){
            foreach($cards as $card){

                foreach($sale as $sales){

                    if($card['id'] == $sales['card_id']){
                        Log::debug("Total cartas obtenidas en la base de datos ".count($cards));
                        Log::debug("Total cartas que estan a la venta ".($sales['amount']));
                        foreach($users as $user){

                            if($user['id'] == $sales['user_id']){

                                $search[] = [
                                
                                    //"Card ID" =>  $card['id'],
                                    "Card name" =>  $card['cardname'],
                                    "Amount" =>  $sales['amount'],
                                    "Seller username" => $user['username'],
                                   "Price" => $sales['price'],
                                ];

                                
                            }
                        }
                    }
                }
            }

            $response = $search;

        }else{

            Log::debug("No existen cartas de ese nombre en venta");
           // Log::debug("Total cartas obtenidas en ventas ".count($cards));

            $response = "Carta no encontrada";
        }


       

     return response($response);
    }

}
}

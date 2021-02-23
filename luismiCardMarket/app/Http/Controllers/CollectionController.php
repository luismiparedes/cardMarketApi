<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Collection;
use App\Models\CardsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function cardCollectionStore(Request $request){
        
        $response = "";
        $isStored = false;
        $data = $request->data;
        $data = json_decode($data);

        $collection = new Collection();
        $collections = Collection::all();

        $card = new Card();
        $cards = Card::all();

        $index = new CardsCollection();
        if($data){

            if($request->hasFile('symbol')){

                $path = $request->file('symbol')->getRealPath();
                $symbol = file_get_contents($path);
                $base64 = base64_encode($symbol);
                $collection->symbol = $base64;
             
            }
          
            if($isStored){
                $response = "Esta coleccion ya existe.";
            }else{
                $collection->name = $data->name;

                foreach($cards as $savedCard){
                    if($data->cardname ==$savedCard->name){

                        $savedCardID = $savedCard->id;

                    }

                }
            
            try{

                $collection->save();

                if(Str::contains($request->symbol, 'png')){
                    $response .= "<img src='data:image/png;base64,".$collection->symbol."'>";
                }else{
                    $response .= "<img src='data:image/jpeg;base64,".$collection->symbol."'>";
                }

                if(isset($savedCardID)){
                    $index->card_id = $savedCardID;
                    $response = "La carta se añadió a la collecion correctamente";

                }else{
                    $card->cardname = $data->cardname;
                    $card->description = $data->description;
                    $card->save();
                    $index->card_id = $card->id;
                    $response = "La coleccion se creó correctamente con la carta";

                }
                $index->collection_id = $collection->id;
                $index->save();
            }catch(\Exception $e){
                $response = $e->getMessage();
            }
        }
        }else{
            $response = "Error";
        }
        return response($response."<img src='data:image/jpeg;base64,".$collection->symbol."'>");
 
    }
}

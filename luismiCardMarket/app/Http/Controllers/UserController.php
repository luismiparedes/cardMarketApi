<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function createUser(Request $request){

        $response = "";

        $data = $request->getContent();

        $data = json_decode($data);

        if($data!=""){


            if($data){

                $user = new User();

                $user->username = $data->username;
                $user->password = Hash::make($data->password);
                $user->email = $data->email;
                $user->rol = $data->rol;

                try{
                    $user->save();
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

    public function loginUser(Request $request){

        $response = "";

        $data = $request->getContent();

        $data = json_decode($data);

        //cogemos password desde username
        $getPassword = User::where('username', $data->username)->value('password');

        $responsePassword = $data->password;

        //Buscamos el ID corrspondiente en la base de datos

        $loggedUserId = User::where('username',$data->username)->value('id');
        $loggedUser=User::find($loggedUserId);


        if($data!=""){
            if($data){
                
                //comprobamos si el usuario y la contraseña estan en la base de datos.
                if(User::where('username', '=', $data->username)->exists() && Hash::check($responsePassword, $getPassword)){

                    $token = $loggedUser->createToken('general')->accessToken;

                    $loggedUser ->api_token = $token;
                    $loggedUser->save();
                    $userRol = User::where('username', $data->username)->value('rol');

                    return response()->json([

                        'respuesta' => 'Accediste como'." ".$userRol,

                        'token'=>$token,
                        'id'=>$loggedUser->id

                    ]);


                }else{
                    return 'Nombre o contraseña incorrecta';
                }
            }
        }else{
            $response = 'Error datos introducidos';
        }
    }

    public function resetPassword(Request $request){
        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);

        $user = User::where('email', $data->email)->first();

        $userPassword = User::where('email', $data->email)->value('password');

        if(isset($userPassword)){
            $generatePassword = uniqid();
            $newPassword = Hash::make($generatePassword);

            $user->password = $newPassword;

            $user->save();
                return "La contraseña se cambió correctamente a:  ". $generatePassword;
        }
        return response($response);

    }

}

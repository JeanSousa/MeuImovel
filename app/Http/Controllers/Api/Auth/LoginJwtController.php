<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LoginJwtController extends Controller
{
    public function login(Request $request)
    {  
        $credentials = $request->all(['email', 'password']);

        Validator::make($credentials, [
            'email' => 'required|string',
            'password' => 'required|string',
        ])->validate();

        //o helper auth retorna por padrao uma instancia de guards web, 
        //mas vou informar que preciso da api 
        if(!$token = auth('api')->attempt($credentials)) {
            //o metodo attempt ira tentar logar o usuario, caso consiga 
            // logar ele retornara o 
            // token na variavel $token
            $message = new ApiMessages('Unauthorized');
        
            return response()->json($message->getMessage(), 401);
        }

        //caso ele consiga logar e retornar o token na variavel $token
        return response()->json([
            'token' => $token
        ]);


    }

    public function logout()
    {   //ao fazer logout o token caira na blacklist
        //devendo assim solicitar outro token
        auth('api')->logout();
     
        return response()->json([
            'message' => 'Logout Sucessfully!'
        ],200);
    }

    //metodo para disponibilizar um endpoint para dar um refresh
    //para atualizar o token
    public function refresh()
    {  
       $token = auth('api')->refresh();

       return response()->json([
        'token' => $token
       ]);
    }


}

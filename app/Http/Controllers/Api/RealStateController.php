<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\RealState;


class RealStateController extends Controller
{
    private $realState;


    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $realState = auth('api')->user()->real_state();

        return response()->json($realState->paginate('10'), 200);

    }

    public function show($id)
    {
        try {

            $realState = auth('api')->user()->real_state()->with('photos')->findOrFail($id);  


            return response()->json([
                'data' => $realState
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        
        $images = $request->file('images');

        try {

            $data['user_id'] = auth('api')->user()->id;

            $realState = $this->realState->create($data);  //mass assigment

            if(isset($data['categories']) && count($data['categories'])) {
                //aqui o objeto do realstate vai usar o metodo categories
                //para salvar seu id e o id da categoria na tabela real_state_categories
                $realState->categories()->sync($data['categories']);

            }

            if ($images) {
                $imagesUploaded = [];

                foreach ($images as $image) { 
                   //2 parametro de store é o driver
                  //1 parametro nome da pasta criada no caminho do driver
                   $path = $image->store('images', 'public');

                   $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                //create many vai criar muitos registros na tabela imagens referente ao real state
                //vai criar quantas estiver no array
                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imovel cadastrado com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }


        
    }


    public function update($id, RealStateRequest $request)
    {
        $data = $request->all();

        try {

            $realState = auth('api')->user()->real_state()->findOrFail($id);  

            $realState->update($data);

            if(isset($data['categories']) && count($data['categories'])) {
                //aqui o objeto do realstate vai usar o metodo categories
                //para salvar seu id e o id da categoria na tabela real_state_categories
                $realState->categories()->sync($data['categories']);

            }

            //para atualizar e conseguir mandar o binario foto e o texto normal
            //precisamos enviar via post para ser interpretado o formulario como form-data
            //e mandamos a diretiva _method= put para atualizar 
            $images = $request->file('images');

            if ($images) {
                $imagesUploaded = [];

                foreach ($images as $image) { 
                   //2 parametro de store é o driver
                  //1 parametro nome da pasta criada no caminho do driver
                   $path = $image->store('images', 'public');

                   $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                //create many vai criar muitos registros na tabela imagens referente ao real state
                //vai criar quantas estiver no array
                $realState->photos()->createMany($imagesUploaded);
            }


            return response()->json([
                'data' => [
                    'msg' => 'Imovel atualizado com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }


    public function destroy($id)
    {
        try {

            $realState = auth('api')->user()->real_state()->findOrFail($id); 

            $realState->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Imovel removido com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}

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
        $realState = $this->realState->paginate('10');

        return response()->json($realState, 200);

    }

    public function show($id)
    {
        try {

            $realState = $this->realState->findOrFail($id);  


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

        try {

            $realState = $this->realState->create($data);  //mass assigment

            if(isset($data['categories']) && count($data['categories'])) {
                //aqui o objeto do realstate vai usar o metodo categories
                //para salvar seu id e o id da categoria na tabela real_state_categories
                $realState->categories()->sync($data['categories']);

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

            $realState = $this->realState->findOrFail($id);  

            $realState->update($data);

            if(isset($data['categories']) && count($data['categories'])) {
                //aqui o objeto do realstate vai usar o metodo categories
                //para salvar seu id e o id da categoria na tabela real_state_categories
                $realState->categories()->sync($data['categories']);

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

            $realState = $this->realState->findOrFail($id);  

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

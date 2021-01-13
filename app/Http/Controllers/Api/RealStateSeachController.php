<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\RealState;
use App\Repository\RealStateRepository;
use Illuminate\Http\Request;

class RealStateSeachController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }


    public function index(Request $request)
    {
       
       

       $repository = new RealStateRepository($this->realState);

       //conditions=name:X;price=x
       if ($request->has('conditions')) {
           $repository->selectConditions($request->get('conditions'));
       }

 
       if ($request->has('fields')) {
           $repository->selectFilter($request->get('fields'));
       }


       $repository->setLocation($request->all(['state', 'city']));


       return response()->json([
         'data' => $repository->getResult()->paginate(10)
       ], 200);
    }


    public function show($id)
    {
        //
    }

}

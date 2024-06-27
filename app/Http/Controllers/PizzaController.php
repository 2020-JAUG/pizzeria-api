<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Exception;

class PizzaController extends Controller
{
    use ApiResponder;
    protected $pizzaRepository;

    public function __construct()
    {
        $this->pizzaRepository = app()->make(\App\Repository\PizzaRepository::class);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'ingredients' => 'required'
            ]);

            $filePath = $this->checkFile($request);
            $pizza = $this->pizzaRepository->createNewPizza($request);
        } catch (Exception $ex) {
            return $this->errorResponse($ex, 400);
        }

        return $this->successJsonResponse('Pizza created', $pizza, 201);
    }

    public function index(Request $request)
    {
        try {
            $options = $request->input('options', []);
            $pizzas = $this->pizzaRepository->getPizzas($options);
        } catch (Exception $ex) {
            return $this->errorResponse($ex, 400);
        }
        return $this->successJsonResponse('Ok', $pizzas, 200);
    }

    public function show($id)
    {
        try {

            $findPizza = $this->pizzaRepository->show($id);
        } catch (Exception $ex) {
            return $this->errorResponse($ex, 400);
        }
        return $this->successJsonResponse('Ok', $findPizza, 200);
    }


    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required',
                'ingredients' => 'required|sometimes'
            ]);

            if ($request->hasFile('file')) {
                $this->checkFile($request);
            }

            $updatePizza = $this->pizzaRepository->update($request, $id);
        } catch (Exception $ex) {
            return $this->errorResponse($ex, 400);
        }
        return $this->successJsonResponse('Pizza updated', $updatePizza, 200);
    }


    public function delete($id)
    {
        try {
            $response = $this->pizzaRepository->delete($id);
        } catch (Exception $ex) {
            return $this->errorResponse($ex, 400);
        }
        return $this->successJsonResponse('Pizza deleted', $response, 200);
    }

    private function checkFile(Request $request)
    {
        if ($request->hasFile('files')) {
            $rules = ['files.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048'];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $validator->errors()->all();
            }
        }
        return null;
    }
}

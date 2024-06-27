<?php


namespace App\Repository;

use App\Models\Pizza;
use App\Helpers\PaginationHelper;
use App\Helpers\S3Helper;
use Illuminate\Support\Facades\DB;
use Exception;

class PizzaRepository
{
    public function getPizzas(array $options)
    {
        $query = Pizza::query();
        $pizzas = PaginationHelper::paginate($query, $options);

        $transformedPizzas = $pizzas->getCollection()->map(function ($pizza) {
            return [
                'name' => $pizza->name,
                'price' => $pizza->price . ' €',
                'ingredients' => $pizza->ingredients,
            ];
        });

        if ($pizzas instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $pizzas->setCollection($transformedPizzas);
        }

        return $pizzas;
    }


    public function createNewPizza($request)
    {
        DB::beginTransaction();
        try {

            //$filePath = $this->uploadFile($request);

            $pizza = Pizza::create([
                'name' => $request['name'],
                'ingredients' =>  json_decode($request->input('ingredients'), true),
                //'image' => $filePath ?? null
            ]);

            if ($request->hasFile('files')) {

                foreach ($request->file('files') as $file) {
                    S3Helper::putImage($file, $pizza);
                }
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }

        return [
            'id' => $pizza->id,
            'name' => $pizza->name,
            'ingredients' => $pizza->ingredients,
            'images' => $pizza->simple_media
        ];
    }

    public function show($id)
    {
        try {
            $pizza = Pizza::findOrFail($id);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $pizza;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {

            // if ($request->hasFile('file')) {
            //     $filePath = $this->uploadFile($request);
            // }

            $pizza = Pizza::findOrFail($id);
            $pizza->update([
                'name' => $request['name'] ?? $pizza->name,
                'ingredients' => is_string($request->input('ingredients')) ? json_decode($request->input('ingredients'), true) : $request->input('ingredients') ?? $pizza->ingredients,
                //'image' => $filePath ?? $pizza->image
            ]);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            report($ex);
            throw $ex;
        }
        return $pizza;
    }


    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $pizza = Pizza::findOrFail($id);
            $pizza->delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            report($ex);
            throw $ex;
        }
        return true;
    }
}

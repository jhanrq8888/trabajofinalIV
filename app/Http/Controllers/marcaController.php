<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MarcaController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $marcas = Marca::with('caracteristica')->get();
        return $this->successResponse($marcas);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'caracteristica_id' => 'required|exists:caracteristicas,id', // Asegura que exista en la tabla caracteristicas
        ];

        $this->validate($request, $rules);

        $marca = Marca::create($request->all());

        return $this->successResponse($marca, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'caracteristica_id' => 'required|exists:caracteristicas,id', // Asegura que el ID de la característica exista en la tabla 'caracteristicas'
        ];
        $this->validate($request, $rules);

        $marca = Marca::findOrFail($id);
        $marca->fill($request->all());

        if ($marca->isClean()) {
            return $this->errorResponse('Al menos un campo debe cambiar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $marca->save();
        return $this->successResponse($marca, Response::HTTP_OK);
    }

    public function show($id)
    {
        $marca = Marca::with('caracteristica')->findOrFail($id);
        return $this->successResponse($marca);
    }

    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();
        return $this->successResponse($marca);
    }
}

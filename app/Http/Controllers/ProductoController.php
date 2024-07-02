<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductoController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $productos = Producto::all();
        return $this->successResponse($productos);
    }

    public function store(Request $request)
    {
        $rules = [
            'codigo' => 'required|max:255',
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'fecha_vencimiento' => 'required|date',
            // Agrega aquí las reglas de validación adicionales según tus requisitos
        ];
        $this->validate($request, $rules);

        $producto = Producto::create($request->all());
        return $this->successResponse($producto, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'codigo' => 'required|max:255',
            'nombre' => 'required|max:255',
            'descripcion' => 'required',
            'fecha_vencimiento' => 'required|date',
            // Agrega aquí las reglas de validación adicionales según tus requisitos
        ];
        $this->validate($request, $rules);

        $producto = Producto::findOrFail($id);
        $producto->fill($request->all());

        if ($producto->isClean()) {
            return $this->errorResponse('Al menos un campo debe cambiar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $producto->save();
        return $this->successResponse($producto, Response::HTTP_OK);
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return $this->successResponse($producto);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return $this->successResponse($producto);
    }
}

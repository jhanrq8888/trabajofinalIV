<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('producto.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('producto.create', compact('categorias'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            DB::beginTransaction();
            //Tabla producto
            $producto = new Producto();
            if ($request->hasFile('img_path')) {
                $name = $producto->handleUploadImage($request->file('img_path'));
            } else {
                $name = null;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'img_path' => $name,
            ]);

            $producto->save();

            //Tabla categoría producto
            $categorias = $request->get('categorias');
            $producto->categorias()->attach($categorias);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('productos.index')->with('success', 'Producto registrado');
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}

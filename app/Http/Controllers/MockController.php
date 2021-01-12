<?php

namespace App\Http\Controllers;

use App\Models as Models;

class MockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Emula a requisição a um model que retornaria as localidades.
     * 
     * @return array
     */
    public static function buscarLocais()
    {
        return response()->json(Models\MockModel::buscarLocais());
    }

}

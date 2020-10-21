<?php

namespace App\Http\Controllers;

class Mock extends Controller
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

    public static function buscarLocais()
    {
        return response()->json(["BSB", "CNF"]);
    }

}

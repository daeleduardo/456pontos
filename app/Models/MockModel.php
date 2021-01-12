<?php

namespace App\Models;


class MockModel extends Model
{

    /**
     * Realiza o mock de uma consulta que buscaria os locais de voo.
     * 
     * @return array
     */    

    public static function buscarLocais()
    {
        return ["BSB", "CNF"];
    }

}

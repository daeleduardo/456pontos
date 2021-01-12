<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models as Models;

class GrupoVooController extends BaseController
{

    private $grupoVooModel;

    public function __construct()
    {
        $this->grupoVooModel =  new Models\GrupoVooModel();
    }

/**
 * @api {get} /gruposVoos Buscar dados agrupados de voo
 * @apiName gruposVoos
 * @apiGroup GruposVoo
 *
 * @apiHeader (gruposVoos) {String} Authorization="" Token de autorização para consumir a API, neste exemplo o token e <code>HEEPwHXmqNMppXb8d8R2UJdKpq2s27AL</code>
 * @apiHeader (gruposVoos) {String} Content-Type=""  <code>application/javascript; charset=utf-8</code>
 * 
 * @apiSampleRequest https://lumen456pontos.herokuapp.com/gruposVoos
 */
    public function gruposVoos(Request $requisicao )
    {
        $voos =  $this->grupoVooModel->buscarDadosVoosServico();

        if (empty($voos['origens']) || empty($voos['destinos'])) {
            return response()->json(["erro" => "Não foi possível encontrar grupos com base nos critérios informados."]);
        }

        $gruposVoo = $this->grupoVooModel->montarGruposVoo($voos['origens'], $voos['destinos']);

        return response()->json($gruposVoo);
    }

}
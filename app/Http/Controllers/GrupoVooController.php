<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrupoVooController extends Controller
{

    private $voos, $vooMaisBarato;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->servico = new Servico123Milhas();
    }

    /*
    Gera chave única de consulta do grupo de voo
     */

    private function gerarIdConsulta()
    {
        return md5(uniqid(rand(), true)) . md5(uniqid(rand(), true));
    }

    /*
    Gera chave com base nas características do voo para agregação
     */
    private function gerarChaveCaracteriscaVoo($voo)
    {   
        /*IMPORTANTE!!!!!!*/
        //Na descrição informava que os itens mais importantes para agrupamento eram:
        //Valor, Tarifa e sentido (ida ou volta) 
        //Porém foi acrescentado também o agrupamento por Cia area,
        //pois nos sites de vendas de passagens consultados, todos agrupam os pacotes por CIA também;
        
        $hash = "";
        $hash .= $voo["fare"];
        $hash .= $voo["cia"];
        $hash .= $voo["origin"];
        $hash .= $voo["destination"];
        $hash .= $voo["price"];
        $hash .= $voo["outbound"];
        $hash .= $voo["inbound"];
        return md5($hash);
    }

    /*
    Monta os grupos de voo, cada grupo é um conjunto de voos de ida e volta,
    Que possui o mesmo valor em todas as combinações e tem o mesmo tipo de taxa.
     */
    private function montarGruposVoo($voosOrigem, $voosDestino)
    {
        if (empty($voosOrigem) || empty($voosDestino)) {
            return [];
        }

        $gruposVoos = [
            "flights" => [],
            "groups" => [],
            "totalGroups" => -1,
            "totalFlights" => -1,
            "cheapestPrice" => -1,
            "cheapestGroup" => -1,
        ];

        foreach ($voosOrigem as $origem) {

            $chave = $this->gerarChaveCaracteriscaVoo($origem);
            $arrOrigem[$chave][] = $origem;
            $gruposVoos["flights"][] = $origem["flightNumber"];
        }

        foreach ($voosDestino as $destino) {

            $chave = $this->gerarChaveCaracteriscaVoo($destino);
            $arrDestino[$chave][] = $destino;
            $gruposVoos["flights"][] = $destino["flightNumber"];
        }

        $grupos = [];

        foreach ($arrOrigem as $origem) {
            foreach ($arrDestino as $destino) {
                if ($origem[0]['fare'] == $destino[0]['fare']) {
                    $grupos[] = [
                        "uniqueId" => $this->gerarIdConsulta(),
                        "totalPrice" => ($origem[0]['price'] + $destino[0]['price']),
                        "outbound" => $origem,
                        "inbound" => $destino,
                    ];
                }
            }
        }

        uasort($grupos, function ($itemA, $itemB) {
            if ($itemA["totalPrice"] == $itemB["totalPrice"]) {
                return 0;
            }

            return ($itemA["totalPrice"] < $itemB["totalPrice"]) ? -1 : 1;
        });

        $gruposVoos["totalFlights"] = count($gruposVoos["flights"]);
        $gruposVoos["totalGroups"] = count($grupos);
        $gruposVoos["groups"] = array_values($grupos);
        $gruposVoos["cheapestPrice"] = $grupos[0]["totalPrice"];
        $gruposVoos["cheapestGroup"] = $grupos[0]["uniqueId"];

        return $gruposVoos;

    }

    /*
    Busca os dados de voo na API de serviço.
     */
    private function buscarDadosVoosServico(Request $request)
    {

        if (!$request->exists("origem") || !$request->exists("destino")) {
            abort(400, "Origem ou destino não informados");
        }

        $dadosOrigem = [
            "origin" => $request->origem,
            "destination" => $request->destino,
            "outbound" => 1,
            "inbound" => 0,
        ];

        $dadosDestino = [
            "origin" => $request->destino,
            "destination" => $request->origem,
            "outbound" => 0,
            "inbound" => 1,
        ];

        return [
            $this->servico->dadosVoo($dadosOrigem),
            $this->servico->dadosVoo($dadosDestino),
        ];
    }

/**
 * @api {get} /buscarGruposVoos?origem=:origem&destino=:destino Buscar dados agrupados de voo
 * @apiName buscarGruposVoos
 * @apiGroup GruposVoo
 *
 * @apiParam {String} origem    Aeroporto de origem
 * @apiParam {String} destino   Aeroporto de destino
 *
 * @apiHeader (buscarGruposVoos) {String} Authorization="" Token de autorização para consumir a API, neste exemplo o token e <code>HEEPwHXmqNMppXb8d8R2UJdKpq2s27AL</code>
 * @apiHeader (buscarGruposVoos) {String} Content-Type=""  <code>application/javascript; charset=utf-8</code>
 * 
 * @apiSampleRequest https://lumen456pontos.herokuapp.com/buscarGruposVoos?
 */
    public function buscarGruposVoos(Request $request)
    {
        list($voosOrigem, $voosDestino) = $this->buscarDadosVoosServico($request);

        if (empty($voosOrigem) || empty($voosDestino)) {
            return response()->json(["erro" => "Não foi possível encontrar grupos com base nos critérios informados."]);
        }

        $gruposVoo = $this->montarGruposVoo($voosOrigem, $voosDestino);

        return response()->json($gruposVoo);

    }

}

<?php

namespace App\Models;


class GrupoVooModel extends Model
{
    private $gruposVoos;

    public function __construct()
    {
        $this->servico = new Servico123Milhas();
        $this->gruposVoos = [
            "flights" => [],
            "groups" => [],
            "totalGroups" => -1,
            "totalFlights" => -1,
            "cheapestPrice" => -1,
            "cheapestGroup" => -1,
        ];        
    }

    /**
     * Gera um código único de consulta
     *
     * @return string
     */
    private function gerarIdConsulta()
    {
        return md5(uniqid(rand(), true)) . md5(uniqid(rand(), true));
    }

    /**
     * Gera chave com base nas características do voo para agregação
     *
     * @param array $voo
     * 
     * @return string
     */     
    private function gerarChaveCaracteriscaVoo($voo)
    {   
        
        return md5(
            $voo["fare"]
        .  $voo["origin"]
        .  $voo["destination"]
        .  $voo["price"]
        .  $voo["outbound"]   
        .  $voo["inbound"]);
    }

    /**
     * Monta os grupos de voo, cada grupo é um conjunto de voos de ida e volta,
     * Que possui o mesmo valor em todas as combinações e tem o mesmo tipo de taxa.
     *
     * @param array $voosOrigem
     * @param array $voosDestino
     * 
     * @return array
     */
    public function montarGruposVoo($voosOrigem, $voosDestino)
    {
        if (empty($voosOrigem) || empty($voosDestino)) {
            return [];
        }
        
        $arrOrigem  = $this->adicionarVoos($voosOrigem);
        $arrDestino = $this->adicionarVoos($voosDestino);

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

        $this->gruposVoos["totalFlights"] = count($this->gruposVoos["flights"]);
        $this->gruposVoos["totalGroups"] = count($grupos);
        $this->gruposVoos["groups"] = array_values($grupos);
        $this->gruposVoos["cheapestPrice"] = $grupos[0]["totalPrice"];
        $this->gruposVoos["cheapestGroup"] = $grupos[0]["uniqueId"];

        return $this->gruposVoos;

    }

    /**
     * Busca os dados de voo na API de serviço.
     * 
     * @return array
     */     
    public function buscarDadosVoosServico()
    {
        $voos = $this->servico->dadosVoo();

        $origens = [];
        $destinos = [];

        foreach ($voos as $voo){
            if($voo["outbound"]&&!$voo["inbound"]){
                $origens[] = $voo;
            }

            if(!$voo["outbound"]&&$voo["inbound"]){
                $destinos[] = $voo;
            }
        }
        
        return ["origens"=>$origens,"destinos"=>$destinos];
    }

    /**
     * Percorre todos os registros do array de voos $voos,
     * e para cada registro o adiciona no grupo de voos,
     * e no fim retorna um array de voos agrupados por características.
     * 
     * @param array $voos
     * 
     * @return array
     */    

    private  function adicionarVoos($voos)
    {
        $arrResultado = [];
        
        foreach ($voos as $voo) {
            $chave = $this->gerarChaveCaracteriscaVoo($voo);
            $arrResultado[$chave][] = $voo;
            $this->gruposVoos["flights"][] = $voo["flightNumber"];
        }
        return $arrResultado;
    }

}
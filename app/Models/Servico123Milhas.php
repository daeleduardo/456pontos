<?php

namespace App\Models;

class Servico123Milhas extends Model
{
    private $url;
    /**
     * Classe que realiza a consulta no serviço da 123Milhas
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = "http://prova.123milhas.net/api/";
    }

    public function dadosVoo()
    {
        return $this->requisicao("flights", "GET");
    }

    /**
     * Realiza a chamada no serviço com base nos parâmetros informados.
     *
     * @return array
     */    
    private function requisicao($servico, $metodo)
    {

        $curl = curl_init();
        $parametros = [
            "servico" => $servico,
            "metodo" => strtoupper($metodo)
        ];
        

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url . $parametros['servico'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $parametros['metodo']
        ]);

        $resposta = curl_exec($curl);
        $erro = curl_error($curl);

        curl_close($curl);

        if ($erro) {
            abort(500, "Erro de requisição de Serviço #:" . $erro);
        }
        return json_decode($resposta, true);
    }

}

<?php

namespace App\Http\Controllers;

class Servico123Milhas extends Controller
{
    private $url;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = "http://prova.123milhas.net/api/";
    }

    public function dadosVoo($dados)
    {
        return $this->requisicao("flights", "GET", $dados);
    }

    private function requisicao($endpoint, $method, $data)
    {

        $curl = curl_init();

        if (strtoupper($method) == "GET") {
            $endpoint .= "?";
            foreach ($data as $key => $value) {
                $endpoint .= "&$key=$value";
            }
        }

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => ($method == "GET") ? "" : strtoupper(json_encode($data)),
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            abort(500, "Erro de requisição de Serviço #:" . $err);
        }
        return json_decode($response, true);
    }

}

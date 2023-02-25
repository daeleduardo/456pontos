<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>456 Pontos</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.2.3/dist/css/uikit.min.css" />
    <!-- UIkit JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/uikit@3.2.3/dist/js/uikit.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/uikit@3.2.3/dist/js/uikit-icons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
</head>

<body>
    <div id="app" class="uk-section uk-section-muted uk-section-small">
        <div class="uk-container">
            <div class="uk-flex uk-flex-center uk-flex-middle uk-flex-wrap">
                <div class="uk-margin-left">
                    <label class="uk-form-label">Origem:</label>
                </div>
                <div class="uk-margin-left">
                    <select class="uk-select uk-form-small uk-border-pill" v-model="origem">
                        <option disabled value="">Selecione...</option>
                        <option v-for="aeroporto in aeroportos" v-bind:value="aeroporto">{{ aeroporto }}</option>
                    </select>
                </div>
                <div class="uk-margin-left">
                    <label class="uk-form-label">Destino:</label>
                </div>
                <div class="uk-margin-left">
                    <select class="uk-select uk-form-small uk-border-pill" v-model="destino">
                        <option disabled value="">Selecione...</option>
                        <option v-for="aeroporto in aeroportos" v-bind:value="aeroporto">{{ aeroporto }}</option>
                    </select>
                </div>
                <div class="uk-margin-left">
                    <button v-if="!emPesquisa" class="uk-button uk-button-primary uk-form-small uk-border-pill"
                        v-on:click="gruposVoos()">Pesquisar</button>
                    <div v-if="emPesquisa" uk-spinner></div>
                </div>
            </div>
        </div>
        <hr class="uk-divider-icon">
        <div class="uk-container">
            <div class="uk-flex uk-flex-column uk-flex-center uk-flex-middle uk-flex-wrap">


                <div v-for="obj in arrGruposVoos.groups"
                    class="uk-card uk-card-small uk-card-default  uk-border-rounded uk-margin">
                    <div class="uk-card-header uk-label uk-width-1-1 uk-label-success uk-text-center ">
                        <h4 class="uk-card-title uk-logo ">R$ {{obj.totalPrice}}</h4>
                        <span class="uk-card-title uk-text-small uk-text-meta ">Voando de {{obj.outbound[0].cia}}</span>
                    </div>&nbsp;
                    <div class="uk-card-body uk-background-muted">
                        <p><span class="uk-margin-right"><strong>IDA &nbsp;&nbsp;&nbsp;&nbsp; </strong></span>
                            <span>{{obj.outbound[0].origin}}<span class="uk-margin-small-left uk-margin-small-right"
                                    uk-icon="arrow-right"></span>{{obj.outbound[0].destination}}</span>
                        </p>
                    </div>
                    <div v-for="rota in obj.outbound" class="uk-card-body">
                        <p>
                            <strong>Saida:</strong>
                            {{rota.departureDate}}&nbsp;{{rota.departureTime}}
                            <span class="uk-margin-small-left uk-margin-small-right" uk-icon="arrow-right"></span>
                            <strong>Chegada:</strong>
                            {{rota.arrivalDate}}&nbsp;{{rota.arrivalTime}}
                        </p>
                    </div>
                    <div class="uk-card-body uk-background-muted">
                        <p><span class="uk-margin-right"><strong>VOLTA </strong></span>
                            <span>{{obj.inbound[0].origin}}<span class="uk-margin-small-left uk-margin-small-right"
                                    uk-icon="arrow-right"></span>{{obj.inbound[0].destination}}</span>
                        </p>
                    </div>
                    <div v-for="rota in obj.inbound" class="uk-card-body">
                        <p>
                            <strong>Saida:</strong>
                            {{rota.departureDate}}&nbsp;{{rota.departureTime}}
                            <span class="uk-margin-small-left uk-margin-small-right" uk-icon="arrow-right"></span>
                            <strong>Chegada:</strong> {{rota.arrivalDate}}&nbsp;{{rota.arrivalTime}}
                        </p>
                    </div>
                    <div class="uk-card-footer">
                        <button
                            class="uk-button uk-button-secondary  uk-button-small uk-width-1-1 uk-border-pill">Comprar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
    /*Incializando instancia vuejs*/
    let app = new Vue({
        el: '#app',
        data: {
            aeroportos: "",
            origem: "",
            destino: "",
            arrNomeServico: {},
            arrGruposVoos: {},
            emPesquisa: false
        },
        mounted: function() {
            this.buscarLocais();
        },
        created: function() {
            /*Simulação de dicionário de endpoints*/
            this.arrNomeServico = {
                "buscarGruposVoos": window.location.href + "gruposVoos",
                "buscarLocais": window.location.href + "locais"
            }
        },
        methods: {
            gruposVoos: function() {
                /*Validação somente para demonstração na interface*/
                if (this.origem != 'CNF' || this.destino != 'BSB') {
                    UIkit.notification({
                        message: '<p>' +
                            "Não foi possível encontrar viagens no roteiro informado." + '</p>',
                        pos: 'top-center',
                        timeout: 2000
                    });
                    app.emPesquisa = false;
                    return;
                }
                let parametros = "?origem=" + app.origem + "&destino=" + app.destino;
                let url = this.arrNomeServico["buscarGruposVoos"] + parametros;
                this.chamarServico(url, function(retorno) {
                    app.arrGruposVoos = retorno;
                    console.log(app.arrGruposVoos);
                });
            },
            buscarLocais: function() {
                this.chamarServico(this.arrNomeServico["buscarLocais"], function(retorno) {
                    app.aeroportos = retorno;
                });
            },
            chamarServico: function(urlServico, callback = null) {
                this.emPesquisa = true;

                let xhr = new XMLHttpRequest();
                xhr.open("GET", urlServico);
                xhr.setRequestHeader("Authorization", "HEEPwHXmqNMppXb8d8R2UJdKpq2s27AL")
                xhr.setRequestHeader("Content-Type", "application/javascript; charset=utf-8")
                xhr.onload = function() {
                    let retorno = JSON.parse(xhr.responseText);
                    if (retorno.hasOwnProperty('erro')) {
                        UIkit.notification({
                            message: '<p>' + retorno.erro + '</p>',
                            pos: 'top-center',
                            timeout: 2000
                        });
                    }
                    callback(retorno);

                    app.emPesquisa = false;
                };
                xhr.onerror = function(e) {
                    UIkit.notification({
                        message: '<p>' + 'Erro ao fazer requisição' + '</p>',
                        pos: 'top-center',
                        timeout: 2000
                    });
                    app.emPesquisa = false;
                };
                xhr.send();

            }
        }
    });
    </script>
</body>

</html>
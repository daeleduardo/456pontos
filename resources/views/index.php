<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>456 Pontos</title>
	<!-- UIkit CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.2.3/dist/css/uikit.min.css" />
	<!-- UIkit JS -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/uikit@3.2.3/dist/js/uikit.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/uikit@3.2.3/dist/js/uikit-icons.min.js"></script>
	<script src="https://unpkg.com/vue"></script>

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
          			<select class="uk-select uk-form-small uk-border-pill"v-model="destino">
					  <option disabled value="">Selecione...</option>
		  				<option v-for="aeroporto in aeroportos" v-bind:value="aeroporto">{{ aeroporto }}</option>
          			</select>
      			</div>
        		<div class="uk-margin-left">
					<button v-if="!emPesquisa" class="uk-button uk-button-primary uk-form-small uk-border-pill" v-on:click="buscarGruposVoos()">Pesquisar</button>
					<div v-if="emPesquisa" uk-spinner></div>
      			</div>
    		</div>
  		</div>
		  <hr class="uk-divider-icon">
		  <div class="uk-container">
    			<div class="uk-flex uk-flex-column uk-flex-center uk-flex-middle uk-flex-wrap">


<div v-for="obj in arrGruposVoos.groups" class="uk-card uk-card-small uk-card-default  uk-border-rounded uk-margin">
    <div class="uk-card-header uk-label uk-width-1-1 uk-label-success uk-text-center ">
      <h4 class="uk-card-title uk-logo ">R$ {{obj.totalPrice}}</h4>
      <span class="uk-card-title uk-text-small uk-text-meta ">Voando de {{obj.outbound[0].cia}}</span>
    </div>&nbsp;
    <div class="uk-card-body uk-background-muted">
        <p><span class="uk-margin-right"><strong>IDA &nbsp;&nbsp;&nbsp;&nbsp; </strong></span> <span>{{obj.outbound[0].origin}}<span class="uk-margin-small-left uk-margin-small-right" uk-icon="arrow-right"></span>{{obj.outbound[0].destination}}</span></p>
    </div>    
    <div class="uk-card-body">
        <p>
			<strong>Saida:</strong> {{obj.outbound[0].departureDate}}&nbsp;{{obj.outbound[0].departureTime}}
			<span class="uk-margin-small-left uk-margin-small-right" uk-icon="arrow-right"></span>
			<strong>Chegada:</strong> {{obj.outbound[0].arrivalDate}}&nbsp;{{obj.outbound[0].arrivalTime}}
		</p>
    </div>
    <div class="uk-card-body uk-background-muted">
	<p><span class="uk-margin-right"><strong>VOLTA </strong></span> <span>{{obj.inbound[0].origin}}<span class="uk-margin-small-left uk-margin-small-right" uk-icon="arrow-right"></span>{{obj.inbound[0].destination}}</span></p>
    </div>    
    <div class="uk-card-body">
		<p>
			<strong>Saida:</strong> {{obj.inbound[0].departureDate}}&nbsp;{{obj.inbound[0].departureTime}}
			<span class="uk-margin-small-left uk-margin-small-right" uk-icon="arrow-right"></span>
			<strong>Chegada:</strong> {{obj.inbound[0].arrivalDate}}&nbsp;{{obj.inbound[0].arrivalTime}}
		</p>
    </div>    
    <div class="uk-card-footer">
<button class="uk-button uk-button-secondary  uk-button-small uk-width-1-1 uk-border-pill">Comprar</button>
    </div>
</div>

    </div>
  </div>
	</div>
	<script>

/*Incializando instancia vuejs*/
var app = new Vue({
  el: '#app',
  data: {
	aeroportos : "",
	origem: "",
	destino: "",
	arrNomeServico:{},
	arrGruposVoos:{},
	emPesquisa : false
  },
  mounted: function (){
	this.buscarLocais();
  },
  created: function(){
	/*Simulação de dicionário de endpoints*/
	this.arrNomeServico = {
		"buscarGruposVoos": window.location.href+"buscarGruposVoos",
		"buscarLocais": window.location.href+"buscarLocais"
		}
	},
  methods: {
	buscarGruposVoos: function(){
		let parametros = "?origem="+app.origem+"&destino="+app.destino;
		let url = this.arrNomeServico["buscarGruposVoos"]+parametros;
		this.chamarServico(url,function (retorno) {
		app.arrGruposVoos = retorno;
		console.log(app.arrGruposVoos);
	});
	},
	buscarLocais: function(){
		this.chamarServico(this.arrNomeServico["buscarLocais"],function (retorno) {
		app.aeroportos = retorno;
	});
	},
	chamarServico: function(urlServico,callback =null) {
		    this.emPesquisa = true;
            let xhr = new XMLHttpRequest();
			console.log(urlServico);
			xhr.open("GET", urlServico);
			xhr.setRequestHeader("Authorization", "HEEPwHXmqNMppXb8d8R2UJdKpq2s27AL")
			xhr.setRequestHeader("Content-Type", "application/javascript; charset=utf-8")
            xhr.onload = function() {
				let retorno =  JSON.parse(xhr.responseText);
				if (retorno.hasOwnProperty('erro')) {
					UIkit.modal.dialog('<p>'+retorno.erro+'</p>');	
				}
				callback(retorno);	
		
				app.emPesquisa = false;
			};
            xhr.onerror = function() {
				app.emPesquisa = false;
			};			
            xhr.send();
          }
		}
	});

</script>
</body>
</html>

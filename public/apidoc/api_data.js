define({ "api": [
  {
    "type": "get",
    "url": "/buscarGruposVoos?origem=:origem&destino=:destino",
    "title": "Buscar dados agrupados de voo",
    "name": "buscarGruposVoos",
    "group": "GruposVoo",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "origem",
            "description": "<p>Aeroporto de origem</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "destino",
            "description": "<p>Aeroporto de destino</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "buscarGruposVoos": [
          {
            "group": "buscarGruposVoos",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Token de autorização para consumir a API, neste exemplo o token e <code>HEEPwHXmqNMppXb8d8R2UJdKpq2s27AL</code></p>"
          },
          {
            "group": "buscarGruposVoos",
            "type": "String",
            "optional": false,
            "field": "Content-Type",
            "description": "<p><code>application/javascript; charset=utf-8</code></p>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "https://lumen456pontos.herokuapp.com/buscarGruposVoos?"
      }
    ],
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/GrupoVooController.php",
    "groupTitle": "GruposVoo"
  }
] });
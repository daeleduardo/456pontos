define({ "api": [
  {
    "type": "get",
    "url": "/gruposVoos",
    "title": "Buscar dados agrupados de voo",
    "name": "gruposVoos",
    "group": "GruposVoo",
    "header": {
      "fields": {
        "gruposVoos": [
          {
            "group": "gruposVoos",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Token de autorização para consumir a API, neste exemplo o token e <code>HEEPwHXmqNMppXb8d8R2UJdKpq2s27AL</code></p>"
          },
          {
            "group": "gruposVoos",
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
        "url": "https://lumen456pontos.herokuapp.com/gruposVoos?"
      }
    ],
    "version": "0.0.0",
    "filename": "/home/daniel/workspace/456pontos/app/Http/Controllers/GrupoVooController.php",
    "groupTitle": "GruposVoo"
  }
] });

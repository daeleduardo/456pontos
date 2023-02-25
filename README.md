# 456 Pontos

Aplicação feita usando o framework [Lumen](https://github.com/laravel/lumen) para consulta de um sistema fictício de passagens áereas.

Os critérios para agregação dos voos foram: 

* Tipo de taxa.
* Companhia aerea.
* Origem do voo.
* Destino do voo.
* Preço da passagem.
* Se é um voo de ida ou volta.

## API

A consulta pode ser feita em forma de API, para mais informações, acessando endereço: ```localhost:8080/apidoc/index.html```

## Uso para desenvolvimento.

Para executar a aplicação localmente, após realizar o download do projeto, acessar a página raiz do projeto e executar um dos dois comandos abaixo para iniciar a aplicação da forma que melhor lhe convém:

***Docker:*** 

 ```docker-compose up -d 456pontos```

 ***PHP Server:***

 ```php -S 0.0.0.0:8080 -t public```


## License

O projeto é de código aberto sobre a licença [MIT license](https://opensource.org/licenses/MIT).

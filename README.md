# test-brasil-tecpar

Pequena aplicação para geração de hash via interface de linha de comando.

- A solução apresentada, foi pensada para ser simples e de fácil entendimento. Usado padrão `Repository` e `Service` para distribuir melhor suas responsabilidades, e ter um menor acoplamento das classes. Também foi utilizados recursos do próprio microframework Lumen como o tratamento no limite de requisições. O ponto chave do desenvolvimento, foi entender a lógica e implementação do cálculo e validação do hash. Para isso também foi criado alguns testes unitários, para garantir o comportamento correto das funcionalidades.

## Tecnologias
- PHP / Lumen 🐘
- SQLite 💾
- Docker 🐳

## Requerimentos
- Docker 🐳
- PHP e Composer para dependências

## Instalação

Clone este repositório

```bash
$ git clone https://github.com/wribeiiro/test-brasil-tecpar
```
Navegue até o diretório 

```bash
$ cd /test-brasil-tecpar/www
```
Execute o seguinte comando para instalar as dependências

```bash
$ composer install
```

Em seguinda volta para a raiz do projeto, e execute o comando para subir o container
```bash
$ cd ..
$ docker-compose -f "./docker-compose.yml" up -d --build
```

Em seguida verifique se o servidor está de pé http://localhost:8000/ 
- Ao acessar o endereço, deve mostrar o seguinte texto `Lumen (8.3.1) (Laravel Components ^8.0)`

# Endpoints

- Existe 2 endpoints disponíveis: 
- Um para consulta de todos os hashes disponíveis `/api/v1/hashes`
- Outro para geração do hash. `/api/v1/hashes/store`, porém este é utilizado somente no command line.

### Buscando todos os hashes

`GET http://localhost:8000/api/v1/hashes`

Retorna uma lista paginada no formato JSON com todos os hashes gerados.
- Permite um parâmetro via query string para filtro `?attempts=1000`, onde o valor é de tentativas para encontrar a key.

### Response

```json
{
   "data":{
        "current_page":1,
        "data":[
            {
                "batch":"2021-10-28 21:50:18",
                "id":1,
                "input":"minhastringfeliz",
                "key":"=E2EQ=1N"
            },
            {
                "batch":"2021-10-28 21:50:21",
                "id":2,
                "input":"000055b84f5d8cc9fdc43dc1b154a5d5",
                "key":"=3yZjQNW"
            },
            {
                "batch":"2021-10-28 21:50:22",
                "id":3,
                "input":"0000c547d8ff017fb48438ef4646bf32",
                "key":"YY1jk3N="
            },
        ],
        "first_page_url":"http://localhost:8000/api/v1/hashes?page=1",
        "from":1,
        "last_page":1,
        "last_page_url":"http://localhost:8000/api/v1/hashes?page=1",
        "links":[
            {
                "url":null,
                "label":"pagination.previous",
                "active":false
            },
            {
                "url":"http://localhost:8000/api/v1/hashes?page=1",
                "label":"1",
                "active":true
            },
            {
                "url":null,
                "label":"pagination.next",
                "active":false
            }
        ],
        "next_page_url":null,
        "path":"http://localhost:8000/api/v1/hashes",
        "per_page":15,
        "prev_page_url":null,
        "to":3,
        "total":3
    },
   "status":200
}
```

### Comando para geração do hash (CLI)

Navegue até o diretório www e execute o seguinte comando já com o docker de pé.

```bash
$ winpty docker exec -it php-test-brasil-tecpar bash
```

Em seguida execute o seguinte comando para a requisição onde:
- `minhastringfeliz` é a string a ser "hasheada"
- `requests` o número de requisições que serão feitas em sequência
```bash
$ php artisan hash:make `minhastringfeliz` --requests=`10`
```

O Resultado de cada requisição deve ser informada no console conforme abaixo:

```bash
Request 1 of 10: OK
Request 2 of 10: OK
Request 3 of 10: OK
Request 4 of 10: OK
Request 5 of 10: OK
Request 6 of 10: OK
Request 7 of 10: OK
Request 8 of 10: OK
Request 9 of 10: OK
Request 10 of 10: OK
```
O Resultado final com todos os resultados encontrados no console conforme abaixo:

```bash
+---------------------+------------+----------------------------------+----------+----------------------------------+----------+
| Batch               | Num. Block | Input string                     | Key      | Hash                             | Attempts |
+---------------------+------------+----------------------------------+----------+----------------------------------+----------+
| 2021-10-28 21:50:18 | 1          | minhastringfeliz                 | =E2EQ=1N | 000055b84f5d8cc9fdc43dc1b154a5d5 | 38411    |
| 2021-10-28 21:50:21 | 2          | 000055b84f5d8cc9fdc43dc1b154a5d5 | =3yZjQNW | 0000c547d8ff017fb48438ef4646bf32 | 35517    |
| 2021-10-28 21:50:22 | 3          | 0000c547d8ff017fb48438ef4646bf32 | YY1jk3N= | 0000a3fffdb4a004f8d8fd03cbaf79d2 | 55711    |
| 2021-10-28 21:50:23 | 4          | 0000a3fffdb4a004f8d8fd03cbaf79d2 | Y3AkY1D5 | 0000e5dbab3e1a70e231f9710039a3b8 | 11314    |
| 2021-10-28 21:50:24 | 5          | 0000e5dbab3e1a70e231f9710039a3b8 | jEZl3cNB | 00007dd32af4bd6de6d1cd34e4054293 | 83929    |
| 2021-10-28 21:50:26 | 6          | 00007dd32af4bd6de6d1cd34e4054293 | ZNTNz3j= | 0000734cf87715dd58ca86f874c32ebc | 74115    |
| 2021-10-28 21:50:27 | 7          | 0000734cf87715dd58ca86f874c32ebc | c4MTAQN1 | 00000281c031000c75896745c5a02685 | 46123    |
| 2021-10-28 21:50:28 | 8          | 00000281c031000c75896745c5a02685 | MNjYxNE= | 0000c358d02ba654224a3dc5db7f0e10 | 125166   |
| 2021-10-28 21:50:32 | 9          | 0000c358d02ba654224a3dc5db7f0e10 | Y1N=3QhN | 00006994a0e41737a788b8397d00e42c | 101      |
| 2021-10-28 21:50:34 | 10         | 00006994a0e41737a788b8397d00e42c | hEjAZ=QY | 000033644c3c39b6a475ea54c2e1e780 | 28379    |
+---------------------+------------+----------------------------------+----------+----------------------------------+----------+

```

### Execução dos Unit Tests

Navegue até o diretório www e execute o seguinte comando já com o docker de pé.

```bash
$ ./vendor/bin/phpunit
```

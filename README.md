[![Actions Status](https://github.com/kessejones/backend-pagamentos/workflows/CI/badge.svg)](https://github.com/kessejones/backend-pagamentos/actions)
# Back-end Pagamentos
[Documentação da API](./API.md)
## Instalação

Acesse a pasta `docker` e suba os containers com o comando `docker-compose up -d`

Agora volte para a raiz do projeto e siga os passos abaixo:

* Criando arquivo com as variaveis de ambiente: `cp .env.example .env`

* Acessando container do PHP: `docker exec -it pagamentos_php bash`

* Instalando dependências do projeto: `composer install`

* Gerando a chave do ambiente: `php artisan key:generate`

* Criando tabelas no banco de dados e preenchendo com os dados padrão:
`php artisan migrate --seed`


## Testes
Para rodar os testes automatizados acesse o container do PHP `docker exec -it pagamentos_php bash`
e então execute `php artisan test`

# Crypto Wallet - API de investimentos em criptomoedas

**Tecnologias utilizadas:** Lumen 7.2, MariaDB 10.5.9

### Setup

- Execute o comando `docker-compose up -d` na pasta anterior à do projeto clonado, onde deverá se encontrar o arquivo [docker-compose.yml](#docker-compose)
- Na pasta do projeto, copie e cole o arquivo `.env.example`, renomeando-o para `.env`, e verificando as configurações como banco de dados e servidor SMTP
- Dentro do container, execute o comando `composer install`
- Ainda no container, execute o comando `php artisan migrate`, para realizar a migração das tabelas ao banco de dados, e em seguida, `php artisan db:seed`

Após isso, a aplicação estará sendo servida na porta especificada no docker-compose (padrão 8080), do localhost.

<a name="docker-compose"></a>
**docker-compose.yml**
```yml
version: '3.7'

services:
  mariadb:
    image: mariadb
    container_name: crypto_wallet-db
    volumes:
      - ./db:/var/lib/mysql
    ports:
      - "3306:3306"
    environment:
      - MYSQL_ROOT_PASSWORD=crypto_wallet
      - MYSQL_DATABASE=crypto_wallet

  app:
    build: ./crypto-wallet
    container_name: crypto_wallet-api
    volumes:
      - ./crypto-wallet:/var/www/app
    ports:
      - "8080:8080"
    links:
      - mariadb
```

### Documentação

A documentação da API poderá ser encontrada [aqui](https://documenter.getpostman.com/view/4348568/Tz5iA1Ln)
E o link para a collection no postman [aqui](https://www.getpostman.com/collections/017f993e22e02de18292)

**Observação:** Por questões de precisão, todos valores monetários são armazenados em sua unidade minima/inteira. Ex.: _**R$ 1,50 = 150 centavos**_, _**0,05 BTC = 5.000.000 satoshis**_. Consequentemente, os valores obtidos na API para consulta de preço do Bitcoin, são em centavos por satoshi.
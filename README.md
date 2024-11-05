# Objetivo: Criar minha api sem framework, com base em conhecimentos adquiridos e com o auxílio de videos do Youtube.

## 1. Início
Criar um arquivo index.php com o comando:

```<?php  phpinfo();```

Testar a instalação do PHP com o comando abaixo:
```
php -S localhost:8080
```

Baixar composer:
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
Iniciar o composer:
```
composer init
```

Alterar o index.php 


```
<?php  
    require 'vendor/autoload.php';
```
## 2. Criar arquivo htaccess
Criar o arquivo para que seja possível a utilização de url amigável

## 3. Criar estrutura de pastas
>src
    >>Controllers
    >>Core
    >>Http
    >>Models
    >>Services
    >>Utils
    >>routes
      >>>v1

## 4. Ordem de criação dos arquivo segundo o canal [NevesCode](https://www.youtube.com/watch?v=5fg5NG2ucsA)

>Controllers
    >>HomeController.php
    >>NotFoundController.php
    >>UserController.php

>Core
    >>Core.php

>Http
    >>JWT.php
    >>Request.php
    >>Response.php
    >>Route.php

>Models
    >>Database.php
    >>User.php

>routes
    >>main.php

>Services
    >>UserServices.php

>Ultis
    >>Validator

## 4. iniciando o meu desenvolvimento

### Vou criar uma função para auxiliar no debug do código
1. Na pasta Utils vou criar a arquivo Utils com a função *VD* de vardump
2. Criado o arquivo Route.php.
3. Criado o arquivo routesv1.php.
4. Criado o arquivo HomeController.php
5. Criado o arquivo Core.php 
6. Criado o arquivo Request.php
7. Criado o arquivo Responnse.php
8. Criado o arquivo NotFoundController.php
9. Criado o arquivo .http

## 5. Implementar as middleware

>Core
    >>Middlewares
        >>>Queue - gerencia as filas
        >>>Maintenance.php 
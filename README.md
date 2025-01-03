[Tutorial: Utilizando JWT no Lumen — parte 1]('https://medium.com/@marcusbrasizza/utilizando-jwt-no-lumen-parte-1-50533175dad3)
![https://lumen.laravel.com/]


- [ ] incomplete task
- [ ] completed task


>**Baixar o Composer**
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
> **Criando o projeto:** 
Para criar o projeto ainda foi necessário instalar as extensões do php
sudo apt-get install php8.4-mbstring php8.4-xml



```php composer.phar create-project laravel/lumen  --prefer-dist lumen-jwt```, sendo lumen-jwt o nome do projeto

> **executar o projeto:** 
```php -S 0.0.0.0:8000 -t public```

> **Criando a estrutura do banco de dados:** 
```php artisan make:migration create_users_table --create=user```

>**CreateUsersTable**
```
Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->notNullable();
            $table->string('password');
            $table->timestamps();
        });
```

> **Alterando o app.php e incluindo o config/auth.php:** 
```

```

> **Configurar o banco de dados**
> **bootstrap/app.php:**, descomentar as linhas abaixo
```
$app->withFacades();
$app->withEloquent();
descomentar:
```
$app->routeMiddleware(['auth' => App\Http\Middleware\Authenticate::class, ]);
```

$app->register(App\Providers\AuthServiceProvider::class);
``` 
e adicionar:
```
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
```

Por fim iremos criar um arquivo auth.php dentro de uma pasta config, na raiz do projeto assim como existe no Laravel:
```
<?php

use App\Models\User;

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class
        ]
    ]
];
```

> **Enfim iniciando com o JWT**
```
composer require tymon/jwt-auth
```

> **Ao final da instalação iremos executar o comando**
```
php artisan jwt:secret
```

> **Adicionar no final do arquivo .env**
```
JWT_TTL=60
```

> **Alterar o model de usuários**
```
<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier() {

        return $this->getKey();
    }

    public function getJWTCustomClaims() {

        return [];

     }

     public function setPasswordAttribute($val){
         $pass = Hash::make(($val));
         $this->attributes['password'] = $pass;
     }
}
```

> **Usando uma Trait para facilitar nossos retornos**, Ao longo dos anos de trabalho com o php eu acabei encontrando uma Trait que eu uso em todos os meus projetos para facilitar o meu desenvolvimento e retorno para APIs RestFull. com ela você pode retornar um success ou erro, incluindo o HTTP CODE que você quer retornar para a aplicação de um jeito fácil. Para isso crie também na pasta app uma pasta chamada Traits e dentro dela coloque o seguinte código em um arquivo chamado apiResponser.php
```
<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait ApiResponser{

    /**
     *
     * Build Success Response
     * @param  string|array $data
     * @param int $code
     * @return Iluminate\Http\Response
     */

    public function successResponse($data,$code = Response::HTTP_OK){

        return response($data,$code)->header('Content-Type','application/json');
    }



     /**
     *
     * Build Erros Response
     * @param  string|array $message
     * @param int $code
     * @return Iluminate\Http\JsonResponse
     */

    public function errorResponse($message,$code,$critical = true){
        if($critical){
        Log::stack(  ['stderr'])->critical($message);
        }
        return response()->json(['error' => $message ,'code'=>$code],$code);
    }


  /**
     *
     * Build Erros Response
     * @param  string|array $message
     * @param int $code
     * @return Iluminate\Http\Response
     */

    public function errorMessage($message, $code)
    {
        return response($message, $code)->header('Content-Type', 'application/json');
    }
}
```

>**Modificando o controller raiz**, Como iremos usar o mesmo método para várias classes nós iremos alterar o Controller q é o controller que extende os demais controllers do sistema. Então basicamente vc vai criar neste artigo somente um método, que é o método que vai retornar o token em formato json e http code 200.
```
<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ApiResponser;
    //
    protected function respondWithToken($token)
    {
        return $this->successResponse([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()
        ], 200);
    }
}
```

>**Finalmente!! Nossa classe de autenticação**, Tivemos que fazer vários passos até chegar no primordial! mas necessário para que não tenha nenhuma dúvida do passo a passo no desenvolvimento da sua API JWT, que eu tenho certeza que na próxima vez que fizer será mais fácil e fluído. Vamos criar uma classe chamada nesse projeto de AuthController.php. O que eu sempre faço é abrir o arquivo ExampleController.php e salvar com o nome que eu quero, para não ter q digitar muita linha de código. Inicialmente nosso banco não tem nenhum registro, então primeiro precisamos criar uma rota para registar ao menos um usuário para fazer a movimentação das APIS. Vamos criar nosso método register, JUNTO com um método de validação somente para que tenha validação de campos obrigatórios e etc para teste.
```
<?php
  /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isRegisterValid(Request $request)
    {
        return  $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5'
            ]
        );
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|App\Traits\Iluminate\Http\JsonResponse|void
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        if ($this->isRegisterValid($request)) {
            try {
                $user = new User();
                $user->password = $request->password;
                $user->email = $request->email;
                $user->name = $request->name;
                $user->save();
                return $this->successResponse($user);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }
```

>**Criando a Rota**, Portanto lá no arquivo web.php iremos incluir nosso código. Como estamos utilizando o Lumen como API eu sempre gosto de colocar o prefixo da chamada como API , ai nosso código sempre será http://url/api/xxxxx, assim fica mais fácil o entendimento do que está na API.
```
<?php

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->get('/', function () {
        return microtime();
    });
    $router->post('/register', 'AuthController@register');
});
```

>**vamos partir para o próximo método que é o de login e autenticação do seu usuário para utilizar o token JWT**
```
<?php
 /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isLoginValid(Request $request)
    {

        return $this->validate($request, [
            'email' => 'required|string',
            'password' =>  'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if ($this->isLoginValid($request)) {
            $credentials = $request->only(['email', 'password']);

            $token = auth()->setTTL(env('JWT_TTL','60'))->attempt($credentials);
            if($token){
            return $this->respondWithToken($token);
            }else{
                return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
            }
        }
    }
```

>**Então vamos criar a rota que vai receber este método de login também lá no web.php**
```
<?php

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () {
        return microtime();
    });
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');   
});
```

>**Feito isso, podemos agora criar um método que só poderá ser acessado depois que o usuário se autenticar e receber o token, vamos chamar o método de me.Nesse código já vou postar o controller inteiro, pois é o último método deste arquivo**
```
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/** @package App\Http\Controllers */
class AuthController extends Controller
{

    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}


    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isRegisterValid(Request $request)
    {
        return  $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5'
            ]
        );
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isLoginValid(Request $request)
    {

        return $this->validate($request, [
            'email' => 'required|string',
            'password' =>  'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if ($this->isLoginValid($request)) {
            $credentials = $request->only(['email', 'password']);

            $token = auth()->setTTL(env('JWT_TTL','60'))->attempt($credentials);
            if($token){
            return $this->respondWithToken($token);
            }else{
                return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|App\Traits\Iluminate\Http\JsonResponse|void
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        if ($this->isRegisterValid($request)) {
            try {
                $user = new User();
                $user->password = $request->password;
                $user->email = $request->email;
                $user->name = $request->name;
                $user->save();
                return $this->successResponse($user);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function me(){
            $user = auth()->user();

            return $this->successResponse($user);
    }
}
```

O método me é somente a recuperação simples do usuário e sua exibição na tela, mas com isso é o essencial para completar o ciclo, inclusive com método que só pode ser acessado depois do token Agora vamos incluir essa rota dentro do web.php incluindo o middleware de autenticação exclusivamente para o método em questão:
```
<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return microtime();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () {
        return microtime();
    });

    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    //Metodo que só pode ser acessado com o usuário autenticado
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/me', 'AuthController@me');

    });
});
```

>**Tratando Exceptions de Token expirado ou token inválido**, Nem tudo é perfeito. o token expira, porém este é o intuito do token, que ele expire e que o acesso ao seu sistema seja por um tempo limitado. Para fazermos esse tipo de alteração você precisa ir lá no seu middleware/Authenticate.php
```
<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
class Authenticate
{
     use ApiResponser;
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if ($this->auth->guard($guard)->guest()) {
            try{
               $token =  auth()->payload();
            }catch(\Exception $e){
                if($e instanceof TokenInvalidException){
                    return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
                }

                if($e instanceof TokenExpiredException){
                    return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
                }

            }
        }

        return $next($request);
    }
}
```
> **Inicio da segunda parte**
>**Cadastrar no site: [Mailtrap.io](https://mailtrap.io/home)**, feito o cadastro ele vai te indicar pra colocar algumas variáveis no seu .env
```
#CONF_EMAIL
MAIL_FROM_NAME="Site API"
MAIL_FROM_EMAIL="noreply@email.com"


#MAILTRAP
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxxxxxx
MAIL_PASSWORD=xxxxxxx
MAIL_ENCRYPTION=tls
```

>**Precisamos agora instalar o pacote do mail no Lumen**
```
composer require illuminate/mail
```

>**Depois de terminado iremos precisar ativar o mail dentro do nosso Lumen entrando lá no boostrap/app.php e colocando os seguintes registros**
```
$app->configure(‘mail’);
$app->register(\Illuminate\Mail\MailServiceProvider::class);
$app->alias(‘mailer’, Illuminate\Mail\Mailer::class);
$app->alias(‘mailer’, Illuminate\Contracts\Mail\Mailer::class);
$app->alias(‘mailer’, Illuminate\Contracts\Mail\MailQueue::class);
```

>**Você vai criar esse arquivo na mesma pasta config que esta o auth.php com o nome de mail.php*
```
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers to be used while
    | sending an e-mail. You will specify which one you are using for your
    | mailers below. You are free to add additional mailers as required.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array"
    |
    */
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs',
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
```

>**Modificando nosso banco para suportar o client id e secret**,Agora com o email funcionando vamos alterar nossa tabela para incluir os 2 outros campos (client_id e client_secret) no nosso sistema. Para isso vamos executar um comando do artisan para criar um migration de uma tabela já criada somente para adicionarmos os campos faltantes.
```
php artisan make:migration --table=users insert_client_id_client_secret
```
É só adicionar esses campos no up(){…}.
Eu coloco sempre o after porque eu gosto que o created_at e updated_at sempre fiquem como últimos campos, mas é somente estética.
```
Schema::table('users', function (Blueprint $table) {
$table->string('client_secret')->after('password');
$table->string('client_id')->after('password');
});
```
>**Alterando o model, Mutator e Observer**, Vamos começar com o registro de um usuário novo que automaticamente gerará o client id e secret, além de enviar um e-mail de boas vindas ao usuário da nossa API. Primeira coisa, Mutators eu dei uma explicada por cima no artigo anterior, mas basicamente consiste no seu modelo você sobrepor a chamada do set/get por meio de métodos. No nosso caso iremos criar automaticamente o client_id e client_secret, porém ao gerar iremos concatenar client_id_xxxx e client_secret_xxxx e para isso iremos usar o Mutator assim setClientIdAttribute($val) onde sempre é fixo o set e o Attribute e o que muda é só o nome da propriedade sempre em CamelCase.
```
<?php
   public function setClientIdAttribute($val){
        $this->attributes['client_id'] = 'client_id_'.$val;
    }

    public function setClientSecretAttribute($val){
        $this->attributes['client_secret'] = 'client_secret_'.$val;
    }
?>
```
Agora vamos criar a Trait Observer que ficará 'escutando' nosso modelo e irá executar alguma ação sempre após outra, ou seja, quando o cliente for inserido no banco nós iremos executar alguma ação. Para isso dentro da nossa pasta Traits que criamos anteriormente vamos criar uma pasta chamava Observers para separar o código e dentro dela vamos criar um arquivo chamado UserObserver.php que está abaixo. Mas qual motivo vamos usar uma trait? A Trait do php nada mais é do que um bloco de código que inicialmente não vem vinculo com nenhuma classe e pode ser praticamente acoplada com qualquer uma, portanto fica mais fácil trabalhar com traits nesse caso.
```
<?php
namespace App\Traits\Observers;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
trait UserObserver
{
    protected static function boot()
    {

        parent::boot();
        //Executa uma ação assim que o registro for adicionado no banco de dados!
        static::created(function (User $user) {
            //Recemos aqui a instância do objeto $user já modificado com os dados finais após a inclusão.
            $text = "Welcome to our website API\n";
            $text .= "This is your credentials\n";
            $text .= "Your Client ID : '{$user->client_id}'\n";
            $text .= "Your Client SECRET : '{$user->client_secret}'\n";
            $text .= "Grant Type : 'credential'\n";
            $text .= "You are now able to use our services!'\n";
            //Enviamos um email simples em texto para a pessoa, porém como é no mailtrap.io não vai para ela e sim pro sistema do mailtrap
            Mail::raw($text, function ($message) use ($user) {
                $message->from(env('MAIL_FROM_EMAIL','noreply@noreply.com'), env('MAIL_FROM_NAME','no-reply'));
                $message->subject("Welcome {$user->name}");
                $message->to($user->email);
            });
        });
    }
}
```

Seu model User.php deverá ter ficado igual ao arquivo abaixo, sem esquecer de criar no fillable o client_id e o client_secret e também fazendo a inclusão da trait no use da classe
```
<?php

namespace App\Models;

use App\Traits\Observers\UserObserver;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, UserObserver;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','client_id', 'client_secret'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier() {

        return $this->getKey();
    }

    public function getJWTCustomClaims() {

        return [];

     }

     public function setPasswordAttribute($val){
         $pass = Hash::make(($val));
         $this->attributes['password'] = $pass;
     }


     public function setClientIdAttribute($val){
        $this->attributes['client_id'] = 'client_id_'.$val;
    }

    public function setClientSecretAttribute($val){
        $this->attributes['client_secret'] = 'client_secret_'.$val;
    }
}
```

>**Alterando o nosso AuthController**, Agora precisamos alterar nosso AuthController para alterar o método de registro do usuário, inserindo o client_id e secret por meio do mutator e também alteração do método de login, para contemplar tanto clientes com e sem o client_id. A primeira coisa que vamos fazer é criar um método para gerar uuids randômica e única para cada um dos 2 campos , assim nosso sistema fica a prova de duplicidade, porém você pode incluir um método antes de incluir para ter certeza que realmente não está duplicado, mas com o random_bytes isso não acontece (é o método que vamos utilizar). Vamos criar um método chamado generateApiKey() e nela vamos criar o uuid único que será retornado como resultado do próprio método
```
<?php
 public function generateApiKey()
    {
        $data = random_bytes(16);
        if (false === $data) {
            return false;
        }
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
?>
```
Esse método basicamente cria um uuid único para quem requisitou. Então iremos modificar nosso método de register para contemplar essa chamada e inclusão do nosso client_id e client_secret bem simples.

```
<?php
  public function register(Request $request)
    {
        if ($this->isRegisterValid($request)) {
            try {
                $user = new User();
                $user->password = $request->password;
                $user->email = $request->email;
                $user->name = $request->name;
                $user->client_id = $this->generateApiKey();
                $user->client_secret = $this->generateApiKey();
                $user->save();
                return $this->successResponse($user);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }
```

>**Alterando o login**, Agora com tudo funcionando, registro, e-mail e etc, Vamos para a parte final desse segundo artigo. Iremos alterar o método de login, mas sem quebrar o outro. Mas como iremos fazer isso? Simples, vamos incluir mais um campo no post chamado grant_type e nele iremos dizer se queremos o login via credential ou não. caso o grant_type for inserido com "credential" ele vai validar e logar com o credencial de client id/secret, caso não estiver nada ou qualquer coisa diferente de credential ele vai tentar logar via email/senha. Para isso vamos alterar o nosso método isLoginValid para isLoginEmailValid e criar um outro chamado isLoginCredentialValid onde vamos validar os dados de entrada para ver se está tudo certo para não dar nenhuma exceção desnecessária. Vou colar o trecho do código mas está tudo no GitHub logo no começo do artigo.

```
<?php
  /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isLoginEmailValid(Request $request)
    {

        return $this->validate($request, [
            'email' => 'required|string',
            'password' =>  'required|string'
        ]);
    }


    public function isLoginCredentiallValid(Request $request)
    {

        return $this->validate($request, [
            'client_id' => 'required|string',
            'client_secret' =>  'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if (isset($request->grant_type)) {
            if ($request->grant_type == 'credential') {
                $token = $this->loginWithCredential($request);
            } else {
                $token = $this->loginWithEmail($request);
            }
        } else {
            $token = $this->loginWithEmail($request);
        }

        if ($token) {
            return $this->respondWithToken($token);
        } else {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function loginWithEmail(Request $request)
    {

        if ($this->isLoginEmailValid($request)) {
            $credentials = $request->only(['email', 'password']);
            $token = auth()->setTTL(env('JWT_TTL', '60'))->attempt($credentials);
            return $token;
        }
    }

    public function loginWithCredential(Request $request)
    {
        if ($this->isLoginCredentiallValid($request)) {
            $credentials = $request->only(['client_id', 'client_secret']);
            $user =  User::where('client_id', $request->client_id)->where('client_secret', $request->client_secret)->first();
            if($user){
                $token = auth()->setTTL(env('JWT_TTL', '60'))->login($user);
                return $token;
            }else{
                return null;
            }

        }
    }
```
> **Para resolver o erro cors**
Criar o arquivo CorsMiddleware na pasta Middleware
```
<?php
namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE, HEAD',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }

        return $response;
    }
}
```
> **em bootstrap/app**
Adicionar antes do middleware Authenticate:
```
$app->middleware([
     App\Http\Middleware\CorsMiddleware::class
]);

```
### Iniciando as configurações de Rules (perfis e permissões)
'''
php artisan make:migration create_roles_table
php artisan make:migration create_abilities_table
php artisan make:migration create_abilityRole_table
php artisan make:migration create_roleUser_table
'''

### Criar as models
'''
php artisan make:model Role
php artisan make:model Ability
'''

### Criação dos Seeders.
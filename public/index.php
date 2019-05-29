<?php
/**
 * Created by PhpStorm.
 * User: duanpei
 * Date: 2019-05-29
 * Time: 09:43
 */


use DI\ContainerBuilder;
use ExampleApp\HelloWorld;
use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Relay\Relay;
use Zend\Diactoros\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use function DI\create;
use function DI\get;
use function FastRoute\simpleDispatcher;

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once dirname(__DIR__) . '/vendor/autoload.php';

// 容器
$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    HelloWorld::class   =>  create(HelloWorld::class)->constructor(get('Foo'), get('Resonpse')),
    'Foo'   =>  'bar',
    'Resonpse'  =>  function () {
        return new Response();
    }
]);
$container = $containerBuilder->build();

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/hello', HelloWorld::class);
});

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
$emitter->emit($response);
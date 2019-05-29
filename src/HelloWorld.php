<?php
/**
 * Created by PhpStorm.
 * User: duanpei
 * Date: 2019-05-29
 * Time: 09:56
 */

namespace ExampleApp;

use Psr\Http\Message\ResponseInterface;

class HelloWorld
{
    private $foo;
    private $response;

    public function __construct(string $foo, ResponseInterface $response)
    {
        $this->foo = $foo;
        $this->response = $response;
    }

    public function __invoke()
    {
        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write("<html><head></head><body>Hello, {$this->foo} world!</body></html>");

        return $response;
    }
}
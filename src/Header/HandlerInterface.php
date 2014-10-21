<?php namespace Ovide\Libs\Mvc\Rest\Header;

use Ovide\Libs\Mvc\Rest;

interface HandlerInterface
{
    public function before();
    public function after(Rest\Response $response);
    public function finish();
}

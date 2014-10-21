<?php namespace Ovide\Libs\Mvc\Rest\Header\Handler;

use Ovide\Libs\Mvc\Rest\Header;

class Etag extends Header\Handler
{
    const HEADER = 'IF_NONE_MATCH';
    
    public function after()
    {
        $response = \Ovide\Libs\Mvc\Rest\App::instance()->di->get('response');
        $content = $response->getContent();
    }

    public function before()
    {
        $value = $this->get();
    }

    public function finish()
    {
        
    }

//put your code here
}

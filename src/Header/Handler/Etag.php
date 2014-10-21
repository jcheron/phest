<?php namespace Ovide\Libs\Mvc\Rest\Header\Handler;

use Ovide\Libs\Mvc\Rest;

class Etag extends Rest\Header\Handler
{
    const HEADER = 'HTTP_IF_NONE_MATCH';
    
    public function after(Rest\Response $response)
    {
        $etag = md5($response->getContent());
        $response->setEtag($etag);
        if ($etag == $this->_content) {
            $response->setContent('');
            $response->setStatusCode(
                Rest\Response::NOT_MODIFIED,
                Rest\Response::$status[Rest\Response::NOT_MODIFIED]
            );
        }
    }

    public function before()
    {
        
    }

    public function finish()
    {
        
    }
}

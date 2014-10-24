<?php namespace Mocks\Headers;

use Ovide\Libs\Mvc\Rest;

class Basic extends Rest\Header\Handler
{
    const HEADER = 'FOO';
    
    public static $_handleCalled = 0;
    
    public static $_initCalled = 0;
    
    public function init()
    {
        self::$_initCalled++;
        return parent::init();
    }
    
    public function before()
    {
        self::$_handleCalled++;
    }

    public function after(Rest\Response $response) {
        
    }

    public function finish() {
        
    }

}

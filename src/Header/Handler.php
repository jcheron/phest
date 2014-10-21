<?php namespace Ovide\Libs\Mvc\Rest\Header;

use Ovide\Libs\Mvc\Rest\App;

abstract class Handler implements HandlerInterface
{
    /**
     * @var \Phalcon\Http\RequestInterface
     */
    protected $_request;
    /**
     * @var string
     */
    protected $_content;

    /**
     * The presence of this header acts as a conditional for the handler
     * Leave empty if there's no precondition
     */
    const HEADER = '';

    public function __construct(\Phalcon\Http\RequestInterface $r)
    {
        $this->_request = $r;
    }

    public function setConfig($key, $value)
    {
        App::instance()->setConfig(static::HEADER, $key, $value);
    }

    public function getConfig($key)
    {
        return App::instance()->getConfig(static::HEADER, $key);
    }

    /**
     * Checks if the header must be present in the request
     * 
     * @return boolean
     */
    public function init()
    {
        if (!static::HEADER || isset($_SERVER[static::HEADER])) {
            $this->_content = $this->_request->getHeader(static::HEADER);
            return true;
        }
        return false;
    }
}

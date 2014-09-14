<?php namespace ovide\libs\mvc;


/**
 * Description of RestController
 * @author Albert Ovide <albert@ovide.net>
 */
class RestController extends \Phalcon\Mvc\Controller
{

    const OK              = 200;
    const CREATED         = 201;
    const ACCEPTED        = 202;
    const NO_CONTENT      = 204;

    const MOVED           = 301;
    const NOT_MODIFIED    = 304;

    const BAD_REQUEST     = 400;
    const UNAUTHORIZED    = 401;
    const FORBIDDEN       = 403;
    const NOT_FOUND       = 404;
    const NOT_ALLOWED     = 405;
    const NOT_ACCEPTABLE  = 406;
    const CONFLICT        = 409;
    const GONE            = 410;
    const UNSUPPORTED_MT  = 415;

    const INTERNAL_ERROR  = 500;
    const NOT_IMPLEMENTED = 501;
    const UNAVAILABLE     = 502;

    protected static $status = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',

        301 => 'Moved Permanently',
        304 => 'Not Modified',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        405 => 'Method not allowed',
        406 => 'Not Acceptable',
        409 => 'Conflict',
        410 => 'Gone',
        415 => 'Unsupported media type',

        500 => 'Internal server error',
        501 => 'Not implemented',
        503 => 'Service unavailable',
    );


    public function index($id=null)
    {
        $method = $this->request->getMethod();
        switch ($method) {
            case 'GET':
                if ($id === null) {
                    $this->_get();
                } else {
                    $this->_getOne($id);
                }
                break;
            case 'POST':
                $this->_post();
                break;
            case 'PUT':
                $this->_put();
                break;
            case 'DELETE':
                $this->_delete($id);
                break;
            default:
                break;
        }
        return $this->response;
    }

    protected function response($content=null, $code=null, $message=null)
    {
        if ($content) {
            $this->buildBody($content);
            if (!$code) $code = self::OK;
        } else if (!$code) $code = self::NO_CONTENT;
        if (!$message) $message = self::$status[$code];
        $this->response->setStatusCode($code, $message);
    }

    protected function buildBody($content)
    {
        $this->response->setContentType('application/json');
        $this->response->setJsonContent($content);
}

    protected function _getOne($id)
    {
        try {
            $this->response($this->getOne($id));
        } catch (\Exception $ex) {
            $this->response($ex->getMessage, $ex->getCode);
        }
    }

    protected function _get()
    {
        try {
            $this->response($this->get());
        } catch (\Exception $ex) {
            $this->response($ex->getMessage, $ex->getCode);
        }
    }

    protected function _post()
    {
        try {
            $obj = $this->request->getPost();
            $this->response($this->post($obj), self::CREATED);
        } catch (\Exception $ex) {
            $this->response($ex->getMessage, $ex->getCode);
        }

    }

    public function _put()
    {
        $r = $this->response;
        try {
            $obj = $this->request->getPost();
            $this->response($this->put($obj));
        } catch (Exception $ex) {
            $this->response($ex->getMessage, $ex->getCode);
        }
    }

    protected function _delete($id)
    {
        try {
            $this->delete($id);
            $this->response('', self::NO_CONTENT);
        } catch (Exception $ex) {
            $this->response($ex->getMessage, $ex->getCode);
        }
    }
}


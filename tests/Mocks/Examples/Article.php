<?php namespace Mocks\Examples;

use Ovide\Libs\Mvc\Rest\Controller;
use Ovide\Libs\Mvc\Rest\Exception;
use Phalcon\Validation;
use Phalcon\Validation\Validator;


class Article extends Controller
{
    const ID   = 'id';
    const PATH = 'articles';
    const RX   = '[0-9]*';
    
    public static $data = [];
    
    public function get()
    {
        $result = [];
        foreach (array_keys(static::$data) as $article_id) {
            $result[] = $this->_content($article_id);
        }
        
        return $result;
    }
    
    public function getOne($id)
    {
        if (isset(static::$data[$id])) {
            throw new Exception\NotFound();
        }
        
        return $this->_content($id);
    }
    
    public function put($id, $obj)
    {
        if (isset(static::$data[$id])) {
            throw new Exception\NotFound();
        }
        
        $this->_validate($obj);
        
        static::$data[$id]['title']   = $obj['title'];
        static::$data[$id]['content'] = $obj['content'];
        static::$data[$id]['updated'] = new \DateTime();
        
        return $this->_content($id);
    }
    
    public function post($obj)
    {
        $this->_validate($obj);
        
        end(static::$data);
        $id = key(static::$data) + 1;
        reset(static::$data);
        
        $now = new \DateTime();
        static::$data[$id] = [
            'id'      => $id,
            'author'  => '', //TODO
            'title'   => $obj['title'],
            'content' => $obj['content'],
            'created' => $now,
            'updated' => $now,
        ];
    }
    
    public function delete($id)
    {
        if (!isset(static::$data[$id])) {
            throw new Exception\NotFound();
        }
        
        unset(static::$data[$id]);
    }

    protected function _validate($obj)
    {
        $validator = new Validation();
        
        $validator->add('title', new Validator\PresenceOf([
            'message' => 'Title required'
        ]));
        $validator->add('content', new Validator\PresenceOf([
            'message' => 'Content required'
        ]));
        
        $validator->add('title', new Validator\StringLength([
            'max'            => 255,
            'min'            => 2,
            'messageMaximum' => 'Title is too long',
            'min'            => 'Title is too short'
        ]));
        
        $msg = $validator->validate($obj);
        if ($msg->count()) {
            $msg->rewind();
            $cur = $msg->current();
            throw new Exception\BadRequest($cur->getMessage());
        }
    }
    
    protected function _content($id)
    {
        return [
            'id'       => $id,
            'author'   => '/'.User::PATH.'/'.static::$data[$id]['author'],
            'title'    => static::$data[$id]['title'],
            'content'  => static::$data[$id]['content'],
            'created'  => static::$data[$id]['created'],
            'updated'  => static::$data[$id]['updated'],
            'comments' => '/'.static::PATH.'/'.$id.'/comments/'
        ];
    }
}

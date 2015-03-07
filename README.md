phest 
=====



###Install with composer

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ovide/phest.git"
        }
    ],
    "require": {
        "ovide/phest": "dev-master"
    }
}
```

###Usage example

####index.php
```php
<?php

use Ovide\Libs\Mvc\Rest\App;

require __DIR__.'/../vendor/autoload.php';

$loader = new \Phalcon\Loader();
//Register dirs [Optional]
$loader->registerDirs(
		array(
			"./../app/controllers",
			"./../app/models"
		)
)->register();

$app = App::instance();

//Set up the database service
$app->di->set('db', function(){
	return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			"host" => "localhost",
			"username" => "me",
			"password" => "",
			"dbname" => "MyDB",
			"options" => array(
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' //UTF-8 is important for PHP JSON Encoding
			)
	));
});

$app->addResources([MyController::class,otherController::class]);

$app->handle();
```


####Your controllers
```php
<?php

use Ovide\Libs\Mvc\Rest

class MyController extends Rest\Controller
{
    public function get()
    {
        return User::find()->toArray();
    }

    public function getOne($id)
    {
        return User::findFirst($id)->toArray();
    }

    public function post($obj)
    {
        //Save the object
        $user = new User();
        $user->create($obj);
        return $user->toArray();
    }

    public function put($id, $obj)
    {
        $user = User::findFirst($id);
        //...
        $user->save();
        return $obj;
    }

    public function delete($id)
    {
        $user = User::findFirst($id);
        $user->delete();
        //No return here
    }
}

class Comment extends Rest\Controller
{
    public function get($userID)
    {
        //...
    }

    public function getOne($userID, $commentID)
    {
        //...
    }

    public function post($userID, $obj)
    {
        //...
    }

    public function put($userID, $commentID, $obj)
    {
        //...
    }

    public function delete($userID, $commentID)
    {
        //...
    }
}
```

Use exceptions if something goes wrong

```php
public function getOne($id)
{
    if (!$foo = Foo::findFirst($id))
        throw new Rest\Exception\NotFound("Ooops! Foo $id not found");
    return $foo->toArray();
}

public function post($fooObj)
{
    if (!$token = getToken()) {
        throw new Rest\Exception\Unauthorized("You must login first");
    }
    if (!canPostWith($token)) {
        throw new Rest\Exception\Forbidden("You can't post here")
    }
    if (alreadyExists($fooObj)) {
        throw new Rest\Exception\Conflict("That object already exists!")
    }
    //...
    return $newObj->toArray();
}
```
###Next

- Add header handlers:
  - Content-Type / Accept
  - ETag / If-None-Match

- Add more options to the router
- Add HEAD and PATCH verbs



[![Build Status](https://travis-ci.org/ovide/phest.svg?branch=master)](https://travis-ci.org/ovide/phest)

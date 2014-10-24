<?php

use Ovide\Libs\Mvc\Rest\App;

class HeaderHandlerTest extends \Codeception\TestCase\Test
{
    /**
    * @var \UnitTester
    */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSetConfig()
    {
        $I = $this->tester;

        $_SERVER[Mocks\Headers\Basic::HEADER] = 'var';
        $app = App::instance();
        $request  = new \Phalcon\Http\Request();
        $header = new Mocks\Headers\Basic($request);

        $header->setConfig('bar', 'var');

        $this->assertEquals([Mocks\Headers\Basic::HEADER => ['bar' => 'var']],
                PHPUnit_Framework_Assert::readAttribute($app, '_config'));
    }

    /**
     * @depends testSetConfig
     */
    public function testGetConfig()
    {
        $I = $this->tester;

        $_SERVER[Mocks\Headers\Basic::HEADER] = 'var';
        $app = App::instance();
        $request  = new \Phalcon\Http\Request();
        $header = new Mocks\Headers\Basic($request);
        $header->setConfig('bar', 'var');

        $actual   = $header->getConfig('bar');
        $expected = 'var';

        $I->assertEquals($expected, $actual);
    }
}

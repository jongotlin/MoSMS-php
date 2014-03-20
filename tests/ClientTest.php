<?php
/*
 * This file is part of the MoSMS package.
 *
 * (c) Timmy Sjöstedt <git@iostream.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ .'/../MoSMS/Client.php';
require __DIR__ .'/TestConnector.php';

class ClientTest extends PHPUnit_Framework_TestCase
{
    protected $C;
    protected $M;

    protected function setUp()
    {
        $this->C = new TestConnector;
        $this->M = new MoSMS\Client($this->C);
        $this->M->setCredentials('foo', 'bar');
    }

    public function testBalance()
    {
        $this->C->setExpectedInput('/se/api-info.php?type=credits&username=foo&password=bar');
        $this->C->setResponse('credits=1337');

        $this->assertEquals($this->M->getBalance(), 1337);
    }

    /**
     * @dataProvider listsProvider
     */
    public function testLists($response, $result)
    {
        $this->C->setExpectedInput('/se/api-info.php?type=lists&username=foo&password=bar');
        $this->C->setResponse($response);

        $this->assertEquals($this->M->getLists(), $result);
    }

    public function listsProvider()
    {
        return array(
            array('6', array()),
            array('lists=foo|', array('foo')),
            array('lists=foo|bar|', array('foo', 'bar')),
        );
    }

    /**
     * @dataProvider listProvider
     */
    public function testList($response, $result)
    {
        $list = 'foo';

        $this->C->setExpectedInput('/se/api-info.php?type=members&p1='. $list .'&username=foo&password=bar');
        $this->C->setResponse($response);

        $this->assertEquals($this->M->getList($list), $result);
    }

    public function listProvider()
    {
        return array(
            array('members=', array()),
            array('members=bar|46701234567|', array(
                array(
                    'name'      => 'bar',
                    'number'    => '46701234567'
                )
            )),
        );
    }

    /**
     * @expectedException MoSMS\NotFoundException
     */
    public function testListNotFound()
    {
        $list = 'foo';

        $this->C->setExpectedInput('/se/api-info.php?type=members&p1='. $list .'&username=foo&password=bar');
        $this->C->setResponse('4');

        $this->M->getList($list);
    }

    /**
     * @dataProvider tariffProvider
     */
    public function testTariff($tariff, $isValid)
    {
        $this->assertEquals($this->M->isValidTariff($tariff), $isValid);
    }

    public function tariffProvider()
    {
        $tariffs = array();
        for ($i = 5; $i <= 200; $i >= 30 ? $i+=10 : $i+=5) {
            $tariffs[] = array($i, true);
        }

        return array_merge(
            $tariffs,
            array(
                array(0, false),
                array(210, false),
            )
        );
    }
}

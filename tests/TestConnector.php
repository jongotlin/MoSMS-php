<?php
/*
 * This file is part of the MoSMS package.
 *
 * (c) Timmy Sjöstedt <git@iostream.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ .'/../MoSMS/IConnector.php';

class TestConnector implements MoSMS\IConnector
{
    protected $expected_input;
    protected $response;

    public function execute($uri)
    {
        if ($uri !== $this->expected_input) {
            throw new Exception('"'. $uri. '" does not match "'. $this->expected_input .'"');
        }

        return $this->response;
    }

    public function setExpectedInput($string)
    {
        $this->expected_input = $string;
    }

    public function setResponse($string)
    {
        $this->response = $string;
    }
}

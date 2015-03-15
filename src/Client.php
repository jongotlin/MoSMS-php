<?php
/*
 * This file is part of the MoSMS package.
 *
 * (c) Timmy Sjöstedt <git@iostream.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace brajox\MoSMS;

class Client
{
    protected $connector;

    protected $username = '';
    protected $password = '';

    public function __construct(IConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Get the account balance in öre (cents) excl. VAT
     *
     * @return int
     */
    public function getBalance() {
        $result = $this->callConnector(
            'api-info',
            array('type' => 'credits')
        );

        return (int) substr($result, 8);
    }

    /**
     * Return names of all lists
     *
     * @return array
     */
    public function getLists() {
        $result = $this->callConnector(
            'api-info',
            array('type' => 'lists')
        );

        if ($result === "6") {
            // No lists
            return array();
        }

        $lists = trim(substr($result, 6), '|');

        return explode('|', $lists);
    }

    /**
     * Return members of a specified list
     *
     * @return array Associative arrays with "name" and "number"
     * @throws NotFoundException
     */
    public function getList($list) {
        $result = $this->callConnector(
            'api-info',
            array('type' => 'members', 'p1' => $list)
        );

        if ($result === "4") {
            throw new NotFoundException('Specified list "'. $list .'" does not exist');
        }

        $members = substr($result, 8);

        if (empty($members)) {
            return array();
        }

        $data = explode('|', trim($members, '|'));
        $list_data = array();
        
        for ($i = 0; $i < count($data); $i += 2) {
            $list_data[] = array(
                'name'      => $data[$i],
                'number'    => $data[$i+1],
            );
        }

        return $list_data;
    }

    /**
     * Return a boolean whether or not the specified tariff is valid
     *
     * @return boolean
     */
    public function isValidTariff($tariff) {
        return ($tariff > 0 && $tariff <= 200)
                && (
                    ($tariff % 10 === 0) || ($tariff < 30 && $tariff % 5 === 0)
                );
    }

    /**
     * Specify your username and password for use with the web service
     */
    public function setCredentials($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param string $number
     * @param string $message
     *
     * @return boolean True if SMS was sent, false otherwise
     * @throws RuntimeException
     * @throws ArgumentsException
     */
    public function sendSms($number, $message) {
        $result = $this->callConnector(
            'sms-send',
            [
                'type' => 'text',
                'nr' => $number,
                'data' => utf8_decode($message),
            ]
        );

        if ($result === "7") {
            throw new ArgumentsException(sprintf('Number %s is not a valid phone number', $number));
        } elseif ($result === "99") {
            throw new RuntimeException('3rd party error. Detailed message in sms-log');
        }

        return $result === "0";
    }

    protected function callConnector($page, array $data = array()) {
        $data += array(
            'username' => $this->username,
            'password' => $this->password
        );

        $uri = '/se/'. $page .'.php?'. http_build_query($data);

        $result = $this->connector->execute($uri);

        if ($result === "1") {
            throw new ArgumentsException("Invalid arguments");
        } else if ($result === "2") {
            throw new AuthException("Wrong username and/or password");
        } else if ($result === "3") {
            throw new NotFoundException("Could not find service");
        }

        return $result;
    }
}

class Exception extends \Exception {}
class AuthException extends Exception {}
class ArgumentsException extends Exception {}
class NotFoundException extends Exception {}
class RuntimeException extends Exception {}

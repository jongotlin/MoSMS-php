<?php
/*
 * This file is part of the MoSMS package.
 *
 * (c) Timmy Sjöstedt <git@iostream.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MoSMS;

require __DIR__ .'/IConnector.php';

class HTTPConnector implements IConnector
{
    protected $hostname;
    protected $port;

    public function __construct($hostname = 'www.mosms.com', $port = 443)
    {
        $this->hostname = $hostname;
        $this->port     = $port;
    }

    public function execute($uri)
    {
        $url = 'https://'. $this->hostname .':'. $this->port . $uri;

        $c = curl_init($url);
        $o = array(
            CURLOPT_RETURNTRANSFER      => true,
        );
        curl_setopt_array($c, $o);

        $result     = curl_exec($c);
        curl_close($c);

        return $result;
    }
}

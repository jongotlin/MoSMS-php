MoSMS
=====

A library to communicate with the [MoSMS](https://www.mosms.com) web service.

Example
-------

```php
<?php
require 'MoSMS/Client.php';
require 'MoSMS/HTTPConnector.php';

$username = 'username';
$password = 'password';

$M = new MoSMS\Client(new MoSMS\HTTPConnector);
$M->setCredentials($username, $password);

$balance = $M->getBalance();

echo 'Account balance: '. ($balance/100) .' SEK excl. VAT'."\n";
```

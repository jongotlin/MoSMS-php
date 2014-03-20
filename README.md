MoSMS
=====

[![Latest Stable Version](https://poser.pugx.org/brajox/mosms/v/stable.png)](https://packagist.org/packages/brajox/mosms)
[![License](https://poser.pugx.org/brajox/mosms/license.png)](https://packagist.org/packages/brajox/mosms)

A library to communicate with the [MoSMS](https://www.mosms.com) web service.

Example
-------

```php
<?php

use brajox\MoSMS;

$username = 'username';
$password = 'password';

$M = new MoSMS\Client(new MoSMS\HTTPConnector);
$M->setCredentials($username, $password);

$balance = $M->getBalance();

echo 'Account balance: '. ($balance/100) .' SEK excl. VAT'."\n";
```

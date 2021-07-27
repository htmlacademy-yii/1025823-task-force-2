<?php

require_once './vendor/autoload.php';

use \HtmlAcademy\Tack;

$_SESSION['user']['role'] = 'customer';

$task = new Tack(1,2);

echo $task->getNextStatus('working');

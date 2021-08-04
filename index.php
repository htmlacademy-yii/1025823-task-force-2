<?php

require_once './vendor/autoload.php';

use \HtmlAcademy\Tack;

assert_options(ASSERT_ACTIVE, 1);

$task = new Tack(1,2);

// Проверка получения значения из карты действий
assert($task->getActionsMap()['respond'] == 'Откликнуться', 'action respond = Откликнуться' );

// Проверка получения значения из карты статусов
assert($task->getStatusesMap()['working'] == 'Выполняется', 'status working = Выполняется' );

// Проверка доступных действий для указанного статуса

assert($task->getAvailableActions(Tack::STATUS_WORKING)[Tack::ROLE_CUSTOMER] == Tack::ACTION_COMPLETE, 'Available action = complete' );
assert($task->getAvailableActions(Tack::STATUS_NEW)[Tack::ROLE_EXECUTOR] == Tack::ACTION_RESPOND, 'Available action = respond' );

// Проверка  статуса, в которой он перейдёт после выполнения указанного действия
// После последней проверки статус - New

assert($task->getNextStatus(Tack::ACTION_RESPOND) == Tack::STATUS_WORKING, 'Next status = working' );




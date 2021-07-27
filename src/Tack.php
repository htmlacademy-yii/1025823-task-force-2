<?php


namespace HtmlAcademy;


class Tack
{
    /**
     * Статусы
     */
    private const STATUS_NEW = 'new'; // новое задание
    private const STATUS_CANCELLED = 'cancelled'; // задание отменено
    private const STATUS_WORKING = 'working'; // задание в работе
    private const STATUS_COMPLETED = 'completed'; // задание завершено
    private const STATUS_FAILED = 'failed '; // задание провалено

    /**
     * Действия
     */
    private const ACTION_CANCEL = 'cancell'; // отменить задание
    private const ACTION_RESPOND = 'respond'; // откликнуться на задание
    private const ACTION_COMPLETE = 'complete'; // отметить как выполненное
    private const ACTION_REFUSE = 'refuse'; // отказаться от задания

    /**
     * Роли
     */
    private const ROLE_EXECUTOR = 'executor';
    private const ROLE_CUSTOMER= 'customer';

    /**
     * Карта статусов
     *
     * @var string[]
     */
    private $statuses_map = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELLED => 'Отменено',
        self::STATUS_WORKING => 'Выполняется',
        self::STATUS_COMPLETED=> 'Завершено',
        self::STATUS_FAILED=> 'Провалено',
            ];

    /**
     * Карта действий
     *
     * @var string[]
     */
    private $actions_map = [
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_COMPLETE => 'Завершить',
        self::ACTION_REFUSE => 'Отказаться'
    ];

    /**
     * Текущая роль
     *
     * @var string
     */
    private $current_status = self::STATUS_NEW;

    /**
     * ID исполнителя
     *
     * @var mixed|null
     */
    private $executor_id = null;

    /**
     * ID заказчика
     *
     * @var mixed|null
     */
    private $customer_id = null;

    /**
     * Роль по-умолчанию
     *
     * @var string
     *
     * default -> 'customer';
     */
    private $role = self::ROLE_CUSTOMER;

    /**
     * Tack constructor.
     * @param $customer_id
     * @param null $executor_id
     */
    public function __construct($customer_id, $executor_id = null)
    {
        $this->customer_id = $customer_id;
        $this->executor_id = $executor_id;
        $this->setRole();
    }

    /**
     * Ф-я берет роль пользователя из сессии, проверяет, что она является допустимой и устанавливает ее текущей
     */
    private function setRole()
    {
        if (isset( $_SESSION['user']['role']) && in_array( $_SESSION['user']['role'], $this->getRoles() )){
            $this->role = $_SESSION['user']['role'];
        }
    }

    /**
     * Ф-я возвращает массив доустимых ролей
     *
     * @return string[]
     */
    private function getRoles()
    {
        return [self::ROLE_EXECUTOR, self::ROLE_CUSTOMER];
    }

    /**
     * Ф-я возвращает допустимых дейтвий в зависимости от переданного статуса
     *
     * @param $status
     * @return array|string[]
     */
    public function getAvailableActions($status)
    {
        switch ($status) {
            case self::STATUS_NEW :
                return [
                    self::ROLE_EXECUTOR => self::ACTION_RESPOND,
                    self::ROLE_CUSTOMER => self::ACTION_CANCEL
                ];
             case self::STATUS_WORKING :
                return [
                    self::ROLE_EXECUTOR => self::ACTION_REFUSE,
                    self::ROLE_CUSTOMER => self::ACTION_COMPLETE
                ];
            default:
                return [];
        }
    }

    /**
     * Ф-я возвращает допустимое действие в зависимости от роли зарегистрированного пользователя
     *
     * @param $current_status
     * @return mixed|string
     */
    public function getNextStatus($current_status)
    {
        $available_actions = $this->getAvailableActions($current_status);
        if (!$available_actions){
            die('Нет доступных действий');
        }
        switch ($this->role){
            case self::ROLE_CUSTOMER:
                return $available_actions[self::ROLE_CUSTOMER];
            case self::ROLE_EXECUTOR:
                return $available_actions[self::ROLE_EXECUTOR];
            default:
                return 'Статус недоступен';
        }
    }
}

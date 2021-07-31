<?php


namespace HtmlAcademy;


class Tack
{
    /**
     * Статусы
     */
    const STATUS_NEW = 'new'; // новое задание
    const STATUS_CANCELLED = 'cancelled'; // задание отменено
    const STATUS_WORKING = 'working'; // задание в работе
    const STATUS_COMPLETED = 'completed'; // задание завершено
    const STATUS_FAILED = 'failed '; // задание провалено

    /**
     * Действия
     */
    const ACTION_CANCEL = 'cancell'; // отменить задание
    const ACTION_RESPOND = 'respond'; // откликнуться на задание
    const ACTION_COMPLETE = 'complete'; // отметить как выполненное
    const ACTION_REFUSE = 'refuse'; // отказаться от задания

    /**
     * Роли
     */
    const ROLE_EXECUTOR = 'executor';
    const ROLE_CUSTOMER= 'customer';

    /**
     * Карта статусов
     *
     * @var string[]
     */
    private $statusesMap = [
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
    private $actionsMap = [
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
    private $currentStatus = self::STATUS_NEW;

    /**
     * ID исполнителя
     *
     * @var mixed|null
     */
    private $executorId = null;

    /**
     * ID заказчика
     *
     * @var mixed|null
     */
    private $customerId = null;
    /**
     * Tack constructor.
     * @param $customer_id
     * @param null $executor_id
     */
    public function __construct($customerId, $executorId = null)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    /**
     * Ф-я возвращает карту статусов
     *
     * @return array|string[]
     */
    public function getStatusesMap()
    {
        return $this->statusesMap;
    }

    /**
     * Ф-я возвращает карту действий
     *
     * @return array|string[]
     */
    public function getActionsMap()
    {
        return $this->actionsMap;
    }

    /**
     * Ф-я возвращает допустимых дейтвий в зависимости от переданного статуса
     *
     * @param $status
     * @return array|string[]
     */
    public function getAvailableActions($status): array
    {
        $this->currentStatus = $status;
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
     * Ф-я возвращает следующий статус в зависимости от действия
     *
     * @param $current_status
     * @return mixed|string
     */
    public function getNextStatus($action): string
    {
        switch ($action) {
            case self::ACTION_RESPOND :
                return  self::STATUS_WORKING;
            case self::ACTION_CANCEL :
                return self::STATUS_CANCELLED;
            case self::ACTION_COMPLETE:
                return self::STATUS_COMPLETED;
            case self::ACTION_REFUSE:
                return self::STATUS_FAILED;
            default:
                throw new \Exception('Статус недоступен');
        }
    }

}

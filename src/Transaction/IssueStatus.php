<?php

namespace Erachain\Transaction;

use Erachain\Item\StatusItem;

class IssueStatus extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_mode)
    {
        parent::__construct($public_key, $private_key, $erachain_mode);

        $this->set_default_params(array(
            'type_item'   => 0,
            'owner'       => $public_key,
            'name'        => '',
            'icon'        => '',
            'image'       => '',
            'description' => ''
        ));

        $this->set_transaction_type([25, 0, 0, 0]);
    }

    /**
     * Формируем вторую часть транзакции
     *
     * @return array Возвращает массив с второй частью транзакции
     */
    protected function get_data_last()
    {
        $params = $this->get_valid_data($this->get_params());

        return $this->data_assembly($params);
    }

    /**
     * Формируем данные по статусу
     *
     * @param array $params Массив данных по статусу
     *  $params = [
     *      'type_item' => (int) тип статуса (1 - уникальный, 2 - не уникальный),
     *      'owner' => (string) публичный ключ владельца статуса,
     *      'name' => (string) название статуса,
     *      'icon' => (string) путь к иконки статуса,
     *      'image' => (string) путь к изображению статуса,
     *      'description' => (string) описание статуса с указанием параметров подстановки:
     *          %1 - параметр подстановки первого числового значения
     *          %2 - параметр подстановки второго числового значения
     *          %3 - параметр подстановки первого строкового значения
     *          %4 - параметр подстановки второго строкового значения
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        $status_item = new StatusItem($params);

        return $status_item->get();
    }
}
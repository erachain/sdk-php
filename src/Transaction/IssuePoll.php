<?php

namespace Erachain\Transaction;

use Erachain\Item\PollItem;

class IssuePoll extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'owner'       => $public_key,
            'name'        => '',
            'icon'        => '',
            'image'       => '',
            'description' => '',
            'options'     => []
        ));

        $this->set_transaction_type([28, 0, 0, 0]);
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
     * Формируем данные по голосованию
     *
     * @param array $params Массив данных по голосованию
     *  $params = [
     *      'owner' => (string) публичный ключ владельца голосования,
     *      'name' => (string) название голосования,
     *      'icon' => (string) путь к иконке голосования (не обязательно),
     *      'image' => (string) путь к изображению голосования (не обязательно),
     *      'description' => (string) описание голосования,
     *      'options' => (array) массив с вариантами ответов
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        $poll_item = new PollItem($params);

        return $poll_item->get();
    }
}
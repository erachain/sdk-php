<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;

class VoteOnPoll extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_mode)
    {
        parent::__construct($public_key, $private_key, $erachain_mode);

        $this->set_default_params(array(
            'poll_key'      => 0,
            'option_number' => 0
        ));

        $this->set_transaction_type([63, 0, 0, 0]);
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
     * Формируем данные по голосованию в опросе
     *
     * @param array $params Массив данных по голосованию в опросе
     *  $params = [
     *      'poll_key' => (int) ключ голосования,
     *      'option_number' => (int) номер выбранного ответа
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        //ITEM_KEY
        $data = ConvertToBytes::from_int64($params["poll_key"]);

        //OPTION_N
        return array_merge($data, ConvertToBytes::from_int32($params["option_number"]));
    }
}
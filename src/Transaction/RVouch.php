<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;

class RVouch extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_mode)
    {
        parent::__construct($public_key, $private_key, $erachain_mode);

        $this->set_default_params(array(
            'block_height' => 0,
            'seq_number'   => 0
        ));

        $this->set_transaction_type([40, 0, 0, 0]);
    }

    /**
     * Формируем вторую часть транзакции
     *
     * @return array Возвращает массив с второй частью транзакции
     */
    protected function get_data_last()
    {
        return $this->data_assembly($this->get_params());
    }

    /**
     * Формируем данные по подписанию транзакции
     *
     * @param array $params Массив данных по подписанию транзакции
     *  $params = [
     *      'block_height' => (int) номер блока,
     *      'seq_number' => (int) номер транзакции в блоке
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        //BLOCK_HEIGHT
        $data = ConvertToBytes::from_int32($params["block_height"]);

        //TRANSACTION_NUMBER_IN_BLOCK
        return array_merge($data, ConvertToBytes::from_int32($params["seq_number"]));
    }

}
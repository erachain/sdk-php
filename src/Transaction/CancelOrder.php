<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;
use StephenHill\Base58;

class CancelOrder extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'signature' => ''
        ));

        $this->set_transaction_type([51, 0, 0, 0]);
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
     * Формируем данные по отмене ордера
     *
     * @param array $params Массив данных по отмене ордера
     *  $params = [
     *      'signature' => (string) сигнатура ордера, который нужно отменить
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        $base58 = new Base58();

        //SIGNATURE ORDER
        return ConvertToBytes::from_string($base58->decode($params['signature']));
    }
}
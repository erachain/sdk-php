<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;
use StephenHill\Base58;

class CertifyPerson extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'person_key' => 0,
            'public_key' => ''
        ));

        $this->set_transaction_type([36, 0, 1, 0]);
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
     * Формируем данные по подтверждению персоны
     *
     * @param array $params Массив данных по персоне
     *  $params = [
     *      'person_key' => (int) ключ персоны,
     *      'public_key' => (string) публичный ключ персоны которую подтверждаем
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    protected function data_assembly(array $params)
    {
        $base58 = new Base58();

        $data = array();
        //PERSON KEY
        $data = array_merge($data, ConvertToBytes::from_int64($params['person_key']));
        //ACCOUNT PUBLIC KEY
        $data = array_merge($data, ConvertToBytes::from_string($base58->decode($params['public_key'])));

        //DAY
        return array_merge($data, ConvertToBytes::from_int32(2 * 356));
    }

}
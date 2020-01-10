<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;
use StephenHill\Base58;

class IssuePerson extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'raw' => ''
        ));

        $this->set_transaction_type([24, 0, 0, 0]);
    }

    /**
     * Формируем вторую часть транзакции
     *
     * @return array Возвращает массив с второй частью транзакции
     */
    protected function get_data_last()
    {
        $base58 = new Base58();

        $params = $this->get_params();

        return ConvertToBytes::from_string($base58->decode($params['raw']));
    }
}
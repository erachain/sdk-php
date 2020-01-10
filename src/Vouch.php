<?php

namespace Erachain;

use Erachain\Helpers\Error;
use Erachain\Request\VouchRequest;
use Erachain\Transaction\RVouch;
use Exception;
use Sirius\Validation\Validator;

class Vouch
{
    private $erachain_params;
    private $validator;

    public function __construct($erachain_params)
    {
        $this->validator = new Validator;

        $this->erachain_params = $erachain_params;
    }

    /**
     * Подписание транзакции
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для создания статуса
     *  $params = [
     *      'block_height' => (int) номер блока,
     *      'seq_number' => (int) номер транзакции в блоке
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/vouch/vouch.php
     */
    public function sign($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $vouch   = new RVouch($public_key, $private_key, $this->erachain_params);
            $request = new VouchRequest($this->erachain_params);

            $this->validator
                ->add('block_height', 'required | integer() (' . Error::INTEGER . ')')
                ->add('seq_number', 'required | integer() (' . Error::INTEGER . ')');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $vouch->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }
}
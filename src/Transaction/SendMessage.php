<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;
use StephenHill\Base58;

class SendMessage extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_mode)
    {
        parent::__construct($public_key, $private_key, $erachain_mode);

        $this->set_default_params(array(
            'recipient' => '',
            'head'      => '',
            'message'   => '',
            'encrypted' => 1,
            'is_text'   => 1
        ));

        $this->set_transaction_type([31, 0, -128, 0]);
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
     * Формируем данные по отправке сообщения
     *
     * @param array $params Массив данных по сообщению
     *  $params = [
     *      'recipient' => (string) адресс получателя,
     *      'head' => (string) заголовок сообщения,
     *      'message' => (string) сообщение,
     *      'encrypted' => (int) шифрование сообщения (0|1, не обязательно),
     *      'is_text' => (int) сообщение является текстом (0|1, не обязательно)
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        $base58 = new Base58();

        //RECIPIENT
        $data = ConvertToBytes::from_string($base58->decode($params['recipient']));
        //HEAD LENGTH && HEAD
        $data = array_merge($data, ConvertToBytes::filter_data($params["head"], 'string', 1));
        //MESSAGE LENGTH && MESSAGE
        $data = array_merge($data, ConvertToBytes::filter_data($params["message"], 'string', 4));
        //ENCRYPTED
        $data = array_merge($data, [$params['encrypted']]);

        //IS_TEXT
        return array_merge($data, [$params['is_text']]);
    }
}
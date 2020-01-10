<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;
use StephenHill\Base58;

class SendAsset extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'recipient' => '',
            'asset_key' => 0,
            'amount'    => '',
            'head'      => '',
            'message'   => '',
            'encrypted' => 1,
            'is_text'   => 1
        ));

        $this->set_transaction_type([31, 0, 0, 0]);
    }

    /**
     * Хук для работы с данными перед формированием данных транзакции
     */
    protected function before_data()
    {
        $params = $this->get_params();

        $this->send_asset_amount_scale($params);
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
     * Формируем данные по отправке актива
     *
     * @param array $params Массив данных по активу
     *  $params = [
     *      'recipient' => (string) адресс получателя,
     *      'asset_key' => (int) ключ актива,
     *      'amount' => (string) кол-во передаваемого актива,
     *      'head' => (string) заголовок для отправки актива,
     *      'message' => (string) сообщение для отправки актива (не обязательно),
     *      'encrypted' => (int) шифрование сообщения актива (0|1, не обязательно),
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
        //ASSET_KEY
        $data = array_merge($data, ConvertToBytes::from_int64($params['asset_key']));
        //AMOUNT
        $data = array_merge($data, ConvertToBytes::from_big_decimal($params['amount']));
        //HEAD LENGTH && HEAD
        $data = array_merge($data, ConvertToBytes::filter_data($params["head"], 'string', 1));

        if ( ! empty($params['message'])) {
            //MESSAGE LENGTH && MESSAGE
            $data = array_merge($data, ConvertToBytes::filter_data($params["message"], 'string', 4));
            //ENCRYPTED
            $data = array_merge($data, [$params['encrypted']]);
            //IS_TEXT
            $data = array_merge($data, [$params['is_text']]);
        }

        return $data;
    }

    /**
     * Смещение в типе транзакции для учёта кол-ва знаков после точки в amount
     *
     * @param array $params Массив данных
     */
    private function send_asset_amount_scale(array $params)
    {
        $amount = explode('.', $params['amount']);

        $shift_message = false;

        if (empty($params['message'])) {
            $shift_message = true;
        }

        $this->amount_scale($amount, 3, $shift_message);
    }
}
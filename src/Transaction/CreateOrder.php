<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;

class CreateOrder extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'have_asset'  => 0,
            'want_asset'  => 0,
            'have_amount' => '',
            'want_amount' => ''
        ));

        $this->set_transaction_type([50, 0, 0, 0]);
    }

    /**
     * Хук для работы с данными перед формированием данных транзакции
     */
    protected function before_data()
    {
        $params = $this->get_params();

        $this->create_order_amount_scale($params);
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
     * Формируем данные по ордеру
     *
     * @param array $params Массив данных по ордеру
     *  $params = [
     *      'have_asset' => (int) ключ актива, который хотим обменять,
     *      'want_asset' => (int) ключ актива, который хотим получить,
     *      'have_amount' => (string) кол-во актива которое хотим передать,
     *      'want_amount' => (string) кол-во актива которое хотим получить,
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        //HAVE_ASSET
        $data = ConvertToBytes::from_int64($params['have_asset']);
        //WANT_ASSET
        $data = array_merge($data, ConvertToBytes::from_int64($params['want_asset']));
        //HAVE_AMOUNT
        $data = array_merge($data, ConvertToBytes::from_big_decimal($params['have_amount']));

        //WANT_AMOUNT
        return array_merge($data, ConvertToBytes::from_big_decimal($params['want_amount']));
    }

    /**
     * Смещение в типе транзакции для учёта кол-ва знаков после точки в amount
     *
     * @param array $params Массив данных
     */
    private function create_order_amount_scale(array $params)
    {
        $have_amount = explode('.', $params['have_amount']);
        $want_amount = explode('.', $params['want_amount']);

        $this->amount_scale($have_amount, 2);
        $this->amount_scale($want_amount, 3);
    }
}
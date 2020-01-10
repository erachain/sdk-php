<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;

class SetStatus extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_mode)
    {
        parent::__construct($public_key, $private_key, $erachain_mode);

        $this->set_default_params(array(
            'key_status'    => 0,
            'item_type'     => 0,
            'key_item'      => 0,
            'date_start'    => 0,
            'date_end'      => 0,
            'value_1'       => 0,
            'value_2'       => 0,
            'data_1'        => '',
            'data_2'        => '',
            'ref_to_parent' => 0,
            'description'   => ''
        ));

        $this->set_transaction_type([37, 0, 0, 0]);
    }

    /**
     * Хук для работы с данными перед формированием данных транзакции
     */
    protected function before_data()
    {
        $params = $this->get_params();

        $this->set_status_params_transaction_type($params);
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
     * Формируем данные по статусу
     *
     * @param array $params Массив данных по статусу
     *  $params = [
     *      'key_status' => (int) ключ статуса,
     *      'item_type' => (int) тип назначения статуса:
     *          1 - ASSET_TYPE,
     *          2 - IMPRINT_TYPE,
     *          3 - NOTE_TYPE,
     *          4 - PERSON_TYPE,
     *          5 - STATUS_TYPE,
     *          6 - UNION_TYPE,
     *      'key_item' => (int) ключ конкретного элемента выбранного типа,
     *      'date_start' => (int) время старта действия статуса,
     *      'date_end' => (int) время окончания действия статуса,
     *      'value_1' => (int) первое числовое значение для подстановки,
     *      'value_2' => (int) второе числовое значение для подстановки,
     *      'data_1' => (string) первое строковое значение для подстановки,
     *      'data_2' => (string) второе строковое значение для подстановки,
     *      'ref_to_parent' => (int) путь к изображению статуса,
     *      'description' => (string) описание статуса
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        //KEY_STATUS
        $data = ConvertToBytes::from_int64($params['key_status']);
        //ITEM_TYPE
        $data = array_merge($data, [$params["item_type"]]);
        //KEY_ITEM
        $data = array_merge($data, ConvertToBytes::from_int64($params['key_item']));
        //DATE_START
        $data = array_merge($data, ConvertToBytes::from_int64($params['date_start']));
        //DATE_END
        $data = array_merge($data, ConvertToBytes::from_int64($params['date_end']));

        //VALUE_1
        if ( ! empty($params['value_1'])) {
            $data = array_merge($data, ConvertToBytes::from_int64($params['value_1']));
        }

        //VALUE_2
        if ( ! empty($params['value_2'])) {
            $data = array_merge($data, ConvertToBytes::from_int64($params['value_2']));
        }

        //DATA_1 LENGTH && DATA_1
        if ( ! empty($params['data_1'])) {
            $data = array_merge($data, ConvertToBytes::filter_data($params["data_1"], 'string', 1));
        }

        //DATA_2 LENGTH && DATA_2
        if ( ! empty($params['data_2'])) {
            $data = array_merge($data, ConvertToBytes::filter_data($params["data_2"], 'string', 1));
        }

        //REF_TO_PARENT
        if ( ! empty($params['ref_to_parent'])) {
            $data = array_merge($data, ConvertToBytes::from_int64($params['ref_to_parent']));
        }

        //DESCRIPTION LENGTH && DESCRIPTION
        if ( ! empty($params['description'])) {
            $data = array_merge($data, ConvertToBytes::from_string($params['description']));
        }

        return $data;
    }

    /**
     * Меняем тип транзакции в зависимости от переданных параметров
     *
     * @param array $params Массив данных по статусу
     */
    private function set_status_params_transaction_type(array $params)
    {
        $default_type = 0;

        $params_type = array(
            'value_1'       => 1,
            'value_2'       => 2,
            'data_1'        => 4,
            'data_2'        => 8,
            'ref_to_parent' => 16,
            'description'   => 32
        );

        foreach ($params as $key => $value) {
            if (isset($params_type[$key]) && ! empty($value)) {
                $default_type += $params_type[$key];
            }
        }

        $type = $this->get_transaction_type();

        $type[3] = $default_type;

        $this->set_transaction_type($type);
    }
}
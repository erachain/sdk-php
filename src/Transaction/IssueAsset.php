<?php

namespace Erachain\Transaction;

use Erachain\Item\AssetItem;

class IssueAsset extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'owner'       => $public_key,
            'name'        => '',
            'description' => '',
            'icon'        => '',
            'image'       => '',
            'quantity'    => 1,
            'scale'       => 0,
            'asset_type'  => 1
        ));

        $this->set_transaction_type([21, 0, 0, 0]);
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
     * Формируем данные по активу
     *
     * @param array $params Массив данных по активу
     *  $params = [
     *      'owner' => (string) публичный ключ владельца актива (не обязательно, по умолчанию $public_key),
     *      'name' => (string) имя актива,
     *      'description' => (string) описание актива,
     *      'icon' => (string) путь к иконке актива (не обязательно),
     *      'image' => (string) путь к изображению актива (не обязательно),
     *      'quantity' => (int) кол-во актива,
     *      'scale' => (int) знаков после запятой (испульзуется при передачи актива, если задан 0, то можно передать только целое кол-во актива),
     *      'asset_type' => (int) тип актива
     *  ]
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    private function data_assembly(array $params)
    {
        $asset_item = new AssetItem($params);

        return $asset_item->get();
    }
}
<?php

namespace Erachain\Item;

use Erachain\Helpers\ConvertToBytes;

class AssetItem extends AbstractItem
{
    /**
     * Получаем массив байтов с типом итема
     *
     * @param array $params Массив данных итема
     *
     * @return array Возвращает массив байтов
     */
    protected function get_type(array $params)
    {
        return [2, 0];
    }

    /**
     * Получаем массив байтов с дополнителями полями итема
     *
     * @return array Возвращает массив байтов
     */
    protected function after_data()
    {
        $quantity   = ConvertToBytes::from_int64($this->params['quantity']);
        $scale      = [$this->params['scale']];
        $asset_type = [$this->params['asset_type']];

        return array_merge($quantity, $scale, $asset_type);
    }
}
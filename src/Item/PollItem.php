<?php

namespace Erachain\Item;

use Erachain\Helpers\ConvertToBytes;

class PollItem extends AbstractItem
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
        return [1, 0];
    }

    /**
     * Получаем массив байтов с дополнителями полями итема
     *
     * @return array Возвращает массив байтов
     */
    protected function after_data()
    {
        $count_options = ConvertToBytes::from_int32(count($this->params['options']));
        $options       = [];

        foreach ($this->params["options"] as $option) {
            $options = array_merge($options, ConvertToBytes::filter_data($option, 'string', 1));
        }

        return array_merge($count_options, $options);
    }
}
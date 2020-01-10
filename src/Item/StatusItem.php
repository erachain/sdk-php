<?php

namespace Erachain\Item;

class StatusItem extends AbstractItem
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
        return [1, $params['type_item']];
    }
}
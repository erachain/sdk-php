<?php

namespace Erachain\Item;

use Erachain\Helpers\ConvertToBytes;

class PersonItem extends AbstractItem
{
    private $owner_sign;

    public function __construct(array $params, $no_length = false)
    {
        parent::__construct($params, $no_length);

        $this->owner_sign = $no_length;
    }

    /**
     * Получаем массив байтов с типом итема
     *
     * @param array $params Массив данных итема
     *
     * @return array Возвращает массив байтов
     */
    protected function get_type(array $params)
    {
        if ($this->owner_sign) {
            return [];
        }

        return [1, 1];
    }

    /**
     * Получаем массив байтов с публичным ключем владельца итема
     *
     * @param array $params Массив данных итема
     *
     * @return array Возвращает массив байтов
     */
    protected function get_owner(array $params)
    {
        if ($this->owner_sign) {
            return [];
        }

        return parent::get_owner($params);
    }

    /**
     * Получаем массив байтов с иконкой итема
     *
     * @param array $params Массив данных итема
     *
     * @return array Возвращает массив байтов
     */
    protected function get_icon(array $params)
    {
        if ($this->owner_sign) {
            return [];
        }

        return parent::get_icon($params);
    }

    /**
     * Получаем массив байтов с дополнителями полями итема
     *
     * @return array Возвращает массив байтов
     */
    protected function after_data()
    {
        $birthday        = ConvertToBytes::from_int64($this->params["birthday"]);
        $death_day       = ConvertToBytes::from_int64($this->params["death_day"]);
        $gender          = [$this->params["gender"]];
        $race            = ConvertToBytes::filter_data($this->params["race"], 'string', 1);
        $birth_latitude  = ConvertToBytes::from_float($this->params["birth_latitude"]);
        $birth_longitude = ConvertToBytes::from_float($this->params["birth_longitude"]);
        $skin_color      = ConvertToBytes::filter_data($this->params["skin_color"], 'string', 1);
        $eye_color       = ConvertToBytes::filter_data($this->params["eye_color"], 'string', 1);
        $hair_color      = ConvertToBytes::filter_data($this->params["hair_color"], 'string', 1);
        $height          = [$this->params["height"]];

        return array_merge($birthday, $death_day, $gender, $race, $birth_latitude, $birth_longitude, $skin_color,
            $eye_color, $hair_color, $height);
    }
}
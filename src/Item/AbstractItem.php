<?php

namespace Erachain\Item;

use Erachain\Helpers\ConvertToBytes;
use StephenHill\Base58;

abstract class AbstractItem
{
    private $type;
    private $owner;
    private $name;
    private $icon;
    private $image;
    private $description;

    protected $params;

    public function __construct(array $params, $no_length = false)
    {
        $this->type        = $this->get_type($params);
        $this->owner       = $this->get_owner($params);
        $this->name        = $this->get_name($params, $no_length);
        $this->icon        = $this->get_icon($params);
        $this->image       = $this->get_image($params, $no_length);
        $this->description = $this->get_description($params, $no_length);
        $this->params      = $params;
    }

    /**
     * Получаем массив данных по итему
     *
     * @return array Возвращает массив
     */
    public function get()
    {
        $result = array_merge(
            $this->type,
            $this->owner,
            $this->name,
            $this->icon,
            $this->image,
            $this->description
        );

        return array_merge($result, $this->after_data());
    }

    /**
     * Получаем массив байтов с типом итема
     *
     * @param array $params Массив данных итема
     *
     * @return array Возвращает массив байтов
     */
    abstract protected function get_type(array $params);

    /**
     * Получаем массив байтов с публичным ключем владельца итема
     *
     * @param array $params Массив данных итема
     *
     * @return array Возвращает массив байтов
     */
    protected function get_owner(array $params)
    {
        $base58 = new Base58();

        return ConvertToBytes::from_string($base58->decode($params["owner"]));
    }

    /**
     * Получаем массив байтов с именем итема
     *
     * @param array $params Массив данных итема
     * @param bool $no_length Не добавлять длинну массива байтов
     *
     * @return array Возвращает массив байтов
     */
    protected function get_name(array $params, $no_length)
    {
        return ConvertToBytes::filter_data($params["name"], 'string', 1, $no_length);
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
        return ConvertToBytes::filter_data($params["icon"], 'image', 2);
    }

    /**
     * Получаем массив байтов с изображением итема
     *
     * @param array $params Массив данных итема
     * @param bool $no_length Не добавлять длинну массива байтов
     *
     * @return array Возвращает массив байтов
     */
    protected function get_image(array $params, $no_length)
    {
        return ConvertToBytes::filter_data($params["image"], 'image', 4, $no_length);
    }

    /**
     * Получаем массив байтов с описанием итема
     *
     * @param array $params Массив данных итема
     * @param bool $no_length Не добавлять длинну массива байтов
     *
     * @return array Возвращает массив байтов
     */
    protected function get_description(array $params, $no_length)
    {
        return ConvertToBytes::filter_data($params["description"], 'string', 4, $no_length);
    }

    /**
     * Получаем массив байтов с дополнителями полями итема
     *
     * @return array Возвращает массив байтов
     */
    protected function after_data()
    {
        return [];
    }
}
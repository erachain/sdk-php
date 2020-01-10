<?php

namespace Erachain\Transaction;

use Erachain\Helpers\ConvertToBytes;
use Erachain\Item\PersonItem;
use StephenHill\Base58;

class PersonInfo extends AbstractTransaction
{
    public function __construct($public_key, $private_key, $erachain_params)
    {
        parent::__construct($public_key, $private_key, $erachain_params);

        $this->set_default_params(array(
            'owner'           => $public_key,
            'name'            => '',
            'description'     => '',
            'icon'            => '',
            'image'           => '',
            'birthday'        => 0,
            'death_day'       => 0,
            'gender'          => 0,
            'race'            => '',
            'birth_latitude'  => 0.0,
            'birth_longitude' => 0.0,
            'skin_color'      => '',
            'eye_color'       => '',
            'hair_color'      => '',
            'height'          => 0
        ));
    }

    /**
     * Получаем сигнатуру и байткод из данных
     *
     * @param array $data_first Первая часть транзакции
     * @param array $data_last Вторая часть транзакции
     *
     * @return array Возвращает сигнатуру и байт код транзакции
     */
    protected function result(array $data_first, array $data_last)
    {
        return ['raw' => $this->get_raw($data_last)];
    }

    /**
     * Формируем первую часть транзакции
     *
     * @return array Возвращает массив с первой частью транзакции
     */
    protected function get_data_first()
    {
        return [];
    }

    /**
     * Формируем вторую часть транзакции
     *
     * @return array Возвращает массив с второй частью транзакции
     */
    protected function get_data_last()
    {
        $base58 = new Base58();

        $params      = $this->get_valid_data($this->get_params());
        $data_person = $this->data_assembly($params);
        $data_sign   = $this->data_assembly($params, true);
        $data_sign   = ConvertToBytes::from_string($base58->decode($this->get_sign($data_sign)));

        return array_merge($data_person, $data_sign);
    }

    /**
     * Формируем данные по информации персоны
     *
     * @param array $params Массив данных по персоне
     *  $params = [
     *      'owner' => (string) публичный ключ владельца персоны
     *      'name' => (string) имя персоны,
     *      'description' => (string) описание персоны,
     *      'icon' => (string) путь к иконке персоны (не обязательно),
     *      'image' => (string) путь к изображению персоны (не обязательно),
     *      'birthday' => (int) день рождения, timestamp с миллисекундами,
     *      'death_day' => (int) день смерти, timestamp с миллисекундами,
     *      'gender' => (int) пол, 0 мужской, 1 женский,
     *      'race' => (string) расса,
     *      'birth_latitude' => (float) широта места рождения,
     *      'birth_longitude' => (float) долгота места рождения,
     *      'skin_color' => (string) цвет кожи,
     *      'eye_color' => (string) цвет глаз,
     *      'hair_color' => (string) цвет волос,
     *      'height' => (int) рост,
     *  ]
     *
     * @param bool $owner_sign Подпись информации персоны
     *
     * @return array Возвращает массив данных для подписи и байткода
     */
    protected function data_assembly(array $params, $owner_sign = false)
    {
        $person_item = new PersonItem($params, $owner_sign);

        return $person_item->get();
    }

}
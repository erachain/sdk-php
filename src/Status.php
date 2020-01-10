<?php

namespace Erachain;

use Erachain\Helpers\Error;
use Erachain\Request\StatusRequest;
use Erachain\Transaction\IssueStatus;
use Erachain\Transaction\SetStatus;
use Exception;
use Sirius\Validation\Validator;

class Status
{
    private $erachain_mode;
    private $validator;

    public function __construct($erachain_mode)
    {
        $this->validator = new Validator;

        $this->erachain_mode = $erachain_mode;
    }

    /**
     * Создание статуса
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для создания статуса
     *  $params = [
     *      'type_item' => (int) тип статуса (не обязательно, 1 - уникальный, 2 - не уникальный),
     *      'owner' => (string) публичный ключ владельца статуса (не обязательно, по умолчанию $public_key),
     *      'name' => (string) название статуса,
     *      'icon' => (string) путь к иконки статуса (не обязательно),
     *      'image' => (string) путь к изображению статуса (не обязательно),
     *      'description' => (string) описание статуса с указанием параметров подстановки: (не обязательно)
     *          %1 - параметр подстановки первого числового значения
     *          %2 - параметр подстановки второго числового значения
     *          %3 - параметр подстановки первого строкового значения
     *          %4 - параметр подстановки второго строкового значения
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/status/issue_status.php
     */
    public function issue($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $status  = new IssueStatus($public_key, $private_key, $this->erachain_mode);
            $request = new StatusRequest($this->erachain_mode);

            $this->validator
                ->add('type_item', 'integer() (' . Error::INTEGER . ')')
                ->add('owner', 'Erachain\Validation\Rule\Base58Rule')
                ->add('name', 'required | Erachain\Validation\Rule\StringRule')
                ->add('icon', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=10K)')
                ->add('image', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=1M)')
                ->add('description', 'required | Erachain\Validation\Rule\StringRule');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $status->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Установка статуса
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для установки статуса
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
     *      'value_1' => (int) первое числовое значение для подстановки (не обязательно),
     *      'value_2' => (int) второе числовое значение для подстановки (не обязательно),
     *      'data_1' => (string) первое строковое значение для подстановки (не обязательно),
     *      'data_2' => (string) второе строковое значение для подстановки (не обязательно),
     *      'ref_to_parent' => (int) путь к изображению статуса (не обязательно),
     *      'description' => (string) описание статуса (не обязательно)
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/status/set_status.php
     */
    public function set($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $status  = new SetStatus($public_key, $private_key, $this->erachain_mode);
            $request = new StatusRequest($this->erachain_mode);

            $this->validator
                ->add('key_status', 'required | integer() (' . Error::INTEGER . ')')
                ->add('item_type', 'required | integer() (' . Error::INTEGER . ')')
                ->add('key_item', 'required | integer() (' . Error::INTEGER . ')')
                ->add('date_start', 'required | integer() (' . Error::INTEGER . ')')
                ->add('date_end', 'required | integer() (' . Error::INTEGER . ')')
                ->add('value_1', 'integer() (' . Error::INTEGER . ')')
                ->add('value_2', 'integer() (' . Error::INTEGER . ')')
                ->add('data_1', 'Erachain\Validation\Rule\StringRule')
                ->add('data_2', 'Erachain\Validation\Rule\StringRule')
                ->add('ref_to_parent', 'integer() (' . Error::INTEGER . ')')
                ->add('description', 'Erachain\Validation\Rule\StringRule');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $status->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }
}
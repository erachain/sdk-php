<?php

namespace Erachain\Transaction;

use Erachain\Crypto\Base;
use Erachain\Helpers\ConvertToBytes;
use Erachain\Helpers\Error;
use Erachain\Request\BaseRequest;
use InvalidArgumentException;
use StephenHill\Base58;

abstract class AbstractTransaction
{
    private $public_key;
    private $private_key;
    private $erachain_mode;
    private $params;
    private $transaction_type;

    public function __construct($public_key, $private_key, $erachain_mode)
    {
        $this->public_key    = $public_key;
        $this->private_key   = $private_key;
        $this->erachain_mode = $erachain_mode;
    }

    /**
     * Добавляем значения по умолчанию
     *
     * @param array $params Массив параметров транзакции
     */
    protected function set_default_params(array $params)
    {
        $this->params = $params;
    }

    /**
     * Получаем массив параметров
     *
     * @return array Возвращает массив параметров
     */
    protected function get_params()
    {
        return $this->params;
    }

    /**
     * Добавляем тип транзакции
     *
     * @param array $transaction_type Массив с типом транзакции
     */
    protected function set_transaction_type(array $transaction_type)
    {
        $this->transaction_type = $transaction_type;
    }

    /**
     * Получаем тип транзакуии
     *
     * @return array Возвращает массив с типом транзакции
     */
    protected function get_transaction_type()
    {
        return $this->transaction_type;
    }

    /**
     * Запускаем формирование транзакции
     *
     * @param array $params Массив данных телеграммы
     *
     * @return array Возвращает байт код и сигнатуру
     */
    public function get(array $params)
    {
        $this->params = array_merge($this->params, $params);

        $this->before_data();

        $data_first = $this->get_data_first();
        $data_last  = $this->get_data_last();

        $this->after_data();

        return $this->result($data_first, $data_last);
    }

    /**
     * Хук для работы с данными перед формированием данных транзакции
     */
    protected function before_data()
    {
        //hook before data
    }


    /**
     * Хук для работы с данными после формирования данных транзакции
     */
    protected function after_data()
    {
        //hook after data
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
        $base58 = new Base58();

        $result = [];

        $result['signature'] = $this->get_sign(array_merge($data_first, $data_last));

        $sign_byte = ConvertToBytes::from_string($base58->decode($result['signature']));

        $result['raw'] = $this->get_raw(array_merge($data_first, $sign_byte, $data_last));

        return $result;
    }

    /**
     * Подписываем транзакцию, получаем сигнатуру
     *
     * @param array $data Массив с данными которые необходимо подписать
     *
     * @return string Возвращает сигнатуру
     */
    protected function get_sign(array $data)
    {
        $base = new Base();

        $port        = ($this->erachain_mode == 'live') ? 9046 : 9066;
        $dataForSign = array_merge($data, ConvertToBytes::from_int32($port));

        return $base->get_sign($dataForSign, $this->private_key);
    }

    /**
     * Получаем байткод
     *
     * @param array $data Массив с данными транзакции
     *
     * @return string Возвращает байт код транзакции
     */
    protected function get_raw(array $data)
    {
        $base58 = new Base58();

        $string = ConvertToBytes::to_string($data);

        return $base58->encode($string);
    }

    /**
     * Формируем первую часть транзакции
     *
     * @return array Возвращает массив данных
     */
    protected function get_data_first()
    {
        $base58 = new Base58();

        $timestamp = round(microtime(true) * 1000);

        //TRANSACTION_TYPE
        $data_first = $this->transaction_type;
        //TIMESTAMP
        $data_first = array_merge($data_first, ConvertToBytes::from_int64($timestamp));
        //REFERENCE
        $data_first = array_merge($data_first, [0, 0, 0, 0, 0, 0, 0, 0]);
        //CREATOR PUBLIC KEY
        $data_first = array_merge($data_first, ConvertToBytes::from_string($base58->decode($this->public_key)));

        //FEE POW
        return array_merge($data_first, [0]);
    }

    /**
     * Формируем вторую часть транзакции
     *
     * @return array
     */
    abstract protected function get_data_last();

    /**
     * Проверяем валидность переданных данных
     *
     * @param array $data Массив данных
     *
     * @return array Массив проверенных данных
     * @throws InvalidArgumentException
     */
    protected function get_valid_data(array $data)
    {
        if ( ! is_array($data) || count($data) < 1) {
            throw new InvalidArgumentException(Error::TRANSACTION_DATA);
        }

        foreach ($data as $key => $value) {
            //валидация и шифрование сообщения
            if ($key === 'message' && ! empty($value) && ! empty($data['recipient'])) {
                if ( ! isset($data['encrypted'])) {
                    $data['encrypted'] = 1;
                }

                if ( ! isset($data['is_text'])) {
                    $data['is_text'] = 1;
                }

                $data[$key] = $this->validate_message($value, $data['encrypted'], $data['recipient']);
            }
        }

        return $data;
    }

    /**
     * Валидация / зашифровка сообщений
     *
     * @param string $message Сообщение
     * @param int $encrypted Нужно зашифровывать или нет (1|0)
     * @param string $recipient Адресс получателя
     *
     * @return string Проверенное / зашифрованное сообщение
     */
    private function validate_message($message, $encrypted, $recipient)
    {
        if ($encrypted) {
            $base   = new Base();
            $base58 = new Base58();
            $r_base = new BaseRequest($this->erachain_mode);

            $recipient_pk = $r_base->get_public_key_by_address($recipient);

            $message = $base->data_encrypt($message, $recipient_pk['DATA'], $this->private_key);
            $message = $base58->decode($message);
        }

        return $message;
    }

    /**
     * Смещение в типе транзакции для учёта кол-ва знаков после точки в amount
     *
     * @param array $amount Массив с значением amount, разбитым на части до запятой и после
     * @param int $type_index Индекс в типе транзакции, значение которого нужно сдвинуть
     * @param bool $shift_message нужно ли сдвигать для сообщения
     */
    protected function amount_scale(array $amount, $type_index, $shift_message = false)
    {
        $scale = 0;

        if ( ! empty($amount[1]) && strlen($amount[1]) !== 8) {
            $scale = strlen($amount[1]) - 8;
        }

        if ($scale !== 0 || $shift_message) {
            $type = $this->get_transaction_type();

            if ($scale < 8 && $scale > 0) {
                $type[$type_index] = $scale + 32;
            } else {
                $type[$type_index] = $scale;
            }

            if ($shift_message) {
                $type[$type_index] = 128 | $type[$type_index];
            }

            $this->set_transaction_type($type);
        }
    }
}
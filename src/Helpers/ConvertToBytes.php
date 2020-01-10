<?php

namespace Erachain\Helpers;

use Brick\Math\BigDecimal;

class ConvertToBytes
{
    /**
     * Возвращаем 8 байт
     *
     * @param int $int64
     *
     * @return array
     */
    public static function from_int64($int64)
    {
        return array_reverse(unpack("C*", pack("Q", $int64)));
    }

    /**
     * Возвращаем 4 байта
     *
     * @param int $int32
     *
     * @return array
     */
    public static function from_int32($int32)
    {
        return array_reverse(unpack("C*", pack("L", $int32)));
    }

    /**
     * Возвращаем 2 байта
     *
     * @param int $int16
     *
     * @return array
     */
    public static function from_int16($int16)
    {
        return array_reverse(unpack("C*", pack("S", $int16)));
    }

    /**
     * Возвращаем байт из float
     *
     * @param float $float
     *
     * @return array
     */
    public static function from_float($float)
    {
        return array_reverse(unpack("c*", pack("f", $float)));
    }

    /**
     * Возвращаем байт из float
     *
     * @param string $big_decimal
     *
     * @return array
     */
    public static function from_big_decimal($big_decimal)
    {
        $big_decimal = BigDecimal::of($big_decimal)->unscaledValue();

        return array_reverse(unpack("C*", pack("Q", $big_decimal)));
    }

    /**
     * Строка в байт
     *
     * @param string $str
     *
     * @return array
     */
    public static function from_string($str)
    {
        return array_slice(unpack("C*", "\0" . $str), 1);
    }

    /**
     * Изображение в байт
     *
     * @param string $image
     *
     * @return array
     */
    public static function from_image($image)
    {
        $array = [];

        if ($image === '') {
            return $array;
        }

        foreach (str_split($image) as $byte) {
            array_push($array, ord($byte));
        }

        return $array;
    }

    /**
     * Байт в строку
     *
     * @param array $s
     *
     * @return string
     */
    public static function to_string(array $s)
    {
        return call_user_func_array('pack', array_merge(["C*"], $s));
    }

    /**
     * Формируем массив байтов из данных по типу и длинне байта
     *
     * @param mixed $data Данные которые необходимо перевести в массив байтов
     * @param string $type Тип данных
     * @param int $length_byte Длинна байта
     * @param bool $hide_length Скрывать длинну
     *
     * @return array Возвращает массив байтов
     */
    public static function filter_data($data, $type, $length_byte, $hide_length = false)
    {
        $result = [];

        switch ($type):
            case 'string':
                $data = ConvertToBytes::from_string($data);
                break;
            case 'image':
                if ( ! empty($data)) {
                    $data = file_get_contents($data);
                }
                $data = ConvertToBytes::from_image($data);
                break;
            default:
        endswitch;

        if ( ! $hide_length) {
            switch ($length_byte):
                case 1:
                    $result = array_merge($result, [count($data)]);
                    break;
                case 2:
                    $result = array_merge($result, ConvertToBytes::from_int16(count($data)));
                    break;
                case 4:
                    $result = array_merge($result, ConvertToBytes::from_int32(count($data)));
                    break;
                case 8:
                    $result = array_merge($result, ConvertToBytes::from_int64(count($data)));
                    break;
                default:
            endswitch;
        }

        if (count($data) > 0) {
            $result = array_merge($result, $data);
        }

        return $result;
    }
}

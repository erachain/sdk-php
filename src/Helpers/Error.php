<?php

namespace Erachain\Helpers;

use UnexpectedValueException;

class Error
{
    const STRING = 'переменная не является строкой';
    const INTEGER = 'переменная не является целым числом';
    const FLOAT = 'переменная не является числом с плавающей точкой';
    const AMOUNT = 'переменная не верного фармата. Формат: \'1.00432\', \'21\'';
    const POSITIVE_NUMBER = 'переменная не является положительным числом';
    const INVALID_REQUEST = 'неверный запрос к ноде';
    const HEIGHT_SEQ = 'неверный неверный формат (пример: \'664236-1\')';
    const FULLNAME = 'имя введено не верно';
    const ARRAY_GET = 'параметр [\'get\'] не является массивом';
    const SERVER_LIST = 'список серверов пуст';
    const MODE_ERACHAIN = 'выбран не верный режим работы erachain';
    const TRANSACTION_DATA = 'не заданы параметры транзакции';
    const SEND_REQUEST = 'возникла ошибка при отправке запроса в ноду';
    const NO_PARAMS = 'возникла ошибка при отправке запроса в ноду';
    const ARRAY_NO_ITEMS = 'массив не имеет ни одного элемента';

    /**
     * Преобразуем ошибки валидатора в исключения
     *
     * @param array $error_messages Массив с ошибками из валидатора
     */
    public static function validate($error_messages)
    {
        $error = "Возникли следующие ошибки:";

        foreach ($error_messages as $attr => $messages) {
            foreach ($messages as $message) {
                $error .= "\n - [$$attr] " . $message->getTemplate();
            }
        }

        throw new UnexpectedValueException($error);
    }
}
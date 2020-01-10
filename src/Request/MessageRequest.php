<?php

namespace Erachain\Request;

class MessageRequest extends TransactionRequest
{
    /**
     * Поиск сообщений по адресу
     *
     * @param array $param Параметры для поиска
     *  $param = [
     *      'address' => (string) адрес,
     *      'unconfirmed' => (bool) неподтвержденные транзакции // TODO error: при true выдаёт 500 error
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с найденными транзакциями
     */
    public function getbyaddress(array $param = [])
    {
        $param['recordType'] = 'Letter';

        return $this->request->send(
            '/apirecords/getbyaddress',
            $param,
            'get'
        );
    }
}

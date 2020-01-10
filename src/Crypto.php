<?php

namespace Erachain;

use Erachain\Crypto\Base;
use Erachain\Helpers\Error;
use Exception;
use Sirius\Validation\Validator;

class Crypto
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator;
    }

    /**
     * Генерация сида
     *
     * @return array Возвращает массив с сидами [массив байт, base58] | Exception
     * @example /example/crypto/generate_seed.php
     */
    public function generate_seed()
    {
        try {
            $base = new Base();

            return array(
                'DATA'   => $base->generate_seed(),
                'STATUS' => 'OK'
            );
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Генерация аккаунта
     *
     * @param string $seed_base58 Сид в base58 (не обязателен при генерации нового аккаунта и сида)
     *
     * @param int $number_account Порядковый номер аккаунта с данным сидом (не обязателен для первого аккаунта)
     *
     * @return array Возвращает массив с данными аккаунта | Exception
     * @example /example/crypto/generate_account.php
     */
    public function generate_account($seed_base58 = null, $number_account = null)
    {
        try {
            $base = new Base();

            $this->validator
                ->add('seed_base58', 'Erachain\Validation\Rule\Base58Rule')
                ->add('number_account', 'integer() (' . Error::INTEGER . ')');

            if ( ! $this->validator->validate(compact('seed_base58', 'number_account'))) {
                Error::validate($this->validator->getMessages());
            }

            return array(
                'DATA'   => $base->generate_account($seed_base58, $number_account),
                'STATUS' => 'OK'
            );
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Шифрование данных
     *
     * @param string $message Сообщение которое нужно зашифровать
     * @param string $public_key Публичный ключ получателя
     * @param string $private_key Приватный ключ отправителя
     *
     * @return array Возвращает зашифрованное сообщение в base58 | Exception
     * @example /example/crypto/encrypt.php
     */
    public function encrypt($message = null, $public_key = null, $private_key = null)
    {
        try {
            $base = new Base();

            $this->validator
                ->add('message', 'required | Erachain\Validation\Rule\StringRule')
                ->add('public_key', 'required | Erachain\Validation\Rule\Base58Rule')
                ->add('private_key', 'required | Erachain\Validation\Rule\Base58Rule');

            if ( ! $this->validator->validate(compact('message', 'public_key', 'private_key'))) {
                Error::validate($this->validator->getMessages());
            }

            return array(
                'DATA'   => $base->data_encrypt($message, $public_key, $private_key),
                'STATUS' => 'OK'
            );
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Расшифровка данных
     *
     * @param string $message Сообщение которое нужно расшифровать
     * @param string $public_key Публичный ключ отправителя
     * @param string $private_key Приватный ключ получателя
     *
     * @return array Возвращает расшифрованное | Exception
     * @example /example/crypto/decrypt.php
     */
    public function decrypt($message = null, $public_key = null, $private_key = null)
    {
        try {
            $base = new Base();

            $this->validator
                ->add('message', 'required | Erachain\Validation\Rule\StringRule')
                ->add('public_key', 'required | Erachain\Validation\Rule\Base58Rule')
                ->add('private_key', 'required | Erachain\Validation\Rule\Base58Rule');

            if ( ! $this->validator->validate(compact('message', 'public_key', 'private_key'))) {
                Error::validate($this->validator->getMessages());
            }

            return array(
                'DATA'   => $base->data_decrypt($message, $public_key, $private_key),
                'STATUS' => 'OK'
            );
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }
}
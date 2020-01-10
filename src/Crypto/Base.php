<?php

namespace Erachain\Crypto;

use Erachain\Helpers\ConvertToBytes;
use Erachain\Helpers\Ripemd160;
use ParagonIE\Sodium\Core\Ed25519;
use ParagonIE\Sodium\Crypto;
use ParagonIE\Sodium\Compat;
use StephenHill\Base58;
use StephenHill\GMPService;

class Base
{
    const SEED_BYTES = 32;
    const METHOD_CRYPT = 'aes-256-cbc';

    private $iv;

    public function __construct()
    {
        $this->iv = chr(0x06) . chr(0x04) . chr(0x03) . chr(0x08) . chr(0x01) . chr(0x02) . chr(0x01) . chr(0x02)
                    . chr(0x07) . chr(0x02) . chr(0x03) . chr(0x08) . chr(0x05) . chr(0x07) . chr(0x01) . chr(0x01);
    }

    /**
     * Генерируем сид
     *
     * @return array Возвращает массив с сидами (массив / base58)
     */
    public function generate_seed()
    {
        $seed   = random_bytes(self::SEED_BYTES);
        $base58 = new Base58();

        return array(
            'seed'        => $seed,
            'seed_base58' => $base58->encode($seed),
        );
    }

    /**
     * Создаём приватный и публичный ключ
     *
     * @param string $seed_base58 Сид в base58
     *
     * @return array Возвращает seed, private_key, public_key
     */
    private function create_key_pair($seed_base58 = null)
    {
        if ( ! $seed_base58) {
            $seed = $this->generate_seed();
        } else {
            $base58 = new Base58();
            $seed   = array(
                'seed'        => $base58->decode($seed_base58),
                'seed_base58' => $seed_base58,
            );
        }
        $pk = '';
        $sk = '';
        Ed25519::seed_keypair($pk, $sk, $seed['seed']);

        return array(
            'seed'        => $seed['seed_base58'],
            'private_key' => ConvertToBytes::from_string($sk),
            'public_key'  => ConvertToBytes::from_string($pk)
        );
    }

    /**
     * Получаем массив с данными счёта, сидов, ключей
     *
     * @param string $seed Сид в base58 (не обязателен)
     * @param int $number_account Порядковый номер аккаунта с данным сидом (не обязателен)
     *
     * @return array|bool Возвращает массив данных по аккаунту (seed, accountSeed, private_key, public_key, address)
     */
    public function generate_account($seed = null, $number_account = null)
    {
        $base58 = new Base58();

        if ( ! $seed) {
            $arSeed = $this->generate_seed();
            $seed   = $arSeed['seed_base58'];
        }
        if ($number_account === false) {
            $number_account = 0;
        }
        $accountSeed = $this->get_account_seed($seed, $number_account);
        $keyPair     = $this->create_key_pair($accountSeed);

        return array(
            'seed'        => $seed,
            'accountSeed' => $accountSeed,
            'private_key' => $base58->encode(ConvertToBytes::to_string($keyPair['private_key'])),
            'public_key'  => $base58->encode(ConvertToBytes::to_string($keyPair['public_key'])),
            'address'     => $this->get_address($keyPair['public_key'])
        );
    }

    /**
     * Шифрование данных
     *
     * @param string $message Сообщение которое нужно зашифровать
     * @param string $public_key Публичный ключ получателя
     * @param string $private_key Приватный ключ отправителя
     *
     * @return string Возвращает зашифрованное сообщение в base58
     */
    public function data_encrypt($message, $public_key, $private_key)
    {
        $base58 = new Base58();

        $password      = $this->get_password($public_key, $private_key);
        $encrypted     = openssl_encrypt($message, self::METHOD_CRYPT, $password, OPENSSL_RAW_DATA, $this->iv);
        $era_encrypted = chr(0x01) . $encrypted;

        return $base58->encode($era_encrypted);
    }

    /**
     * Расшифровка данных
     *
     * @param string $message Сообщение которое нужно расшифровать
     * @param string $public_key Публичный ключ отправителя
     * @param string $private_key Приватный ключ получателя
     *
     * @return string Возвращает расшифрованное
     */
    public function data_decrypt($message, $public_key, $private_key)
    {
        $base58 = new Base58();

        $password      = $this->get_password($public_key, $private_key);
        $era_encrypted = $base58->decode($message);
        $encrypted     = substr($era_encrypted, 1, strlen($era_encrypted) - 1);

        return openssl_decrypt($encrypted, self::METHOD_CRYPT, $password, OPENSSL_RAW_DATA, $this->iv);
    }

    /**
     * Подписываем данные
     *
     * @param array $data Массив данных
     * @param string $private_key Приватный ключ
     *
     * @return string Получаем подпись (signature) в base58
     */
    public function get_sign(array $data, $private_key)
    {
        $base58 = new Base58();

        $data        = ConvertToBytes::to_string($data);
        $private_key = $base58->decode($private_key);
        $sign        = Ed25519::sign_detached($data, $private_key);

        return $base58->encode($sign);
    }

    /**
     * Получение пароля из приватного и публичного ключей
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     *
     * @return string Получаем пароль
     */
    private function get_password($public_key, $private_key)
    {
        $base58 = new Base58();

        $public_key             = $base58->decode($public_key);
        $private_key            = $base58->decode($private_key);
        $public_key_curve25519  = Ed25519::pk_to_curve25519($public_key);
        $private_key_curve25519 = Compat::crypto_sign_ed25519_sk_to_curve25519($private_key);
        $ss                     = Crypto::scalarmult($private_key_curve25519, $public_key_curve25519);

        return substr(hash('sha256', $ss, true), 0, 32);
    }

    /**
     * Получаем сид аккаунта
     *
     * @param string $seed_base58 Сид в base58
     * @param int $number_account Порядковый номер аккаунта с данным сидом
     *
     * @return string Возвращает сид аккаунта в base58
     */
    private function get_account_seed($seed_base58, $number_account)
    {
        $base58 = new Base58();

        $result        = [];
        $n_a_byte      = ConvertToBytes::from_int32($number_account);
        $result        = array_merge($result, $n_a_byte);
        $seed_byte     = ConvertToBytes::from_string($base58->decode($seed_base58));
        $result        = array_merge($result, $seed_byte);
        $result        = array_merge($result, $n_a_byte);
        $hash_res      = hash('sha256', ConvertToBytes::to_string($result), true);
        $hash_hash_res = hash('sha256', $hash_res, true);

        return $base58->encode($hash_hash_res);
    }

    /**
     * Получение номера счёта из публичного ключа
     *
     * @param array $public_key Публичный ключ (массив байт)
     *
     * @return string Получаем адрес в base58
     */
    private function get_address(array $public_key)
    {
        $gmp       = new GMPService();
        $base58    = new Base58(null, $gmp);
        $ripemd160 = new Ripemd160();

        $address_version = 15;
        $string          = implode(array_map("chr", $public_key));
        $hash_public_key = hash("sha256", $string, true);
        $public_key_1    = unpack('C*', $hash_public_key);
        $public_key_2    = array_slice($public_key_1, 0, 32);
        $r160_public_key = $ripemd160->digest($public_key_2);

        array_unshift($r160_public_key, $address_version);

        $string     = implode(array_map("chr", $r160_public_key));
        $hash_r160  = hash("sha256", $string, true);
        $hash_r160  = hash("sha256", $hash_r160, true);
        $first4byte = substr($hash_r160, 0, 4);
        $e1         = $string . $first4byte;

        return $base58->encode($e1);
    }

}
# Erachain PHP SDK
Библиотека предназначена для работы с блокчейн сетью Erachain
. Библиотека имеет функционал по генерации аккаунта (сид, ключи, адрес),
 созданию персон, созданию и отправке активов, отправке сообщений, работе с транзакциями и тд.
 
 Библиотека работает только с 64-битными версиями PHP, от версии PHP 5.6 и выше.
## Устанока Erachain PHP SDK
Установка производится с помощью Composer:
```bash
composer require erachain/erachain
```
## Использование библиотеки
Для использования библиотеки необходимо подключить автозагрузку composer и объявить объект Erachain
```php
require_once 'vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain('dev');
```
### Доступные методы Erachain
Криптография:
+ `crypto->generate_seed()` - Генерация сида
+ `crypto->generate_account()` - Генерация аккаунта (сид, ключи, адрес)
+ `crypto->encrypt()` - Зашифровка сообщения
+ `crypto->decrypt()` - Расшифровка сообщения

Работа с персонами:
+ `person->info()` - Создание байт-кода персоны
+ `person->issue()` - Регистрация персоны в сети Erachain
+ `person->certify()` - Подтверждение персоны
+ `person->api()` - Работа с API запросами к ноде по персоне

Работа с активами:
+ `asset->issue()` - Создание актива
+ `asset->send()` - Отправка актива
+ `asset->api()` - Работа с API запросами к ноде по активам

Работа с сообщениями/телеграмами:
+ `message->send()` - Отправка сообщения
+ `message->api()` - Работа с API запросами к ноде по сообщениям
+ `telegram->send()` - Отправка телеграмы
+ `telegram->api()` - Работа с API запросами к ноде по телеграмам

Работа с биржей:
+ `order->create()` - Создание ордера
+ `order->cancel()` - Отмена ордера
+ `order->api()` - Работа с API запросами к ноде по ордерам

Работа с статусами:
+ `status->issue()` - Создание статуса
+ `status->set()` - Установка статуса сущности (персона, актив и тд)

Работа с голосованием:
+ `poll->issue()` - Создание голосования
+ `poll->vote()` - Проголосовать

Работа с подтверждением транзакций:
+ `vouch->sign()` - Подписать транзакцию

Работа с транзакциями:
+ `transaction->api()` - Работа с API запросами к ноде по транзакциям

Выполнение любого запроса к ноде:
+ `api()` - список доступных запросов в документации: <https://app.swaggerhub.com/apis-docs/Erachain/era-api
/1.0.0-oas3>

## Примеры использования
### 1. Криптография
#### 1.1. Генерация сида
```php
$seed = $era->crypto->generate_seed();
```
Возвращает сид в виде байт кода и в base58.
#### 1.2. Генерация аккаунта
```php
$seed_base58 = 'BXe6d6TqrfoBFGW1TxJcJZPzcm3zpgLLht75MLSJa1aM';
$number_account = 0;

$account = $era->crypto->generate_account($seed_base58, $number_account);
```
+ `$seed_base58` - параметр не обязателен. При отсутствии параметра или при указании `false` генерируется новый сид.
+ `$number_account` - параметр не обязателен. При осутствии параметра, создаётся аккаунт с порядковым номером `0
`. Если вам необходимо создать дополнительный аккаунт для конкретного сида, в параметрах укажите ваш `$seed_base58
`, а в параметре `$number_account` укажите `1` для второго аккаунта, `2` для третьего и тд.
#### 1.3. Шифрование сообщения
```php
$message = 'Это сообщение нужно зашифровать';
$public_key = '4MCNiC7ziMvufkMs2rihvrQCQepXkpTW3Jku6hR5bDBn';
$private_key = '5wndPtWGG1EyEWxcv5eyqnamo5VyPCGqpn7T8TwyrQTB9oLT8de331mtDiMHztdxwDVQo2JFoAzHyyf922RmJxnC';

$encrypt_message = $era->crypto->encrypt($message, $public_key, $private_key);
```
+ `$message` - обязательный параметр. Сообщение которое нужно зашифровать.
+ `$public_key` - обязательный параметр. Публичный ключ получателя сообщения.
+ `$private_key` - обязательный параметр. Приватный ключ отправителя сообщения.
#### 1.4. Расшифровка сообщения
```php
$message = '7wrhFVTVDESUpaTrZ3X2n3bp3g9HLTfLyirnfwwYDdsa6yD533AtS3UbETzEUgKqbd8kAZNvisX1rMBeNJSCaxxW';
$public_key = 'Cek7WfsAWgFfGsVVwNp6CFC3EXNM5YkFJRpRvnhqhvS4';
$private_key = '2BjWXep7dWrw9vwV5jG4idVAQrUmm6mvdFVsfrNHG3rMQRUT4EgwaCt7s9HdkPabBsFypkTaT1qJxmREgV4e3oWg';

$decrypt_message = $era->crypto->decrypt($message, $public_key, $private_key);
```
+ `$message` - обязательный параметр. Сообщение которое нужно расшифровать.
+ `$public_key` - обязательный параметр. Публичный ключ отправителя сообщения.
+ `$private_key` - обязательный параметр. Приватный ключ получателя сообщения.
### 2. Работа с персонами
#### 2.1. Создание байт-кода персоны
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'owner'           => $public_key, // (string)
    'name'            => 'Тестовая персона', // (string)
    'description'     => 'Создание тестовой персоны PHP SDK', // (string)
    'icon'            => dirname(__FILE__) . '/person-icon.jpg', // (string)
    'image'           => dirname(__FILE__) . '/person-image.jpg', // (string)
    'birthday'        => 744292334000, // (int)
    'death_day'       => 0, // (int)
    'gender'          => 0, // (int)
    'race'            => 'Европеец', // (string)
    'birth_latitude'  => 56.680146225285846, // (float)
    'birth_longitude' => 59.420222824939685, // (float)
    'skin_color'      => 'Белый', // (string)
    'eye_color'       => 'Серые', // (string)
    'hair_color'      => 'Черные', // (string)
    'height'          => 175 // (int)
);

$new_person = $era->person->info($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ создателя персоны.
+ `$private_key` - обязательный параметр. Приватный ключ создателя персоны.
+ `$params` - обязательный параметр. Параметры для создания персоны.
    + `owner` - не обязательный параметр. Публичный ключ владельца персоны (по умолчанию $public_key)
    + `name` - обязательный параметр. Имя персоны
    + `description` - обязательный параметр. Описание персоны
    + `icon` - не обязательный параметр. Путь к иконке персоны
    + `image` - не обязательный параметр. Путь к фото персоны (JPG, максимум 20КБ)
    + `birthday` - обязательный параметр. Дата рождения (timestamp с миллисекундами)
    + `death_day` - обязательный параметр. Дата смерти (timestamp с миллисекундами)
    + `gender` - обязательный параметр. Пол персоны (0 - мужской, 1 - женский)
    + `race` - обязательный параметр. Раса персоны
    + `birth_latitude` - обязательный параметр. Широта места рождения
    + `birth_longitude` - обязательный параметр. Долгота места рождения
    + `skin_color` - обязательный параметр. Цвет кожи персоны
    + `eye_color` - обязательный параметр. Цвет глаз персоны
    + `hair_color` - обязательный параметр. Цвет волос персоны
    + `height` - обязательный параметр. Рост персоны
#### 2.2. Регистрация персоны в сети Erachain
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'raw' => '4nk6DzGpdPpsRnejoKUruA4tM6b52KNkHZXsHPwcY1QtcNRyACfwFxKejD4acmGRA69GyP6yXwES912p53yXdXNtsJ2F2kpnGaJDmbfBVrK5V5oHAtLWcoMhwj1xSMgnwAWf38mkDYYqzAm2kj1gkwfBAauPzeADpgRhdncksyxkHf92X7Bo3qcY13rd4YjMEQH2W9J8bMimSWGdtzeLtNk1z2S5tGb5GG91zxBtUWA76N9Qg1kAdFvVcXJsAtC4mxQPgTv4gzrKUHnZMKKyc67gF3NWSGk9h3b3k77UCskmgPmMU5NA2PvTh2CzLHxY8UAyGScUuXuQfmmRpZMXAFmAoKi47x975vdsBzLvQCHw8fPMKfLmzxbAfjD9wZy6Pg5sfxJ5FhUN1hfzxwyUE9AanopcqRQKWVbQzUVMmWikyJsM436SbEeMSz8TAoQ4NcKdV4q8TEWHv6SnDw8g9xgn2wnvab4HBVmid51C4udcDk3Rejqc8M9dANYuSxR6t9GNLosMrxPZ9WQY9fieNRZhvCgCuSArtEAocTwRb9tKzroSSQQS7bA5gnw1XbYtgjfTvW4hFX5JDCef9bmUhahsLj1P7Pto8TzuVuHsVzCgvFGvKjuPLmeYPZKdGcijDSf7YLy5CJPSjpF9iZJRsFiMFVcamd2kc9iyJ2de7pNTGtxxYiHYZgwHq8eyRKuR7fSQn7HvF4nx5yRyJUqTAD7QRht25s3FHaff6M5hVviLiGNa3kaN9R99z5AQ2edc7ZwpsNeExzTUjRHhfxocbJQHV2DgNVXm5KxJwot9CKUz4RMLw5dM99kzpiRPMGjeTbG2PZyq95y97suQoL9KKhgJ7XhUpY9wkPb3ti42MTPrJaErbsxm83aiRnNn5Hv8fK6GdayPyrQUGW46btia1ZQt4GCQLkKSvdgTmSjKGaDpATnFWnCethvQFFs8uLCqFtRVFJE5ftm1UU3w83SFijzkspfSpCxG61TfyqRafEUfY3unQRsPuPaEwdhZGzbd7SmSzPxWvgF2XSgN8jqNLHwnCEcuUnqpboauYVLMFDXpSMMWzstvXhx1gYmBmPqC7LBkoQV6B39JvFyRXmUynFoXKxBWgLt19ehNCmSmyBcqBABosnmZtmPSZp8QYXMFAd1q9RpXxuqeMPXwk3hW7dNG1M9kiDmh4Lh7rf2pjiQpBxKJ58mV3iAKKdsQVQ6XBiiH2mC6Vst1zf3a4zEH97oc9ZemAsGs3Pf9UTkFSbHHwG7fYb4DVdskK8WdE7kSadMbzxw4p4jXirM2isZYWfnyExnYKPdA9b6vKngA2AZfP5gaHpBg58domvPLudELDyuCo9Tk2YoDKN1HNk8yZo9GgGpat8azcMcP5zJk817vDVFcE3GqjqJuErtEmwXBiChwaCc37s6F8M3XGPmX2QhVaa1j3fk3jhEnu9jWZkpimzGVkTeeAhaXnjFCTU25Ru9W6yZyWFxcBTWxWjzAadbgGdEZPMBQZhEU3gNz6j9UrKQS7nQ3prAjxJYv5u46Q1cdt8tjfEToWUDDX5BjWFCjzP66Lu2KTWXp28YzRrV3X6jJX8p8v7usW3QN7RZaXqUepUSvUDUboRqhRScp6XzzdprrrhoEsV2hjjoLEJ5b1KXEW2KdfvrwznM3uV6qTpjS8XsKgYXBEkKfqEPbnX3mVhA2sJceUvzQoMNJugHtcyT2jvX6T45w5GATwoqQqFct2jmNDx1FbyiC6RpJThipAy9Vy2k1j6uab2EgRqs7pTuPPMP5Ae9fuqoYLZiXSDC5LwL4vRbprSNGmTNJUbQxPiuES2KDCTDyyxUYPDavAFfKVxJehdQiQMNkg6P6kSK5ubtqzicMHiPuXweLgTB55HgYkTKG3kLw4SP8L8Y8HSCWs2cwJNBnGCmb1z46etovNPcdc1BjPKdZaypdC6HZRXPbQaRmhQYVm53S2rC5dQFUmEApSRrK6xGrQWwcLRwrRUnW3aJ5jUS4rDkdpLWAkvy5nRe5kZNtxSQaukC4JiYCbZ7MxyU8T6ufthtR9VDasC9Ub5nzJ2iSLVnnyXS45dTutFNo1e6SBRq6AnsTQHQvxLp5Bia1kseAd5ECjW2WgqvYrYPw2msCHbFDgDU5v9BWVnASMVa9fT1sHXKNsmcCtc2pMVccDHAoHwqDnzuAkoeskTuF2xcD6czhh39SYzryAtrt3oTsH5j7VdAtK3CXNJsA5aZvmdhriM2GQUE8gS7J9tQWpYYsTboDBxtbRH31VD5xsi8fawiuTsrDJQvmTXmpZnSkVLfSafJoiLMRynKAHZpZNwmCuMvUzMXS68F4ti9kn8fR5GZf5RkeQZNkXXfJwTxsWPkNnPYZY65yHZPDNufpxgAtzMCAs7zr5GaTfDDqU5MzZr2zMvpaEMTJWWi1r1jQCoBBx3BJwoP7g9eY2q2bBerCpWYxo9x8JjfoA4i2JxZayNcidL5KxHWNwPupPSTRr7V2xS3JQ8Ni4pJmSwT4QZ8PeSQo5wULccSdbxoEWo7A4jXfgHdzXLbFADX62jfpVngFJErxJ4conTgtgH9iDzYXM1SM6ckhcd4nmMTcriu4ogGabnbL7Ch8agwqEZChc48eMpmNWdDMVkFc3pHuFaw3SBMFgwssSvtbH3pJZC9Et8BLLTrFikXzEvdCXC9uXeWahDC3nQUaNZYRLiygHV2VfP9jYpgSeW46Kk3fCg1hWorB6gFDSLTsP8f321rSgu5bqEHK6Kaa5eZdjjzxGpodsHAsYwvZbbYxcFqXPmYvMg9gHCD2jwrmuH4Ab1e3ijB1rPQ1haHZwzjumCeNqr4jQTpU3pGsbrTQTFAaDeAKNNZSTVEkTn9kgkddVRPoZNP65h1GMWtYddVXzoNS6quWjEPk4dMupVwwHZcoWi3HJYp7RKTbdNNWVJEaxjCnNiUhMr7iRCWq324cyXswWUdKtMEyEEGpycugzDwyNkmdibA9jJVARt1X9mpWU7YEhn7WCX6UsjSfKKib1jy3qeAyXLXnZcBajNp6FfPuWgXJ8tkv3PNck39T7yhJoWaBfBzxRLSUjzwMYaQGHT3tgEEUth2a2qcG9mEYBns7SaL6vTTUqgn8fgt93Hb3k9vwKTm85hAq1zrEERY8zCcfwaRaPgsXCCbkyKq6KTQzqvXouioAySnp7yjzG4wPmUazGPDSNUqqQMBDSTbYzG9saCMpVsfzatVnUXL7XayL4Z9hhnkr2wZNvuZ2hQa9E3Syd1pQZuZiHhXx2AgVyfgUsL4iYhA1TMtjGdvfJa4x8NMRKqJa6XA4FPL4U9cA7o7ajygGF94gAFiQTt9XmBazofvick1ZL9AU93nfE3T3f2g8P13BPJhCe6UyveTTYuzUDnKZggvUj7mM36UTkkKvgYuQdkQMvNtubsyL8XVLZVp7ZcLXb8fVVnPurA9sLS4entoLSVDAdQRW4w3YxTrbKpmWzSK38NrqMwPcjcMQYww7CscvZnrwqUUtGRh1NrC2woTFqzW396JGTZirEr2oYrcaKMzpk1sUjYbfwa7dTm6BqPLU5KzMstC2E9gv1nVadcgjuNcEZJEshH1y5Qp67Wdd19bVQAF8eAUHdbmmfoX8fTKwJv8mMGmGsKoXiuVVmLdhqCvzp2QpamF2d69s1eWdpYz4q24cq73wgEWEPKxivJcRkN99sVnEnHXNB5sdrvUtoXDK5VhMNzh2EzpCabQM9915Xz2zbVZaVcnoq6XxZE7WJJYPbyycTjP1ai6nhB7RWCNfp4EF2W8yxDps8Mnomz2ccNXJ4PXhJkKnD1KFDAUrtqKDn5whtBpNcJGPFaXVkJ6PkJaRCUjx6cgwGEGRcqxbYgNqJiY9nwi5iKdXigksHaYh3TEfQtBZvqj13EKr6ZNdfgmqKB14E8DtdhyLNRrY7LkKHxc7SECnyDcAiw8ZSoU6dbMCp84dxYNPGG5QoziRnv81wYpp6RtPCaFS2xkHYq7LKguuwZLNfrrEbaKojceGBmFVvFEc26jnbbHtpwLqwmT49zFvzDfPYXFA189rwxg7pLv2nhmtynfQ65zALDbFF2wx3iPM1zkxmqqh6315KK9nAQDQH6MhGV7hGpACiJkUrnRjNwmwpBbusJRo3ZHyBw7PkSuLNizz8MQJdRx4jvoDZtDShjjzXxkdXhUBegKueDxah4Gx8Vdp7mh6DqaA6MMvKb5ebA9VVmfctGn21pKzoMXRVR3KS6ZoWrZf9WKhB5F9H2zjvWhgdsyMfs1JPuV6G5S5WwbAnoArHqZRjRzoSBTkPx5mx2YgNCfE1HwThwc7QBKF1ASKkF5ZinfZsKLbyLYoEVkj57gA32qYgbyEkMe91YKrD8ZHsxPMGQ3bX7UdCJqPy5wdCcffiAHBXnpz4u15q5i4UwZXBgVydXrC47i6toh9wMFkp7mqsa7D46HfSjofFjXX5uaitA7bgMSnQQpqhyv5J7Eygp3ubUKbVFvYEAXnXdJUweWM8EMcU9bwyudi5YYqU8UQmed3c7cPzRGLGaZnhmx7HcNGmEj22KF9Uh5rsK3WkErq2GZP951SY7oHtPERDsPycSyyRMyq1nmyVXcqYSDFxvZsSizChCPFRifTo9LDm3nGnHp52CEh6Tsd8paQqWdLN8EimeGyqgGnGSrJETFp9HJM7x2pUCHdSWEp7pg2CARGx72SWyCFDpAR2oGsWHLrPcStgts8oZKMtFg3C1xY6wa2v4M6JbaWpARTm5Z191EcxfK5NNPz27M7LivWzQiCTJA4ubf1tVahTixs9kLq5m4j81PTgAJLJr5iiWh5xFeEfuzeSfEtfZC3SEcYL5XuqKZqs6KekNMFWxyiZFS4KzvCz6YvSiCWdHHiuZGxW8f5DXP6Q17RcAbKxELzjpSy3a1G3XQf3DrE7gQzJPDBBNCSvphBftB89Dte6E6Y3gosjfAtQAo2wyrRYeGW4DseUXxj3oaAgavbrfPNGk97CwvvACiuPbugv2BkYkbjZhnfsrvZbsPFpzb6y5mTHH3x7DPXxkVYsrLugnZ9cLbBRN2eEax4DrGUVou5TZZ7beAPKtxJgj5doKZVWdcLrbNRjhj9JnNx8bbjRwFafbL6yfxZwUQZ2vqTkZ2AJmgJy6gUZjrxQrwr72fp1T8ey1bC9ykaN3uNWkdkxJsYnfpfmoC8vrZH2mk6G6eip5BgJeFR1tuNYAaho5NU2Q1v1SretmUPVVDRokWEH8e3ec92zf5FR3H2dfYqpMRnEhmwLN6DpVzaiYtLZtVP5x3TKZGiS8oUM8CiSJtGsMbVbMuh72tikqdag5vPTs1ZHWiV4A4kVf48PWrbTBn2eEKkv55b7DsJ7uBKQS1n8YsLZ9q9BJVpP8uoBLV2T5dkAVT8ax5xi9VNmodjx6DWPaQobjRUH58Qvu7KbqrNEk5b9VbLjNg4iHrPwP4sgNhqFXcEDYdM8L8c1ZF8QoU1yytBezJNZdXGyjDm6vBSB2J6wtDAbuc5k9Jep9weL9yLgdpG22v3NZ3Tf6YJGovM7aAmTnGbMbZ6hEpMiGgHEsNhXNBTibMRhBeXHVyVCCp4RcLnozvrWQhRgGoCM6tFUQqtk7Qn2P4vpbDDvjfwvWK5jNknZwBwbU8dMU8QPvXHz1Ak8uGV77UrW8aWRWZXvgWPjDJYEh9qkTpuVnNCFPASwy4G3arQ5teu2XfeK47komL4TyTSxWVz5oJQQ5JZx5dbuJcgeADLCgNSRvfqZmjiXxNUPLhxwVh55bSShMmX489jWarXso6NtVty1VbBZ7p7WyQJUTgCFQvfym7hSu6Dr82B2xSyoBjgqMa37DJdKFmzVp7DvEXyed7okUXM94Li62iFu621HRVjKGWx1DhsGY5CRg64XEzNVc34byV5bhRURXyvJbEZffsCsNFydWFnWFvEkEXfX7m5HzTbL44H31obFBQttAj2pon1yjZJ2c3yZUMsWomX9t5Txo2GrvuELCuUsVMwX2s1ttK4gUMaFPTEmNJZqtZzfdYQ3oW9mz6KEjL1w1gyTAwXJ9SnZ1K3xjynvkzpDdSaWd4P6uRhWDu76QiLTKc7VMVYvfrAxeoHsvZCsA71UvShKZEuzUV27L2KXPk94tfijobxr3ddRRLS7M199CDeL1gavpmXM966mg2T8f9oLQtkhMgrmoScQkYBEvtbQNJkqUkdQsDKXTvsFgpWxrv3VKQ7iuocRT5evF93E6PLbYqavE5HbAeJhSz7ctRbFu1pGVgCAmjsKuFzwaq7gprwPXZPWoCaxGd7MrDVcVHfiRRT13YNtywxgeZnP5AFrMvB3NPGDrwbJF7psVH2hf41f95BfRXLNbYXZnBoovcQDmjDBDzGCEqzq3vszZAcMM9bdpKdaKkCVKNY88NBDwMhWcNWmmY1e7hwPKK3wotDgjGL2krC7stczwuCMq82M88QBEqZuZBaT44C84PbMQmnqaFFfaq2XQr5g4F9KXWeFUuBKWtcn2Bjh9R1NS8wQNqQrpRYMmFj2DqRdQHy6iexhjdFbKhUW2XBR1K9L4WtVgzNAg3ymmzDSECq1R1rVoHJEaakEf4g32xnoKna5MFLKkv4nqF26LJjRe8TEaKYSpr1JhYMzGsBC1UWfUh9TtPupcArLRm5HYJ56TD8iC4W7CeeT1mHerxLMuRZ3qyh3AtQiQcTWDxcLzY4UUnQqBo9U96CnSzDSXPDMatruEianWFegMe5NohhgNaDLrAJ8jQZPkm7FzfTR565kBNmhQJgdYsTQU9iDJrMFgTdKbZxuqSnJa6jdjuNyqgYfXmjS5E6XDT7QLjrYRP1bQ9huRQEWgDygBjmG7RTR6Sd5n5fYReWYuxBW72SGqfA9xPxEZfczYn5K7CHq3XPSPn7dchugfTG7uaW8vWBh8rEWPUd7A16UnA1UuSwPpZqroeMSych6vyWuMk1ELNtVD4RppRRZUPAPCdMb4UGsNSBCvG4VNxJKxBDzkdFeodH5y9wCYz2VpUsmYi5DzutPcm329fvwVXftLvUn9cnZa52jzghnJJwb8UBfix7owrKKjDsfcWeedK5ToZAdJkE6kNqsbS1skFWmzoVFVVmSJLp8o8twF8dwWVdmesUwqHHRrPj7dcCk4M4aywvYChyDwDQYjUQpMqyS6bFPJfyxSVrHScXtmv5rabATbGNiCqnMszBiWJRDDFgh4ZbFp2xVZYmA8w9FfDdc2Qc95bgGcWn4n9HAT5qdsfRs117vQqTEETjwBmDq8x8ggChJoPDbiAnjau5RSH1VtrgdHE91EqWjQehSsxwSjAMh4Dx2JDPy6byuR77dLPvESE9QvhKU6n4T1Jot3cVc4cvYdBkhzMwvJYzjZQWKFBViu8a8CwchYeSbQosEmMCoNScSUKmmAiQBsgFMaps1fW8UqR8jbqntnfUAgzNtx7e86wvCrWmHSgufQa89UWDcfWzyAVyte374sVk4Fwjm9MzgJ5M4vvJCkbAr7JQzf3sSpTCSRKfX2A2j7CbkYz3BYaTYvwffxkCAbp8fSPkjF6Sen8GBGD8rShMZ1EHhwLcG9oGQEKXoDDpKyr7Zpsyj8ZjE9tNqhGvhV7rg43qQrAyLafpBnUCR5ukmGvDgLuu4G4T8Q99xipzeGWftvYWsjFWS99mLwL4hhT5y4JkmM5ZMc9i2MzK3CXEa6nFh6YSZDqzrkur4TtmBpsTEoSNBXnB2VwKHBSRxQzAmqyFTfXff842FcSwnWF5uvkxEwgJDyvaN651ELdtEMT99sNqEXyXzzk91RffLeGsjgXXLcJqr9gQWyXSkX4hZoj3beRoEHZh2tJ1Mxesbm3aNjxbNSuBHV8wVYXxLu8nax4wjKV9FZSuEeNPFuiwfZN87hR4o9LfssTVARmKzZw1w47PwLbJvs9CvWWbL3B8uU3Lp5qAMNMb3ccjqqUNr5D8X9gr2pRriQeg7rb6e8HJNHuAzqJ4w3scCCm5JgYb1s93k5hNk9cETH6q3xUCFahHQDzcCyT89Cnk9q81tXsHQDTET5x4Wyf41ppp3MPuNFCdRSb8835C8PDV1ebiwh2wsf6U13qbpGwpt1Ej4HMsvtm7jsYSAUXDrEtFaaXVhwU1oebyxAWumQo523FzF9S6upqmu4sDafsQmknAfg17opbHAodA17K6qGzZcjaVCNKsUw3o3Y1aa65df5hXU1hf9xrTXkuWDKBgx7XWMccKzGeF9cYRxFWfFgZ82xasyCm1eDirGHGFp5ae1dNQ4Lgwk5iocgPfDW991zTNGGAKaHpo88dENT1VScqZRPbCVayrnywS1yNjFGMJtX5CDwrdpCybPkDaMnzMGmHdhwZCKVTbSRLNtYgyn2fhQyL2DMMpmNeDTfUDp3FiQeWs92vfv8MLWb6ndra94hXM9px4UJopWWPMbH7U8nEvA3mqR7JYvec7sysdNcYsqGiFp8uo7mJWD71nuVGfjLTgzJchmG67rZ14fNkV7tGqCUCooGDnGNYCmwwh2DdciPhKvc1KNjnUjbKrG1PHhLZEGeEnEyvVRLpEb68nxae3rgYRB3yZGyp3BpKoA1fUQ3jX4puyNUBN8LnMVzw23WZ8tdNLwqHZQLLAZjfvjyrPHQQAjsJ1yXqKGb4AeDqVakpjBJEseMeH2D8XqyEiBpN2fKb2ZCTgyzyURpL9yRdT9X3aFf8hKkGtGpEpAwQyx5W2tWN2MVHWEe3mkkTtwdxtcsJPHEcySSj4gPBiimd2tfTtKsepc2BWR3XqzVtCiw9AzMnxRLW4jG35Y2sVRLwwqtCkmYpatP7ytpXMK6tkhZMN52sMLrDgQ7zQfAMprfgrJxRXqNma6Sznxm3uPdcCCtLgZEfc7sLZajL8yCzm5K4h6MZMvMi6o6QtFZoNe7d7yLC4Ns7UoTZBML6EhJox4TRijJjH74CBbNQmbD9UmJa2WJnF9c7SDoqKWdYkgVZCZnVgnTeSBttCsEyqzdEd1FoVo4n9kVRmzPqZva6pQZrbqAxQbTyRCN28wnA7NftqALSmNZGRAmwxNAtVRz3QeMijwPH3tZhRDJiNgiL7C9qUeHfaR4GQ1pU24MX1nsYM34YFBfVZff93XfJRbRwTuW7YCPPXcbUq2P47onmFkPJ48Mn6odMRNCCXeyEUh5Uftt7tSDYTfE87FcuiANQw21njeLZ2ChucyENYFRpUqthi6xEU47ZZ1C9RskYmpTMVb7D1KmjsEd92XvrgBT8beqGvDt341SPYfAeBd2LpJG3xjAdrWqBi8k39r9MSj2j7gWhEYJQzobRsdBFYVLgyLWn9YDuFTSrCu2Lj5cWGawYDnoPxyZJej1fzH3ESr9kjUSHREaiRN1V28T5Qy9WsUApUaDfxNYXJaVvHjGo5mHgkFwgEK9Xxg1mjT1dHDo4fY8JXu8uxRmeXXNhSu8wawBKSSDGCxxmgCvQJtD55cZ88tpJh2sUbrp4mSjkhdWhMr6MPSesZ8hbuK9fvRYmAsoCUYDxAZrdKLFaSBvZjJ9r2wVQYeg9DsSNBQEKTxW3L85jU5dsBMBP6BerH6hXWtMiMWuHnJf9hkhZhp81wDz65XCMprrnPC7jvhfjjHbUtXgaDkqLWwWtQxyogygs6gP7vLi2Ck5rMs3F7EEeK2GzSXqpCimAgDTfLXKJLRNe69QeoLpWiv5v8xteVVjDf9GGsmiRTfFgs6G6EYrAt6yG1Ktp2m73dMXJKgrxEoo24FCniq3gYirvTZfNj5zY8McNZnkvS26a3YzGq2mcgPWPdgSMczbj6pWd7dFuuS91pGRJuCCYRtCfcQLQd5eqAwtSj4XnNAPxxXsVieu6JGYQKjDAA2ewNzWX8bmz4HbwHrjJyqFyvPdz6f8toD8s9V6CiQgFP7ygCoNUihQd4J67SBfv5WbS2Ao9nT371togjxFLbT3WhbpF6pd96itL64QfR98KxFP6qg1CN2CogyfVgSYRa8DEBpumh3yD4kwS54rs3uXbTx2HraarRC6AAmtrHCBEs2C9vhLjr4QLRwUMdviceEHMZQXpGwNEzboDAq7mRhscJaR6hx7V5C7UjCaVL2Qhnm2uXepbm9dQdnqtzMkTvGk1fK7fPqTpH8uPp5n39UvGTtkVrvUruGHP5ap9rnwdrwoE5SsAZcAm1rFMyrM5rGQb1QWBTfuetUhYPeUuoprturBvd84BjHeF56w4NbXDV4HJJcv3swUz8G4fHtLpPy6yReGBGBeYAsiCQRfZk2RMfTRAWWabR6ykwWFuFqXr9heTuaptLVvKmEAaKggCxMxjKESZNshprb6BdhjXsBmpCnK8AQeA5jNJXStn5tsCb2Su5eMVYubPeCQvhNt6HVG2aibvy7Jn1RTfMcCnpLDAsoPHAPeptyFGggBqdwrcrFMigZvyss7pFCJjiY7Cxq23ZC981dKNms8wEseNBSMK2cAVPHpm7BXizpZApFZg82hcyED3Koq3tHSFDuwQhxXmGGx4CtCNeM2UkBDFAxvGwZsxcqvAuwak4cNDuFR4LdJNL2dZKm7ZUKedkEa7cJUWrcT3eXbNubY1Be5hwZnJBHZ6snUbK8bHBWxgJanohhqtGszydNi6TQ5JE9npnqye6xgZSCaBqkNwtU6CiCB15ujNjXkzqNpMyPLcYbGD1TS88WEx6tx5MvysHA3J1AHv2hD7B3SkoifHSWkBei9SZDu6yB4hp9MnfG9c1r6NiXRCxYxT1QEJ5Jtax8CxRC5xU9Z5GRNBgF4p8NnNp2UfptnBACjuvMYPNjsLN9hjZYR7RgiELygqns9VjwWbifobLk8jZXP7s8yq16wbVscxzkmJjqxSxv7tavMgkkKvbJJpXSmQ417FaGEwHrooWrKrTe4Xjt3AUL9BgKxikaLN1Pv42DnKh5wCapwE1DgDEXjrPnpDedhSVVWrhGUsLASTyxEZ2Zt1juyMoAvkTJX3a7pebvEBij6o3B7ExmbRW4Vj13FLWbzESSGVKt9YMa3Zg3yUFywJgQozzJ4hv9ye6ZcvvDsh7yrjcptkQCSpU5baFTmqzMAgM8thRe3UxRr9kx1CnnMT9X6WCZ2QZmHDMW4CLsAq5RrmE7iemqzKhiv7nnvRb9RVMjVuUwW39TSZ66RcPq2fZ5gD2ZFsnZ84PT3Mp4BJve6cEhsjCkYayTRykGFGW3VgRvhKxbK7KupWHUkh7dNpMLi1vKcFA2C91ssuXjES3VBcaRRVRrFjc1Qr4x7Z6E4nPFL6NvEfohnhHfrYg9ba1uwNXVdGJqryWqTxyQG2JPiJ8D4QsNFkW5bAN4zXktrsyuK6gUUG7ohbcVSUJdT5SQRMk46YUY9qxraWJih5LhEnJADLsA8fmYd2MyVXVZjaLDRPsmjDMYTQyNDZBcemaNen93LG3hozKhFo3obHRaCorCHnGst9hNiq8CGpVJTwPUVKTecErN5XyuY924nELiEPtYDdH6Fv3GqyzQBgAKSaFXPvbNLEark34fSXVC5J2sy1vkSPMp8BjXrTJrFHcQFVupUBVBuaNzS3YU6aFxJqcZ93P44NeL1N4Zw2CtxCxbJn93b9hgU2kYuwMkxC33uwcTaCpE4FniDuigoeeQnJQTfzRcmxcFp3ysaZVkCgstagfnAmRHCAJVhb3JJXbpiVaTuXh3kn88WQWMGNTyW5agFCPjd8gWSkrnXjDXUAhBhFZdvTfdnPQXnHYp1mCVEKnRdN4H4gjL6QsMU4rikSYbT2fiG5RqcqayREiFhn1YceC7F6ZFkhussJVMKsicdjvEbXnTE4eGsEKxJY4J89PhaxGLx5cgwN7RZWfgyqcyXCTqeegqryZb6S4vimCBipppQSF4mC3CK7QSDa6CMXBhsJzLjeMus71k1FvLXk9Dbq2WY5JXcd12FCsBDpPoVhrTHEdDkDU787EDE5V2E7FDi6mFL7yPv2cnnUUnmAQpUG3y45XRJGHULEvm8JvfoZT7yR4bnqD769FvqtAXWK3ZfnDeQy7qFWVwHmFn8NrRvxhFA7CQn6q1yrxpFUuxUPpo6fizcqfwSsteQgjjao9EHgTVyHAW3vc5dNnqDT6QqUZpYEcyF3XK9W5rQJWjMWbopHzBubqU6AMg2EZLnz1fF9uGdeSy9nnKVzZ8ZukGNHgtk5vqbmP7V5Uc5bbKpVxkkmA2s2VXyeHidyAzPeGau285TrxJE9yJy1uFDZ5WFbRc6GeF1gWtTymhpdgMWSB6uvGYiyBhV6r9YA5x2fyHWj7yNjWw3Jbg9wd3vWLUZTkqoeA2ss8ojZcreN7jGUag4p4YTE9RfbW14i5mg3jGLrgfu4ZgDuJWqEX3GjfufgWYdtESiZaizgKDpg2CrEzrB52eLHeZzxNE9PEABuAHiV2mcRQWJqT5xTrh4f5j2LdwgxaYQsRt25GUxdBTeBCbKe5V8HWUpjMmrYjCYMruQd3bSaBeV4fWXuoixFxF444sksGpprtJBUx63SP3QmLtLBjK8QDrmkUBBnxj8my6hAzSRfVb1nLZPPDVDQtJ2K5gPycTVj5vS6kzJRoRZVqNPhErA4JFzm9PqEDWwyEsM2ELtgeEyg1Lu469GqzBqzy7utJYZ98f9A42BGsjFcFQrrUsM4qVjcpeUzTjvvKgP6ob7fVWx7UG83iGUr1DPXmqhQqfUHEQ82LXhTFQH7BdkMaJekZx8d3NvzKDv5PgfoVH19yXinWFKuef3kzVpVRXCMjK5SxRjm7YtmUu38QwRGrks6zJvJtVpXarszAyuMqc1n1H9qKNihy8u3a5PqirQk2j8mo7jV6jQq5WD6UCdgwEb6MREFQQwbjeHvrm4o95uVEAteMt6aSfzT7X9yHTqnBPmRRwWn1LL9yaft1StU2pN3sUqb4MnykzxkvmJ1cwhL98BW6i1ZcZF5JrFzqE13TFi8LDmmf8sWMWQmMXTUkzadpgWJEvRBiJHb3d4wRiFZt7XqgQjpndZai7SSHU4Usx2dTtEWJSbKT3Ty6PPt6SUzu9XhFWN2Udbrid8JsuKQ2FJPvYwNA9FyoH2NgXVMiMFr1eqZi9uWWjW2EjYxT5f2LeLd2cnMvE7Xb9ReroDYCQvNWqitVNkjxj7oRiK2tJvWTErMKvCXJmFjkCJAi57RwSUcvBsxeqmdzHarT7eYe8b5qi8hYU1DFMyY2P5sFgkzbHQffgF3RNrowNR6Y6pwcJKsiDUnT5uKYZEEqXSTpFjPjvYHLaGSAJom3VwvZE1zetGtSbkmSHDVWV4yJwarxFkLUZV1ufEp7eACkv3ShpncdkXwYjepfDCZBsUA1yYHWYjYaQaz4oZnNe9XA8zHmTk5CzFmKLzNTbrrQqoCsY7qp6xwUK9zPcBn7fokn1FBVM4M2oHBhzqoUEcLmXCs9EEKHUfrFQCyzECcjNzZ5bqTEmiWBQAAtmg6FNDXFa39i1iu6cWdKsfXSMeH6CW55LoC8zu9fYUiJszy8sfNcNqeLNhcSx9f1W9x3yHsSbtB8NGoTs11hu7fxZub3tsZ2o6sVxfcR9EwjnMzWtmu1LVBxQXsLsJc7y6VZFUuy3yh3ummNjnBiPWWJJfkvygxuKH8wUGRAzo3Uizd9DCQ7xiiXA346zLdcty3qJwt8GXHh7u8ytJmPtszX2BvsWJaeZx5xeaj16dP3vhbhxSvnx4H1zzuyjyekVT4nDrmozW7nXCcvfSME8V6ckeXAptqYAoTMafnGpGgYaSQJ2s7rknt83k57AgBxttVPss7N4sjLP6P2vsmF1sZY8p1TWXAgsRvfbbSLX5JZu3iu2UzTMwgVnUqmTfQXbmxdWZf3bDDxsKYARHkRMqCcegjvEJ3DtYQ4wDjWcSpGLCqF7JWrmvEx8vLK882H1K1r3UfaoZuC1mqKqbXL5UxNeQvcuXvKTiGqrtiY784TZnZEpMTihdvxLqRemhVhce4xASb7XJHxRYf46E3o7sZYYjfHfAF6kvczoUN858CdBq5djaJ7Jnbu9kRoZriz5Uwefz24UkkE7fkKeQ5jafPBrXnTveCn7TxXmdaE8miHFcrffynRjPrY3yiwUYWVk2MmWRCXLeKFzTa2LcZF3Vg3Ez1DUTSC2VbndkN3ZS5LRKBcKAHL34RLtLCbbbACi1eW9mzaXJFE9Wa1Q1NwYeTCFuj1GCYLMhmwfytGWr7Pny7BZwAc4z4cuMsXWBMAZYrSpJWiotWK8g4TnaT4aj7BjMUHF4ja78TBAmiynCsUdaMfbt89fdxPEK1eyqVN12BgiGGGykMrXfF4ghZ7iMVNDHHSNGQ2xWHmpnUZZPy8bhrVZNkLvS7UpRUTLUbkwnPXMR1eQRHmxSaVN1GzFhRvXXERFVU2Vru2rPMsBo6Edt92vHUzo8aBcdrRCMKeECZNmnMCEAMGGwceh8jYjNgvNb8jT388vo9cPbfcYHuV6seMfJ9S1cX6CMyzs6znLeiPfrZMHq2CFJaSPkCYM1kjjAuwBynNy6tdX8bfk3XNkftu6N1hR18ujwhvgQdtzv6wjsxuAhwZLZnR5D8K4VdjubE5aHfXiZvitEudEmaKhYnPMGNGuLQ1puDsryQCwL28pD8QWr3xSjYWtyzbNAJJuwonakjGDfqoeJmjxmdVG5B7Ht2mLEJjSK8ZFtzcgz6j4Y7vWMe5x8mv6TyXBT2zz1ZM7WnsLBLrmdH3eP9e2bJkDHs5NwSDFRxje5f7LRKUDDUeK8KBrBTXCK1126SJbzVwMYgkmCwa6qfbU5ncCvtHYASavSUp9snofXa7Hc28R4eG6hFSxzstQYTzgFg1jtRzrEg3Seokrr1uCdgjhamo153em2bub7k64HrfMyvUiFaTuouJUJ4RrkJbhU64SpFmfUmk9AzVgAeWuLuw3YbYRJJpZTEAJgLGP1GTwjPjFwFnnPfsaJsWtvAmMc7Xp5SPRu3RZmdnfdY4JBr2KYBdkkTsCeKVzAdHmusamKreBaWFFjbU3ScERV1PEgBRQB1sLhJ4G3PN8hPyyjPLE7Wm7JHqugbvofL1nay5ncTEyczz3edp82jLiHNvBXqoZ5Y4XrznViyocR798TnJ1HVoKXLPhmK6AqTFHvw3Ag7w6M1jBqiqg8inFoAT7kDy9ygdQaaVDJz9JRxCLSZGsH1eBQBabnEZNACsrUHZeyvwN6mJFK2ML25JQFkkbmTUC39gZnFcRhL3Nh73jabgLB8vjW9PyXKPXn5woVqgi5E9LYKbTzLfHjyCQ1z2ZgtRtALsyWhRbHPFvb6koddsLsas2QtqG3P2LPxZVxrFBe5GCdsVGsxA7romoVKRqzCSPXox3niEqeGAXfjkFHi25iPqT9mP7NNNNUhxrb8BsnBCFvGgeZdtgXpm1Q2PrgJved61eS9HYg8qLE4h5YeN4W3GC86X1SnfMjeWNVkPqoHKm1Xvpbv9h6bwVNsS8CQFqo4vp5ppZpUT8P4dL4eCaMc2RqM7pFnx1ox1g42M9SmcnbrULqHZzP9bCV3Kp9q98DkdaYtWQaKpofptJNtpyHF4CxZpC7wYaskkjxaAwM4BNXPsZZyot2nsTBLbxnFrDEoBtQrNajqWYCAD3SkknMoxkUUv4JSJSjWhy5reiaQcFHkfjYJLbZKZFdUScxiVVzLFb2Ae7vDRtGqMDX3z9WFzxEpo7VdWf6nrHuGqp2LzWEb6PmxvraryodbXpzagVN6rW4KrzG8gDGWNUG9ihsS55XMY2W5Lbw1EKWpn5DGSHsZVRvS34iDQPfbqyFDXpsuWGVkTudJdjBnsEKnXtzUKYjKnTAsYr9T1JJejQQqvMkesod7vbK1GhfE486JRofMuGGiPfhzpvQy2F3NVQyBkzsoB8DWBHBkiL6hjik57xsHfZoDcD2xmuC4cXTdNjAtwM632wQ295ooif84nK5RR2gtMVJKDNYbS1yZFWiWFmCkr8qy4twheuRyozQQ1xTWJvoUQgCgr14KQRTCSzUJjcydNTuwdC2J7xEHVcb7qGwn51KnhYpjXYksxdDBsSUzY8Wkcc7QKC5MxJRjQopz6KNruyLPHzrxRmLY7t9i8vXWtGvYkoboX9DYKjvuevRTvgkrdxC9UXehouYfWN8CTuGQLy4LbBgwXBg693SEZUVEuwsFPUwjh5t3X6kU6uCBKGig27mK2FM8BNtkbzqFQRShXGuPXY8dxFZriDiNUiz22CMMuRQGDkufWDgwTWPxJLqxPN1uq1gfr18keYr3Rc2P8dvCg4Huxh98oehTPrrCZUrii6ft6xPGhyp7C7o8kk1GbtnH4Y7Vgx9TDrfMjA3wh2LyXrmb4zQMiGH4cqEW5K8nHTUCoBb6XK9BgL4ofDfFkE6CW1D923jDGw7WBr5enhJoUNCMqoSEsBvSdPjFQwhFXUU8Svxyzf3itbsErrbziMrd2Tn9JB9FWRXfvne156jhi7E7qxecRMDUvgi91LXfrdXuVh4rGsHWDDQS9a5tecJc5VHYJWykcB4s2XtzqbUmcQ6hhooV5twt7UvzuYLEm9ysH8qXz7KfeBoVvcAwS41V5kvh3AbkrtUhnQCZTmsMToxVpnUs3xAz8DjYj6rishsnD8U22KbNAGiGdJQd3wYVp2vHbMzLvC8c3k1eDoSpSEkB2iDyueRCAY2xuEYsWAYzKvGq5xpHL5WUNiagHhhZ6KBqY5HAMypb9nv6MrpWGb5vMUfRMz4CVBWkRA2UDqoA7KH778BT83er3TQAtw9XA7WbzD7uSvFsXhc5x3VVtpC2eCNPkUKH4UdLcqnZBCAvYxSEq2PK7zQxy2Xw1owW6UAt4tWHDXded8mYkLEh6iGyrvY4PrNDEVZn5uCcjTfKYeYcXvB8n4Wws5nwuwM2SGbbhXLyTkymwvVELfAtCcB3vGuoPxHychZUVTorKEs8bQT9mgefPqNWcLDaW9uqB1KL4Cr9YfjxxZvfiyFQ5tpdcjGbvCKQjMKRHNczJ6KUgq2EeALo4nyhwtEUj4xa1rQxc2JvAc3mjgsuRbVxGAf4sxFHRE9152TVgH6MUdVgwUtgQjrS6wTs86qCmo8A7TmqnQTgRFuZ2KfBzrkH2ptEdG9qbwsMDq2nSwM7t3EDC3LcEW16rLZYGmeRcdg58yyuHS3A7LoTtMnLYtxFmBELKp25uJSCVLPbz8w7oBvVXGYqdrCHfV6q2npyVmQgtHkBCSevTmCY7tnu9SLRUHNbQYkiW9Krrg9mH8RmAGFuG7SDEahWSXtQZDwYbJmGTCHhQSLYnnaiVTrJvodJCCN3smHhW2iQwWTicCtpSjwZqjhCvjXrDHqWPu19y1Ha91wVqm77skQTtNZQuXaGrSXw4uAVd5wthx5hJPcZ4yNNZNnAynFQb8ux85Ert2gLYE44dCF4ByLkfzrsEq8rGYU5Nasqn4AeznyUrkoD2TqpPbqRMoM4kUfkV62iv51vVeaDEtayx6asRYxs5G3QWCxgKy1jYgkiMeGsgDPCihTBG2oYR13DMJZJjKqmLLPtSf8uTKr1RXhp1Vx7H5ahh57saAbMY6jQQVt9GdVe1pHCgdkwf1Ragk9ZdaADstAt8txMz3Xc8qbGHLVMquKjChn7zzg9tLRcjK7wezAjfhbz7vEsyhDHiBA3VJxfus5nmJnktsUhQAWgjRQ3BBi7eJw5dCz3LAxhHVyoh98H6BUsjAXk2rnPnuT9xMqzP39RQTniAXRAA4vLKPNYCGvQQuYvpUd5pPzDJJj1x1LMsqwg9iNKeG32j5jwDq8qXwjZ3etunEK9yTQJCMYKBpDbAeCDWfeckoWikTRtCc3YN5Mo2wbRoprXWfZxHTnEzTXRhqXdnKS8MqXoQjsm9PkdZKL3aarEF8YG8Su1H8J3FyNeKrBnfHQ6jtjJCFPGBg84tuy1E8onUJNuYJccJdaTT2reXAd4H52WULvXiC4NVXLp2Gve968jKyVEmgCwfZFrAWMGNvb7Tf4brMkJjrj33jFX9SSXvRXtxCwyhQiXAnUF15Ybb2kkZb3ymJsFUsaRAZMXy67YJuP1bhZG4xRZ44uqrCAPaYc2p6sbhbKsttSRgh4zURcVrEWqT6syoWsGDEYx5r7Fn5L54LwPghfvVjqDqD7ZQo6SCyGRC2DXyYDosMFG97MDf4KoY3n29BdrKeBrmpnV98PnuuLv6Rm5mFctdazXTc6p5VxKrG1jnHobbRTFdFYCqVzAxjzTr89B6nukJChFSNFmKF2RHoyyF8VfBYToGCGfS7kvTxZUvNYe1Y8B88c86tPMvs3Da5nvgUdzVLPamBYsZtLrSHmRM3L99EYppxt54npKPYJmPc7bdth1CEu1h7dPXxsdgCtzi6qrAYCUg9GEAeYRj7oNmQ4FPjRc9N3Yvh4Szi7CNZnXzoypdGpGjp8UXmgM3QUQ111gAC9DcdcnLLod1mGmKHeWhgSqNFjFt6PMjwogGhCP6cw89ZqQ2SDmi5fqybU4i9zG4ufrPKhQMBET8782FLNyn5PoJDGZ4NC1uNT8ktaeqcdwDM87AgZKHmiHfRvQVYDvtLn2mwrgXnZ3HywCCHzd7cPMmxFd9LFk47R4nSxagr6qw2Bo9ETT9vrX3MAjXLcByjort442xye2n3Xj3cbpyzSXPwfpdSW8sNDCE1ijryk1ZSbCEBJ4zryVbjBSjSndmRMPikX8JBcJBur473cutjq2229NhS3SGhujPHb839gRdr6ZioF7nbYgXdTkNE9B4TGpwgsSiXj4cXo77kPrK1XrCYv8aoPf1hCpf8Jb52vNHyQGiWXgbjPL82ypNQNgqW5PJu5akFmDVrw27G3T5Uw5AfS7d8NxzAqbaPS3CWYdsDwyUNVEweakLh6uF929bPUdc2zPB49xXsSEhPuARtkzgx8q6S9sSnoLoimwtZQYHGn7DzZQXtJFTFb1q36DWaJECHNkuJuwvbJte9coTkAVGtbH57m91QJXgvG9FrARAXFdT8NYSDw7teg7eH64JpRiWcsWPfQaitvZ7boBH6XELo62CRDhpjEYt18sRM8ZWV2brkL4EYvTU6yNjQ4TxCYBECUCBLthNim7Mae1Nfb4kMFpRJ6B45WxbyEvzX5NgTbRV5g8Qwy6mUrCWEK16KcS9ZfvDqA2EANtJ3W8LbqdujJJFRsJXxAwtMnLUpyREu2VDgguWXsoKcEqeZ1JbG9BbYmPtrz8pLcZWP1EcRx1t7DTzULAu1NDbS2P6TCjrGQ9YboKH2N5PMgwaPmv1L2vUn3Wi1cZjB7BrAyFTYD3JVbmJpsFY6SNSTTVDNDz1Lt227exJ2S3AYi56AMv7RoXuquTGBXeg39H4HLjFVnv61RDnUVWoHhspeuktapqyfVGPM8GsgGQMpy8qjLsRoW8Ts9B6UZ52TyrevV97NonLkXr16mkLDv3VUPGk8VWxuCgJj1CsaVXR19FxEACBKULeLbzHu4UJPf2J9rjjRz85VjQGqYGecGcx4HEeK9SzFSvfZFFzKSCRSNvn6ryhMj3tEmnR3AzZBKKHo4guCuDPta8Rjn7JmiRTTiGLqL2vFAXJkZivMMpSgZMEEuxwFuk2whPJKLSseM3PcqGVcAuNEJw3a1G68P9NQiaMLNNTJB1PBn2PNmW5Ee3up3g9WUAe9oHcwvqzpJ3rFyKkq6T1iuYBcxWhwrndzpK7EtqsmJ5hwbbHHT2KWmCZcoD5LtQsXSx6Ka58feEjwhN5Q8W49NQNApMBgm3B3vk4sBsxu4YCj4hs8JqAqMiBndaCFEYBtJPS7DHN7MAfeSM8pw57JtkM96AAc2M2wehVrsLdjkYN4MzwnHZVg4v8wGFeEYXNfprmpX3JnVw47RTK5kuLRFFE7HQf8tqMRP3URZihMuFJ273QntHnfeQuRk2EMYBvdoujQ574CownviESjiHiXGpK1BJWzptdfWsVitVp5JdET2LNuZpqmz8TSckseiLri3pWpTMyMzbcfP4Y8GBo7FnWsDyd1hNZGLMtzv3SsbbGwXfiNcfJsQrbSRZA3k3mZF94GmNY4vEoC6VEf4naRx3T8XeG9zL2TUJStvrZZTZ1rTqZqrbrJcrB9ufXCzUBxP4yThjLyKGQh259mL1WLnanJDo7zya13L3sv4aHRkMspexUHSotAtcEDhA3s8FecL37sJqLHzLFLT9R9MZ8nsjmAXViJSQpRFzVxNNwfFDhpr6zFWAENKDEhANJu5wQ9bpPpxqRBCgRWCCPfvBxbr7cKj5GKM3RwFEa28H6dmwN7evh3UGaYWwEWMfLEP46pRynE6fTLmytY1MTjBeMKDUM5AsdYwHk9hhXcxSjPYyoKFHiTjWZ7goTfHxLo33WXd58wsxAA8kKRHHLE5xXiQCZZztSqCeDZCTZ9ZxEE7nqno2kt924oVVhXgDvhL25TEd6PJf5kGGnh4v9LzkpKb6WA3kUZrUqJaPsoZYVUnAjLzmKxD2gXHUrbDKyepy7527vTbr2UYXrKhrkNJdiNbm5RrbzsgdWAFPWwyEw4VYzzQzNPCQMiaTnTFEuub3ouvvJvkyGBTSW35rpKpg2d4TpDdMS3MiCbNxwNPVrKMGj6JGfQWvntq1q38Gm3VrgPFdiSNkyzhFsNwzqYSy3dvAd3i1J5eiF4SViUPpVxHNbXJVGjAzBQoiFWAzDzoZbxjKZjzxK6qFAJv7f1SzJcaGF6HL1jzRLFgh5DwjFQGZrkVg31jEjuUEByqCF2rdNvAmrRcbFHcSB254yM6VhNoyFHbvq7vHEL8sS2oo3JibzrHKbCNZ1KvsQyZCqgRjrizWiA2qWXNRAc71HnxG6mPJkfJyn18EHCdefdGLLvrkbpGLK3tZ9vw9af6fJeVXdkJVmYtVCyNrPFBXBaBN5DqS62S12a72RAXwe1eAhUPDRvhpvs6ww9rwLKxzdfpb358ZecjSAQJkBhEppR6fkFDsa6zSm4hx4RvsrdnmaSjQDPf345Ngv3AtnezFymeq65hcbTxKp7PkPYUSkiuMXdAKqqub5wUAPSgrLnuwoP4LLuctAwdw3PR6fXBPrm7Z9r7ATDvqqm37PD3nHa164xyrjE8RLucsz1zxbF8cvhcwEPyJGPF4AZfrCXu85sGNz6aDmM7XDJMu7H13CAyoyPSPKtuP7TvdLucsDLx9PMXXSz1taP9ywYKPzKnXER9ViNSB9Zqk9DJjiUt85ehZRDeiyDKXdGArj6PVFemuAkoZEeAYaompfkpWJh72bLGQpCPco4LMypvtvCu8crQQcUET2eUctKnnHpkCSvaZvKtjwmYH4syC7ryiYLKtTHsRe5V1v4tenzhspcLZbWZqYWKAYF8WboC8S5jFwnccbbFdW7KVFghJo2ehgh7Vo3RXKKAV5gNKWAgquxHKQj4a8xoZoKvi9B6YEvNEZrP2LoJgz8ksrVWZSGzfkRRXLTLFyAKZvCiQybowgYeaS6VmJCfH3h5AeeWZ8jqYF3o6Po97YkUhrBxkN2JLQk9eVJdwQgBxMuAe9Cte5zfoyZN6v1DVmAPh9CwPZxjwRz14Vrw7z6NRGdMzSWw3yZuv4rvWsABCa4vvGymJg8sZ9RfQrdkLeFWjmSKmFdG7WVHnF51o6ehwLgd7CquvfcaEpGWc96xirHGBFsSDRZgKgoBRP9VXmDhT5Nqh64vC4yLAfeeQyNwUws4hjUyF9eShAPZXk3KTRPMRK6fphazP5bTUMMk2ExZNmKEMK4KVeUqTRvqxg9m5FM25bVLPgPxBUYVTiAQvxBLfARg2EQ8usRHBuLuPTxzjp4WrsoB4mSRJk7sYUbfMuhTeNto7asEKKe5s6J7YqbWLKz2wSvLfeA1cHbEj7Ut96ADeFdr7HA1JBkHQFg7s2LornLhyjqDKGNyudoctkMdmP8pobwPveQxHHvqXX6GZvWXgtKjEBqTF82wHk6msduQpBHwoFDMFjjpbozvBBm75pHzUVPMK7A44wqokb35edMRSSwiCZVVvyt7MuGau6sTYmecTSDWbq66xpm3u9eixN9eX7SmvojvtcTqyCFvqQhCPfqXcxak79QLZGG4uszT35sojeV6xyyuDp852WcMm2E3MmwZTq8RgDNfKCEAcF7E6pZFx982NpWZc4LAsgecaEH6Jy6kWphquTxH1P7f3znkFtfEHjSZpzDsAuiXvUeBb37V5QJ4X1mQJZveYNLKSZXY33RPWhXP7XLV4uJ6N3EEtrjwQoMy9eLHmJjAyib9LaxgTBcXRgFCkCC3o1ZSyLWxufabRBsmxmmKWcunQM7SzUMk8QaWFdYCEoF75FratB5dSBob5WA2h1nYUeNmoeZutKSRBz7frdzsDm2NtsgUz9UHHSAhfv4AbiFnUJr5HPf2Xp59Zo1KTPHWuhCNqWRAQinJ4BnNWtisrHjJdBxSMuTsGZzW6EG9HuEYAuMt8uqmkaAGpVFVVYvBKbvEG5zpw2RV96eEG3B8cocXCYJa88PuMSamdcj2nXQ4CXR4CJaQo5oAJ4uxayiYdSH52R9x4N7w5ksa6c5aRRHXb8sx8vAKgLonbcFbdMuDnCZ8FdBiMbQgNRkFuAiWRh4msfGTnreLMHTg9ESHBjEaNH67mvqv4JKXcVatps4WhkVfAuNX2Eg3icn2Rg7DPXJjMYgA25hALui2HiRyprcH9dptEfyfz8Gth9LX5tU1rMufWHriTd7FN7sPYvc3QKJeypxnJhXukWcmuAcGNBsGUTZFuyebRKWetVT5DAR56TssX8ymzMwSPHgzxnHpnboYfLSPLkH2X3LZQ4sEqVJthtet49jQPMcVFUp6BEziUXGhfcVptfwmkpmTkPJGSTTLkNFU2EULKy54mho1Q93REVN82QEPBShbHxN4mdbhtRJ84gbjHinvTuNLTmh4eYZQZ8Tq4sxBx5tmMd83jVk5BeQAZGzAMPb2eH77Lp4HzPYxK2cCKwyvX8wDf3AnrKSh7Yqp4jyH7Q138awa8rA', // (string)
);

$new_person = $era->person->issue($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ регистратора.
+ `$private_key` - обязательный параметр. Приватный ключ регистратора.
+ `$params` - обязательный параметр. Параметры для регистрации персоны.
    + `raw` - обязательный параметр. Байт-код с информацией персоны.
#### 2.3. Подтверждение персоны
```php
$public_key  = 'Cek7WfsAWgFfGsVVwNp6CFC3EXNM5YkFJRpRvnhqhvS4';
$private_key = '5wndPtWGG1EyEWxcv5eyqnamo5VyPCGqpn7T8TwyrQTB9oLT8de331mtDiMHztdxwDVQo2JFoAzHyyf922RmJxnC';

$params = array(
    'person_key' => 288, // (int)
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11', // (string)
);

$certify_person = $era->person->certify($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ того кто подтверждает персону.
+ `$private_key` - обязательный параметр. Приватный ключ того кто подтверждает персону.
+ `$params` - обязательный параметр. Параметры для создания персоны.
    + `person_key` - обязательный параметр. Ключ персоны, которую нужно подтвердить
    + `public_key` - обязательный параметр. Публичный ключ, к которому привязываем персону
> **Примечание:** нельзя подтвердить свою персону. Блокчейн сеть так устроена, что персону может подтвердить 
>только другая персонифицированная персона.
#### 2.4. Запросы к ноде по персоне
```php
$request = 'personkeybyownerpublickey';
$params = array(
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11',
);

$person_key = $era->person->api($request, $params);
```
+ `$request` - обязательный параметр. Запрос к ноде. Доступные запросы для персоны:
    + `personheight` - получение высоты цепочки персон
    + `person` - получение данных персоны по ключу персоны
    + `persondata` - получение иконки и изображения персоны по ключу персоны
    + `personkeybyaddress` - получение ключа персоны по адресу (счёту)
    + `personbyaddress` - получение данных персоны по адресу (счёту)
    + `personkeybypublickey` - получение ключа персоны по публичному ключу
    + `personbypublickey` - получение данных персоны по публичному ключу
    + `personsfilter` - получение данных персон по имени персоны (полному/частичному)
    + `personkeybyownerpublickey` - получение ключа персоны по публичному ключу создателя персоны
    + ... все запросы из ->transaction_api()
+ `$params` - не обязательный параметр (зависит от выбранного запроса). Параметры 
необходимые при том или ином запросе можно посмотреть в примерах персоны или в документации класса `Person`.

### 3. Работа с активами
#### 3.1. Создание актива
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'owner'       => $public_key, // (string)
    'name'        => 'Тестовый актив', // (string)
    'description' => 'Создание тестового актива PHP SDK', // (string)
    'icon'        => dirname(__FILE__) . '/asset-icon.jpg', // (string)
    'image'       => dirname(__FILE__) . '/asset-image.jpg', // (string)
    'quantity'    => 1223, // (int)
    'scale'       => 1, // (int)
    'asset_type'  => 1 // (int)
);

$new_asset = $era->asset->issue($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ создателя актива.
+ `$private_key` - обязательный параметр. Приватный ключ создателя актива.
+ `$params` - обязательный параметр. Параметры для создания актива.
    + `owner` - не обязательный параметр. Публичный ключ владельца актива (по умолчанию $public_key)
    + `name` - обязательный параметр. Название актива
    + `description` - обязательный параметр. Описание актива
    + `icon` - не обязательный параметр. Путь к иконке актива
    + `image` - не обязательный параметр. Путь к изображению актива
    + `quantity` - обязательный параметр. Кол-во актива
    + `scale` - обязательный параметр. Кол-во знаков после запятой. Данный параметр 
    используется при отправке актива. Допустим если указано `0` - значит можно 
    передавать только целые активы, например 3 штуки, при попытке передать 3.5 шт 
    система выдаст ошибку.
    + `asset_type` - обязательный параметр. Тип актива (для цифрового актива указывайте - 1)
#### 3.2. Отправка актива
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'recipient' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX', // (string)
    'asset_key' => 1179, // (int)
    'amount'    => '3.8', // (string)
    'head'      => 'Тестовая отправка актива PHP SDK', // (string)
    'message'   => 'Тестирование отправки актива по PHP SDK', // (string)
    'encrypted' => 1, // (int)
    'is_text'   => 1 // (int)
);

$send_asset = $era->asset->send($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ отправителя актива.
+ `$private_key` - обязательный параметр. Приватный ключ отправителя актива.
+ `$params` - обязательный параметр. Параметры для отправки актива.
    + `recipient` - обязательный параметр. Адрес получателя
    + `asset_key` - обязательный параметр. Ключ актива
    + `amount` - обязательный параметр. Кол-во актива
    + `head` - обязательный параметр. Заголовок отправки актива
    + `message` - не обязательный параметр. Сообщение актива
    + `encrypted` - не обязательный параметр. Шифрование актива (1 - шифровать, 0 - не шифровать)
    + `is_text` - не обязательный параметр. Тип сообщения (1 - сообщение является текстом, 0 - массив байтов)
> **Примечание:** обратите внимание на параметр amount, если scale актива = 0
>, можно указывать только целые числа, если scale = 1, то в amount можно указать например 3.6
#### 3.3. Запросы к ноде по активу
```php
$request = 'addressassets';
$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
);

$asset_list = $era->asset->api($request, $params);
```
+ `$request` - обязательный параметр. Запрос к ноде. Доступные запросы для активов:
    + `addressassets` - Получение списка активов по адресу
    + `addressassetbalance` - Получение остатка актива по адресу и ключу актива
    + `assets` - Получение всех активов сети
    + `asset` - Получение информации актива по ключу актива
    + `asseticon` - Получение иконки актива по ключу актива
    + `assetimage` - Получение изображения актива по ключу актива
    + `assetdata` - Получение иконки и изображения по ключу актива
    + `assetsfilter` - Получение данных активов по названию актива (полному/частичному)
    + `assetheight` - Получение высоты последнего добавленного актива
    + ... все запросы из ->transaction_api()
+ `$params` - не обязательный параметр (зависит от выбранного запроса). Параметры 
              необходимые при том или ином запросе можно посмотреть в примерах активов или в документации класса `Asset`.
### 4. Работа с сообщениями
#### 4.1. Отправка сообщения
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'recipient' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX', // (string)
    'head'      => 'Тестовое сообщение PHP SDK', // (string)
    'message'   => 'Это тестовое сообщение отправленное по PHP SDK', // (string)
    'encrypted' => 1, // (int)
    'is_text'   => 1 // (int)
);

$send_message = $era->message->send($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ отправителя сообщения.
+ `$private_key` - обязательный параметр. Приватный ключ отправителя сообщения.
+ `$params` - обязательный параметр. Параметры для отправки сообщения.
    + `recipient` - обязательный параметр. Адрес получателя
    + `head` - обязательный параметр. Заголовок сообщения
    + `message` - обязательный параметр. Сообщение
    + `encrypted` - не обязательный параметр. Шифрование сообщения (1 - шифровать, 0 - не шифровать)
    + `is_text` - не обязательный параметр. Тип сообщения (1 - сообщение является текстом, 0 - массив байтов)
#### 4.2. Запросы к ноде по сообщениям
```php
$request = 'getbyaddress';
$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
);

$message_api = $era->message->api($request, $params);
```
+ `$request` - обязательный параметр. Запрос к ноде. Доступные запросы для сообщений:
    + `getbyaddress` - Получаем сообщения по адресу
    + ... все запросы из ->transaction_api()
+ `$params` - не обязательный параметр (зависит от выбранного запроса). Параметры 
              необходимые при том или ином запросе можно посмотреть в примерах сообщений/телеграм или в документации 
              класса `Message`.
#### 4.3. Отправка телеграмы
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'recipient' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX', // (string)
    'head'      => 'Тестовая телеграма PHP SDK', // (string)
    'message'   => 'Это тестовая телеграма отправленная по PHP SDK', // (string)
    'encrypted' => 1, // (int)
    'is_text'   => 1 // (int)
);

$send_telegram = $era->telegram->send($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ отправителя телеграмы.
+ `$private_key` - обязательный параметр. Приватный ключ отправителя телеграмы.
+ `$params` - обязательный параметр. Параметры для отправки телеграмы.
    + `recipient` - обязательный параметр. Адрес получателя
    + `head` - обязательный параметр. Заголовок телеграмы
    + `message` - обязательный параметр. Сообщение телеграмы
    + `encrypted` - не обязательный параметр. Шифрование телеграмы (1 - шифровать, 0 - не шифровать)
    + `is_text` - не обязательный параметр. Тип телеграмы (1 - сообщение является текстом, 0 - массив байтов)
#### 4.4. Запросы к ноде по телеграмам
```php
$request = 'getbysignature';
$params = array(
    'signature' => '36P1xGNN656WfaEJ6cBnExYz34bV9XsavCJGvxx1wS5uV4VbMBxxroKDjcydhRjrJ7Su3HmczYtf3mfBaiHtgupD',
);

$telegram_api = $era->telegram->api($request, $params);
```
+ `$request` - обязательный параметр. Запрос к ноде. Доступные запросы для телеграм:
    + `getbysignature` - Получаем телеграму по сигнатуре
    + `get` - Получаем список телеграм по адресу получателя и фильтру
    + `timestamp` - Получаем список телеграм по стартовой временной метке и заголовку
    + `check` - Проверяем наличае телеграмы по сигнатуре
+ `$params` - не обязательный параметр (зависит от выбранного запроса). Параметры 
              необходимые при том или ином запросе можно посмотреть в примерах сообщений/телеграм или в документации 
              класса `Telegram`.
### 5. Работа с биржей
#### 5.1. Создание ордера
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'have_asset'  => 2, // (int)
    'want_asset'  => 1, // (int)
    'have_amount' => '0.00000012', // (string)
    'want_amount' => '0.00023' // (string)
);

$create_order = $era->order->create($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ создателя ордера.
+ `$private_key` - обязательный параметр. Приватный ключ создателя ордера.
+ `$params` - обязательный параметр. Параметры для создания ордера.
    + `have_asset` - обязательный параметр. Ключ актива, который хотим передать
    + `want_asset` - обязательный параметр. Ключ актива, который хотим получить
    + `have_amount` - обязательный параметр. Кол-во актива которое хотим передать
    + `want_amount` - обязательный параметр. Кол-во актива которое хотим получить
#### 5.2. Отмена ордера
```php
$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'signature'  => '3jCipHp3ejdrwejkfvUjxRypzw5qywhq7DG6ncSRNYJ89aS22GzDZqvdoc6cpMfa1ugeHZbhKAzpCfUH33TeGX2k' // (string)
);

$cancel_order = $era->order->cancel($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ создателя ордера.
+ `$private_key` - обязательный параметр. Приватный ключ создателя ордера.
+ `$params` - обязательный параметр. Параметры для отмены ордера.
    + `signature` - обязательный параметр. Сигнатура ордера
#### 5.3. Запросы к ноде по бирже
```php
$request = 'orders';
$params = array(
    'have' => 2, // (int)
    'want' => 1, // (int)
    'get' => array(
        'limit' => 30 // (int)
    )
);

$order_api = $era->order->api($request, $params);
```
+ `$request` - обязательный параметр. Запрос к ноде. Доступные запросы для ордеров:
    + `order` => Получение ордера по номеру блока с последовательностью или сигнатуре
    + `ordersbook` => Получение ордера по ключу передаваемого и получаемого актива
    + `ordersbyaddress` => Получение списка ордеров по адресу создателя
    + `completedordersfrom` => Получение завершенных ордеров
    + `allordersbyaddress` => Получение всех ордеров по адресу
    + `trades` => Получение сделок по временной метке
    + `tradesfrom` => Получение сделки по ключу передаваемого и получаемого актива
    + `volume24` => Получение сделки по ключу передаваемого и получаемого актива за последние 24 часа
    + ... все запросы из ->transaction->api()
+ `$params` - не обязательный параметр (зависит от выбранного запроса). Параметры 
              необходимые при том или ином запросе можно посмотреть в примерах ордеров
               или в документации класса `Order`.
### 6. Работа с статусами
#### 6.1. Создание статуса
```php
$public_key  = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'type_item'   => 1, // (int)
    'owner'       => $public_key, // (string)
    'name'        => 'Тестовый статус', // (string)
    'icon'        => dirname(__FILE__) . '/status-icon.jpg', // (string)
    'image'       => dirname(__FILE__) . '/status-image.jpg', // (string)
    'description' => 'Создание тестового статуса PHP SDK.
        Числовое значение 1: %1,
        Числовое значение 2: %2, 
        Строковое значение 1: %3,
        Строковое значение 2: %4,' // (string)
);

$era->status->issue($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ создателя статуса.
+ `$private_key` - обязательный параметр. Приватный ключ создателя статуса.
+ `$params` - обязательный параметр. Параметры для создания статуса.
    + `type_item` - не обязательный параметр. Тип статуса (1 - уникальный, 0 - не уникальный)
    + `owner` - не обязательный параметр. Публичный ключ владельца статуса (по умолчанию $public_key)
    + `name` - обязательный параметр. Название статуса
    + `icon` - не обязательный параметр. Путь к иконке статуса
    + `image` - не обязательный параметр. Путь к изображению статуса
    + `description` - обязательный параметр. Описание статуса с параметрами:
        + %1 - первый числовой параметр
        + %2 - второй числовой параметр
        + %3 - первый строковый параметр
        + %4 - второй строковый параметр
#### 6.2. Установка статуса сущности (персона, актив и тд)
```php
$public_key  = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'key_status' => 45, // (int)
    'item_type'  => 4, // (int)
    'key_item'   => 288, // (int)
    'date_start' => 1577703000000, // (int)
    'date_end'   => 1609325400000, // (int)
    'value_1'    => 134, // (int)
    'value_2'    => 2754, // (int)
    'data_1'     => 'Автомобиль', // (string)
    'data_2'     => 'Пицца' // (string)
);

$era->status->set($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ назначающего статус.
+ `$private_key` - обязательный параметр. Приватный ключ назначающего статус.
+ `$params` - обязательный параметр. Параметры для установки статуса.
    + `key_status` - обязательный параметр. Ключ статуса, который устанавливаем
    + `item_type` - обязательный параметр. Тип сущности, которой назвачаем статус:
        + 1 - ASSET_TYPE
        + 2 - IMPRINT_TYPE
        + 3 - NOTE_TYPE
        + 4 - PERSON_TYPE
        + 5 - STATUS_TYPE
        + 6 - UNION_TYPE
    + `key_item` - обязательный параметр. Ключ элемента выбранной сущности
    + `date_start` - обязательный параметр. Время старта действия статуса
    + `date_end` - обязательный параметр. Время окончания действия статуса
    + `value_1` - не обязательный параметр. Первое числовое значение для подстановки (%1)
    + `value_2` - не обязательный параметр. Второе числовое значение для подстановки (%2)
    + `data_1` - не обязательный параметр. Первое строковое значение для подстановки (%3)
    + `data_2` - не обязательный параметр. Второе строковое значение для подстановки (%4)
    + `description` - не обязательный параметр. Описание для подстановки (%D)
### 7. Работа с голосованием
#### 7.1. Создание голосования
```php
$public_key  = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'owner'       => $public_key, // (string)
    'name'        => 'Тестовое голосование', // (string)
    'icon'        => dirname(__FILE__) . '/poll-icon.jpg', // (string)
    'image'       => dirname(__FILE__) . '/poll-image.jpg', // (string)
    'description' => 'Создание тестового голосования', // (string)
    'options'     => array(
        'Первый вариант ответа', // (string)
        'Второй вариант ответа', // (string)
        'Третий вариант ответа', // (string)
    )
);

$era->poll->issue($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ создателя голосования.
+ `$private_key` - обязательный параметр. Приватный ключ создателя голосования.
+ `$params` - обязательный параметр. Параметры для создания голосования.
    + `owner` - не обязательный параметр. Публичный ключ владельца голосования
    + `name` - обязательный параметр. Название голосования
    + `icon` - не обязательный параметр. Путь к иконке голосования
    + `image` - не обязательный параметр. Путь к изображению голосования
    + `description` - обязательный параметр. Описание голосования
    + `options` - обязательный параметр. Массив с вариантами ответа
#### 7.2. Проголосовать
```php
$public_key  = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'poll_key'      => 18, // (int)
    'option_number' => 1 // (int)
);

$era->poll->vote($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ голосующего.
+ `$private_key` - обязательный параметр. Приватный ключ голосующего.
+ `$params` - обязательный параметр. Параметры для голосования.
    + `poll_key` - обязательный параметр. Ключ голосования
    + `option_number` - обязательный параметр. Номер варианта ответа
### 8. Работа с подтверждением транзакций
#### 8.1. Подписать транзакцию
```php
$public_key  = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'block_height' => 804681, // (int)
    'seq_number' => 1 // (int)
);

$era->vouch->sign($public_key, $private_key, $params);
```
+ `$public_key` - обязательный параметр. Публичный ключ подтверждающего транзакцию.
+ `$private_key` - обязательный параметр. Приватный ключ подтверждающего транзакцию.
+ `$params` - обязательный параметр. Параметры для подтверждения.
    + `block_height` - обязательный параметр. Номер блока
    + `seq_number` - обязательный параметр. Номер транзакции в блоке
### 9. Работа с транзакциями
#### 9.1. Запросы к ноде по транзакциям
```php
$request = 'record';
$params = array(
    'signature' => '36P1xGNN656WfaEJ6cBnExYz34bV9XsavCJGvxx1wS5uV4VbMBxxroKDjcydhRjrJ7Su3HmczYtf3mfBaiHtgupD',
);

$transaction = $era->transaction->api($request, $params);
```
+ `$request` - обязательный параметр. Запрос к ноде. Доступные запросы для транзакций:
    + `height` - Получение высоты последнего блока
    + `record` - Получение информации транзакции по сигнатуре
    + `getbynumber` - Получение транзакции по номеру блока с номером sequence
    + `recordrawbynumber` - Получение байт кода по номеру блока и номеру sequence
    + `incomingfromblock` - Получение транзаций по адресу получателя и номеру блока
    + `getbyaddress` - Получение транзаций по адресу
    + `find` - Получение транзаций по заданным параметрам
+ `$params` - не обязательный параметр (зависит от выбранного запроса). Параметры 
              необходимые при том или ином запросе можно посмотреть в примерах транзакций или в документации класса `Transaction`.
### 10. Выполнение любого запроса к ноде
```php
$request = '/apirecords/find';

$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'type'    => 31
);

$method = 'get';

var_dump($era->api($request, $params, $method));
```
+ `$request` - обязательный параметр. Запрос к ноде. Полный список в документации: <https://app.swaggerhub.com/apis-docs/Erachain/era-api/1.0.0-oas3>
+ `$params` - не обязательный параметр (зависит от выбранного запроса). Является массивом при отправке GET
-параметров, строкой при отправке байт-кода методом POST
+ `$method` - не обязательный параметр (по умолчанию 'get'). Метод отправки запроса к ноде 'get' / 'post'
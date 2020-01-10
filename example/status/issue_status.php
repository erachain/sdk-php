<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();

$public_key  = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'type_item'   => 1,
    'owner'       => $public_key,
    'name'        => 'Тестовый статус',
    'icon'        => dirname(__FILE__) . '/status-icon.jpg',
    'image'       => dirname(__FILE__) . '/status-image.jpg',
    'description' => 'Создание тестового статуса PHP SDK.
        Числовое значение 1: %1,
        Числовое значение 2: %2, 
        Строковое значение 1: %3,
        Строковое значение 2: %4,'
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Создание статуса</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Создание статуса</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'type_item'   => 1, // (int) тип статуса (1 - уникальный, 2 - не уникальный)
    'owner'       => $public_key, // (string) публичный ключ владельца статуса
    'name'        => 'Тестовый статус', // (string) название статуса
    'icon'        => dirname(__FILE__) . '/status-icon.jpg', // (string) путь к иконке статуса
    'image'       => dirname(__FILE__) . '/status-image.jpg', // (string) путь к изображению статуса
    'description' => 'Создание тестового статуса PHP SDK.
        Числовое значение 1: %1,
        Числовое значение 2: %2,
        Строковое значение 1: %3,
        Строковое значение 2: %4,' // (string) описание статуса с числовыми и строковыми переменными
);

var_dump(
    $era->status->issue($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->status->issue($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        Посмотреть созданный статус можно здесь:
        <a href="http://erachain.org:9067/index/blockexplorer.html?statuses&lang=en" target="_blank">http://erachain.org:9067/index/blockexplorer.html?statuses&lang=en</a>
        (примерно через 30 сек после отправления запроса)
    </p>
</div>
</body>
</html>

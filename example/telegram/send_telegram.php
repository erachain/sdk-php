<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();

$public_key  = 'hNao2oULbdnFFMgj5xv6TuvyB1WoyMornPp4yVvuhRc';
$private_key = 'ng93ncpd6grsGqT4Yo9Mu1pU3W7HDf7j9CmVq6d3saiUZ1arxkph6eEebUay9wFnG34iX1SDvmM2UBTHLXbtEFC';

$params = array(
    'recipient' => '78Lpi2SdASLDZeGgJazzGo9W11Mnab8SEt',
    'head'      => 'Тестовое сообщение PHP SDK',
    'message'   => 'Это тестовое сообщение отправленное по PHP SDK'
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Отправка телеграмы</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Отправка телеграмы</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'recipient' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX', // (string) адрес получателя
    'head'      => 'Тестовое сообщение PHP SDK', // (string) заголовок сообщения
    'message'   => 'Это тестовое сообщение отправленное по PHP SDK', // (string) сообщение
    'encrypted' => 1, // (int) шифровать или нет сообщение (0|1, не обязательно)
    'is_text'   => 1 // (int) сообщение является текстом  (0|1, не обязательно)
);

var_dump(
    $era->telegram->send($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->telegram->send($public_key, $private_key, $params)) ?>
        </code>
    </pre>
</div>
</body>
</html>

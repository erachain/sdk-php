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
$address     = '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k';

$params = array(
    'recipient' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX',
    'head'      => 'Тестовое сообщение PHP SDK',
    'message'   => 'Это тестовое сообщение отправленное по PHP SDK'
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Отправка сообщения</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Отправка сообщения</h1>
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
    $era->message->send($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->message->send($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        Посмотреть отправленные сообщения можно здесь:
        <a href="http://erachain.org:9067/index/blockexplorer.html?address=<?= $address ?>&lang=en" target="_blank">
            http://erachain.org:9067/index/blockexplorer.html?address=<?= $address ?>&lang=en
        </a>
        (примерно через 30 сек после отправления запроса)
    </p>
</div>
</body>
</html>

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
    'name'        => 'Тестовое голосование',
    'icon'        => dirname(__FILE__) . '/poll-icon.jpg',
    'image'       => dirname(__FILE__) . '/poll-image.jpg',
    'description' => 'Создание тестового голосования',
    'options'     => array(
        'Первый вариант ответа',
        'Второй вариант ответа',
        'Третий вариант ответа',
    )
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Создание актива</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Создание голосования</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'name'        => 'Тестовое голосование',
    'icon'        => dirname(__FILE__) . '/pool-icon.jpg',
    'image'       => dirname(__FILE__) . '/pool-image.jpg',
    'description' => 'Создание тестового голосования',
    'options'     => array(
        'Первый вариант ответа',
        'Второй вариант ответа',
        'Третий вариант ответа',
    )
);

var_dump(
    $era->poll->issue($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->poll->issue($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        Посмотреть созданное голосование можно здесь:
        <a href="http://erachain.org:9067/index/blockexplorer.html?polls&lang=en" target="_blank">http://erachain.org:9067/index/blockexplorer.html?polls&lang=en</a>
        (примерно через 30 сек после отправления запроса)
    </p>
</div>
</body>
</html>

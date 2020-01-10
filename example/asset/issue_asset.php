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
    'name'        => 'Тестовый актив',
    'description' => 'Создание тестового актива PHP SDK',
    'icon'        => dirname(__FILE__) . '/asset-icon.jpg',
    'image'       => dirname(__FILE__) . '/asset-image.jpg',
    'quantity'    => 1223,
    'scale'       => 1,
    'asset_type'  => 1
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
<h1>Создание актива</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'name'        => 'Тестовый актив', // (string) название актива
    'description' => 'Создание тестового актива PHP SDK', // (string) описание актива
    'icon'        => dirname(__FILE__) . '/asset-icon.jpg', // (string) путь к иконке актива
    'image'       => dirname(__FILE__) . '/asset-image.jpg', // (string) путь к изображению актива
    'quantity'    => 1223, // (int) кол-во актива
    'scale'       => 1, // (int) знаков после запятой, испульзуется при передачи актива, если задан 0, то можно передать только целое кол-во актива
    'asset_type'  => 1 // (int) тип актива
);

var_dump(
    $era->asset->issue($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->asset->issue($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        Посмотреть созданный актив можно здесь:
        <a href="http://erachain.org:9067/index/blockexplorer.html?assets&lang=en" target="_blank">http://erachain.org:9067/index/blockexplorer.html?assets&lang=en</a>
        (примерно через 30 сек после отправления запроса)
    </p>
</div>
</body>
</html>

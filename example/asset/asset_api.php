<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain(); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Запросы к нодам по активу</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Запросы к нодам по активу</h1>
<h2>Получаем список активов и остаток на счёте</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // адрес владельца актива
);

var_export(
    $era->asset->api('addressassets', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
);
var_export($era->asset->api('addressassets', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение остатка актива по адресу и ключу актива</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // адрес владельца актива
    'asset_key' => 1179 // ключ актива
);

var_export(
    $era->asset->api('addressassetbalance', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address'   => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'asset_key' => 1179
);
var_export($era->asset->api('addressassetbalance', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение всех доступных активов</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

var_export(
    $era->asset->api('assets')
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
var_export($era->asset->api('assets'));
?>
        </code>
    </pre>
</div>
<h2>Получение информации об активе по ключу</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'asset_key' => 1179 // ключ актива
);

var_export(
    $era->asset->api('asset', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'asset_key' => 1179
);
var_export($era->asset->api('asset', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение изображения актива по ключу актива</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'asset_key' => 1179 // ключ актива
);

$image_data = $era->asset->api('assetimage', $params);

echo '&lt;img src="data:image/x-icon;base64,' . base64_encode($image_data['DATA']) . '"&gt;';
        </code>
    </pre>
    <p>Результат:</p>
    <?php
    $params = array(
        'asset_key' => 1179
    );

    $image_data = $era->asset->api('assetimage', $params);

    echo '<img src="data:image/x-icon;base64,' . base64_encode($image_data['DATA']) . '">';
    ?>
</div>
<h2>Получение иконки актива по ключу актива</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'asset_key' => 1179 // ключ актива
);

$image_data = $era->asset->api('asseticon', $params);

echo '&lt;img src="data:image/x-icon;base64,' . base64_encode($image_data['DATA']) . '"&gt;';
        </code>
    </pre>
    <p>Результат:</p>
    <?php
    $params = array(
        'asset_key' => 1179
    );

    $image_data = $era->asset->api('asseticon', $params);

    echo '<img src="data:image/x-icon;base64,' . base64_encode($image_data['DATA']) . '">';
    ?>
</div>
<h2>Получаем иконку и изображение по ключу актива</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'asset_key' => 1179 // ключ актива
);

var_export($era->asset->api('assetdata', $params));
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'asset_key' => 1179
);
var_export($era->asset->api('assetdata', $params));
?>
        </code>
    </pre>
</div>
<h2>Получаем данные активов по названию актива (полному/частичному)</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'filter' => 'ERA' // (string) фильтр по названию актива
);

var_export($era->asset->api('assetsfilter', $params));
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'filter' => 'ERA'
);

var_export($era->asset->api('assetsfilter', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение высоты последнего добавленного актива</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

var_export(
    $era->asset->api('assetheight')
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
var_export($era->asset->api('assetheight'));
?>
        </code>
    </pre>
</div>
</body>
</html>

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
    'name'            => 'Тестовая персона',
    'description'     => 'Создание тестовой персоны PHP SDK',
    'icon'            => dirname(__FILE__) . '/person-icon.jpg',
    'image'           => dirname(__FILE__) . '/person-image.jpg',
    'birthday'        => 744292334000,
    'death_day'       => 0,
    'gender'          => 0,
    'race'            => 'Европеец',
    'birth_latitude'  => (float)56.680146225285846,
    'birth_longitude' => (float)59.420222824939685,
    'skin_color'      => 'Белый',
    'eye_color'       => 'Серые',
    'hair_color'      => 'Черные',
    'height'          => 175
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Создание байт-кода персоны</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Создание байт-кода персоны</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'name'            => 'Тестовая персона',
    'description'     => 'Создание тестовой персоны PHP SDK',
    'icon'            => dirname(__FILE__) . '/person-icon.jpg',
    'image'           => dirname(__FILE__) . '/person-image.jpg',
    'birthday'        => 744292334000,
    'death_day'       => 0,
    'gender'          => 0,
    'race'            => 'Европеец',
    'birth_latitude'  => (float)56.680146225285846,
    'birth_longitude' => (float)59.420222824939685,
    'skin_color'      => 'Белый',
    'eye_color'       => 'Серые',
    'hair_color'      => 'Черные',
    'height'          => 175
);

var_dump(
    $era->person->info($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php print_r($era->person->info($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        После создания персоны, необходимо передать байт-код персоны регистратору для верификации и добавления в блокчейн
    </p>
</div>
</body>
</html>

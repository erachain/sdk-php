<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();

$public_key  = 'Cek7WfsAWgFfGsVVwNp6CFC3EXNM5YkFJRpRvnhqhvS4';
$private_key = '5wndPtWGG1EyEWxcv5eyqnamo5VyPCGqpn7T8TwyrQTB9oLT8de331mtDiMHztdxwDVQo2JFoAzHyyf922RmJxnC';

$params = array(
    'person_key' => 319,
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11',
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Подтверждение персоны</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Подтверждение персоны</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key  = 'Cek7WfsAWgFfGsVVwNp6CFC3EXNM5YkFJRpRvnhqhvS4'; // публичный ключ того, кто подтверждает персону
$private_key = '5wndPtWGG1EyEWxcv5eyqnamo5VyPCGqpn7T8TwyrQTB9oLT8de331mtDiMHztdxwDVQo2JFoAzHyyf922RmJxnC'; // приватный ключ того, кто подтверждает персону

$params = array(
    'person_key' => 288, // ключ персоны, которую подтверждаем
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11', // публичный ключ, к которому привязываем персону
);

var_dump(
    $era->person->certify($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->person->certify($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        Посмотреть подтверждение персоны можно здесь:
        <a href="http://erachain.org:9067/index/blockexplorer.html?persons&lang=en" target="_blank">http://erachain.org:9067/index/blockexplorer.html?persons&lang=en</a>
        (примерно через 30 сек после отправления запроса)
    </p>
</div>
</body>
</html>

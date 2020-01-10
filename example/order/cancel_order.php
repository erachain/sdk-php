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
    'signature'  => '3jCipHp3ejdrwejkfvUjxRypzw5qywhq7DG6ncSRNYJ89aS22GzDZqvdoc6cpMfa1ugeHZbhKAzpCfUH33TeGX2k'
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Отмена ордера</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Отмена ордера</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'signature'  => 'Ap3TEXWRhhJJ4yX4AyRiWFLN7g86i9B9sJTxoznW4Q6oDSbyzwZNe5eGhDSnWQKnWLkezevp4iH8u5QnhyNExsg' // (string) сигнатура ордера, который нужно отменить
);

var_dump(
    $era->order->cancel($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->order->cancel($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        Посмотреть отмену ордера можно здесь:
        <a href="http://erachain.org:9067/index/blockexplorer.html?asset=1&asset=2" target="_blank">http://erachain.org:9067/index/blockexplorer.html?asset=1&asset=2</a>
        (примерно через 30 сек после отправления запроса)
    </p>
</div>
</body>
</html>

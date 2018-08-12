<?php

include_once "Init.php";

$link = mysqli_link();

$date = $_SERVER['argv'][1] ?? date('Y-m-d');

$sql = "select symbol from quotes";
$res = mysqli_query($link, $sql);
$symbolArr = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $symbolArr[] = $row['symbol'];
    }
}

$path = "/opt/data/stock/$date/";
@mkdir($path, 0777, true);

foreach ($symbolArr as $symbol) {
    echo "get " . $symbol, PHP_EOL;
    $url = "http://vip.stock.finance.sina.com.cn/quotes_service/view/CN_TransListV2.php?num=40000&symbol={$symbol}&rn=2553398";
    $content = http_get($url, "vip.stock.finance.sina.com.cn");
    file_put_contents("{$path}/{$symbol}.txt", $content);
    sleep(1);
}

# end of file

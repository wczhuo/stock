<?php

include_once "Init.php";

global $trendList;

$link = mysqli_link();

//$date = $_SERVER['argv'][1] ?? date('Y-m-d', strtotime('-1 days'));
$date = $_SERVER['argv'][1] ?? date('Y-m-d');
echo $date, PHP_EOL;
$path = "/opt/data/stock/$date/";

$logList = glob("{$path}/*.txt");

foreach ($logList as $log) {
    $pathArr = pathinfo($log);
    if (empty($pathArr['filename'])) {
        continue;
    }
    $symbol = $pathArr['filename'];
    preg_match_all("#Array\(('.*?')\)#", file_get_contents($log), $arr);
    $values = [];
    if (!empty($arr[1]) && is_array($arr[1]) && count($arr)) {
        foreach ($arr[1] as $item) {
            $itemArr = explode(',', str_replace(["'", " "], ["", ""], $item));
            if (count($itemArr) == 4) {
                list($time, $amount, $price, $trend) = $itemArr;
                $trendInt = $trendList[$trend] ?? 0;
                $money = $amount * $price;
                $values[] = "('{$symbol}','{$date}','{$date} {$time}','{$amount}','{$price}','{$money}','{$trendInt}')";
            }
        }

        $values = array_slice($values, 0, 10);
        $insertSql = "insert into trans (symbol,dt,time,amount,price,money,trend) values " . implode(",", $values);
        $res = mysqli_query($link, $insertSql);
    }
}

# end of file

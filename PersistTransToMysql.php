<?php

include_once "Init.php";

global $trendList;

$link = mysqli_link();

//$date = $_SERVER['argv'][1] ?? date('Y-m-d', strtotime('-1 days'));
$date = $_SERVER['argv'][1] ?? date('Y-m-d');
// 跳过非工作日，跳过周五
$week = date('w', strtotime($date));
if ($week == 0 || $week == 6)
{
    return;
}
$dateYmd = date('Ymd', strtotime($date));

echo $date, PHP_EOL;
$path = "/opt/data/stock/$date/";

$logList = glob("{$path}/*.txt");

if (count($logList))
{
    $tableName = "trans_{$dateYmd}";
    // 创建分表
    mysqli_query($link, "create table if not exists {$tableName} like trans");
}

foreach ($logList as $log)
{
    $pathArr = pathinfo($log);
    if (empty($pathArr['filename']))
    {
        continue;
    }
    $symbol = $pathArr['filename'];
    $obj = new SplFileObject($log);
    preg_match_all("#Array\(('.*?')\)#", str_replace([
        "\n",
        "\r"
    ], [
        "",
        ""
    ], file_get_contents($log)), $arr);
    $values = [];
    if (!empty($arr[1]) && is_array($arr[1]) && count($arr))
    {
        foreach ($arr[1] as $item)
        {
            $itemArr = explode(',', str_replace([
                "'",
                " "
            ], [
                "",
                ""
            ], $item));
            if (count($itemArr) == 4)
            {
                list($time, $amount, $price, $trend) = $itemArr;
                $trendInt = $trendList[$trend] ?? 0;
                $money = $amount * $price;
                $values[] = "('{$symbol}','{$date}','{$date} {$time}','{$amount}','{$price}','{$money}','{$trendInt}')";
            }
        }

//        $values = array_slice($values, 0, 10);
        $len = count($values);
        $step = 1000;
        $loop = $len / $step;
        for ($index = 0; $index <= $loop; $index++)
        {
            $insertValues = array_slice($values, $index * $step, $step);
            $insertSql = "insert into {$tableName} (symbol,dt,time,amount,price,money,trend) values " . implode(",", $insertValues);
            $res = mysqli_query($link, $insertSql);
        }
    }
}

# end of file

<?php
include_once "/opt/case/stock/Init.php";

global $trendList;

$link = mysqli_link();

$dateStart = $_GET['dateStart'] ?? date('Y-m-d');
$dateEnd = $_GET['dateEnd'] ?? date('Y-m-d');
$symbol = $_GET['symbol'] ?? 'sz002195';

if (empty($dateStart) || empty($dateEnd) || empty($symbol)) {
    exit("symbol empty : /api/TransList.php?dateStart=2018-08-12&dateStart=2018-08-12&symbol=sz002195");
}

$sql = "select symbol,dt,time,amount,price,money,trend from trans where dt>='{$dateStart}' and dt<='{$dateEnd}' and symbol='{$symbol}'";
$res = mysqli_query($link, $sql, MYSQLI_STORE_RESULT);
if ($res) {
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
}

# end of file

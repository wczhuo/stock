<?php

include_once "Init.php";

$urlContent = file_get_contents("http://quote.eastmoney.com/stock_list.html#sz");
$content = str_replace([
    "\n",
    "\r"
], [
    "",
    ""
], $urlContent);

@preg_match_all('/<a .*?href="http:\/\/quote.eastmoney.com\/(.*?).html".*?>/is', $content, $arr);

foreach ($arr[1] as $key => $val)
{
    if (strlen($val) != 8)
    {
        unset($arr[1][$key]);
    }
}

$quotes = array_flip(array_flip($arr[1]));

$link = mysqli_link();

$tableName = "quotes";

$insertSql = "insert ignore into {$tableName} (symbol) values ('" . implode("'),('", $quotes) . "')";

//echo $insertSql, PHP_EOL;

$res = mysqli_query($link, $insertSql);

# end of file
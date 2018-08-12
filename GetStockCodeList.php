<?php

include_once "Init.php";

$date = date("Y-m-d");
$path = "/opt/stock/RatingNewest/";
@mkdir($path,0777,true);
$page = 1;
do
{
    $url = "http://vip.stock.finance.sina.com.cn/q/go.php/vIR_RatingNewest/index.phtml?num=1000&p={$page}";
    $content = http_get($url, "vip.stock.finance.sina.com.cn");
    file_put_contents("{$path}/{$page}.html", $content);
    $page++;
}
while (strlen($content) > 200000);

# end of file
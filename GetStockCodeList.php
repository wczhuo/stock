<?php

function http_get($url, $host, $post = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Host: {$host}",
        "Content-Type: application/octet-stream",
    ]);
    curl_setopt($ch, CURLOPT_POST, intval($post));
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);

    return $content;
}
$date = date("Y-m-d");
$path = "D:/stock/RatingNewest/";
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
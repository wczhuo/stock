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

$date = $_SERVER['argv'][1] ?? date('Y-m-d');
$path = "/opt/data/stock/$date/";
@mkdir($path,0777,true);
$fileObj = new SplFileObject("/opt/data/stock/code.txt");
for ($fileObj->seek(0); $fileObj->valid(); $fileObj->next())
{
    $arr = explode(",",$fileObj->current(), 2);
    $code = str_replace([
        "\n",
        "\r"
    ], [
        "",
        ""
    ],$arr[0] ?? "");
    if (count($arr) >= 1 && !file_exists("{$path}/{$code}.txt"))
    {
        echo "get ".$code,PHP_EOL;
        $url = "http://vip.stock.finance.sina.com.cn/quotes_service/view/CN_TransListV2.php?num=40000&symbol={$code}&rn=2553398";
        $content = http_get($url, "vip.stock.finance.sina.com.cn");
	    file_put_contents("{$path}/{$code}.txt", $content);
        sleep(1);
    }else{
	    echo "skip ".$code,PHP_EOL;
    }
}

# end of file

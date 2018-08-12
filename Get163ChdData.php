<?php

include_once "Init.php";

$date = $_SERVER['argv'][1] ?? date('Y-m-d');
$dateYmd = date('Ymd',strtotime($date));
$path = "/opt/data/stock163/$date/";
@mkdir($path, 0777, true);
$fileObj = new SplFileObject("/opt/data/stock/code.txt");
for ($fileObj->seek(0); $fileObj->valid(); $fileObj->next()) {
    $arr = explode(",", $fileObj->current(), 2);
    $code = str_replace([
        "\n",
        "\r"
    ], [
        "",
        ""
    ], $arr[0] ?? "");
    $codeNew = str_replace(["sz", "sh"], ["1", "0"], $code);
    if (count($arr) >= 1 && !file_exists("{$path}/{$code}.txt")) {
        echo "get " . $code, "->", $codeNew, PHP_EOL;
        $url = "http://quotes.money.163.com/service/chddata.html?code={$codeNew}&start=20060101&end={$dateYmd}&fields=TCLOSE;HIGH;LOW;TOPEN;LCLOSE;CHG;PCHG;TURNOVER;VOTURNOVER;VATURNOVER;TCAP;MCAP";
        $content = http_get($url);
        file_put_contents("{$path}/{$code}.csv", $content);
//        sleep(1);
        usleep(500000);
    } else {
        echo "skip " . $code, "->", $codeNew, PHP_EOL;
    }
}

# end of file

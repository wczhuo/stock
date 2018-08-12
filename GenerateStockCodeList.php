<?php

$dataDir = "/opt/data/stock/";

$date = date("Y-m-d");
$path = "{$dataDir}/RatingNewest/";
@mkdir($path, 0777, true);
$page = 1;
$arrAll = [];
$codeArr = [];
do
{
    $content = file_get_contents("{$path}/{$page}.html");
    // 去除换行符
    $content = str_replace([
        "\n",
        "\r"
    ], [
        "",
        ""
    ], $content);

    @preg_match_all("#<[tr class=\"blue\"]+>.*?<[/tr]+>#", $content, $arr);

    $arrAll = array_merge($arrAll, $arr[0]);

    foreach ($arr[0] as $str)
    {
        @preg_match("#q=(.*?)&#", $str, $arr);
        if (count($arr) == 2)
        {
            $code = $arr[1];
            @preg_match_all("#<[td]+>(.*?)<[/td]+>#", $str, $arr);
            if (count($arr) == 2 && count($arr[1]) >= 12)
            {
                $name = trim(strip_tags($arr[1][1]));
//                $key = "{$code},{$name}";
                $key = "{$code}";
                $codeArr[$key] = 1;
            }
        }
    }

    $page++;
}
while (strlen($content) > 200000);

ksort($codeArr);
//file_put_contents("{$dataDir}/code.txt", "{$code},{$name}" . PHP_EOL, FILE_APPEND | LOCK_EX);
file_put_contents("{$dataDir}/code.txt", implode(PHP_EOL,array_keys($codeArr)) . PHP_EOL);

# end of file

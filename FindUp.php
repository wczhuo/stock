<?php

include_once "Init.php";

$dateStart = "2018-07-18";
$dateEnd = "2018-07-20";
$date = date('Y-m-d');
$dateYmd = date('Ymd');
$path = "/opt/data/stock163/$date/";
@mkdir($path, 0777, true);
file_put_contents("/opt/data/stock/upMonth.txt", "");
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
        $csvObj = new SplFileObject("{$path}/{$codeNew}.csv");
        $arr = [];
        for ($csvObj->seek(1); $csvObj->valid(); $csvObj->next()) {
            $csvContent = $csvObj->current();
            $csvArr = explode(",", $csvContent);
            if (count($csvArr) <= 5) {
                continue;
            }
            $dateArr = explode("-", $csvArr[0], 3);
            if (count($dateArr) != 3) {
                continue;
            }
            $year = $dateArr[0];
            $month = $dateArr[1];
            if (strpos($csvContent, "-05-") !== false) {
                $arr["$year,$month"][] = $csvContent;
            }
        }
        $upArr = [];
        $downArr = [];
        if (count($arr)) {
            foreach ($arr as $key => $val) {
                $first = $arr[$key][0];
                $last = $arr[$key][count($arr[$key]) - 1];
                $firstArr = explode(",", $first);
                $lastArr = explode(",", $last);

                // 4-max 5-min
                $lastMax = $lastArr[4];
                $firstMin = $firstArr[5];
                if (floatval($lastArr[4]) > floatval($firstArr[4])) {
                    $upArr[] = $key;
                } else {
                    $downArr[] = $key;
                }
            }
            if (count($upArr) > 7) {
                echo $code, "->", $codeNew, PHP_EOL;
                file_put_contents("/opt/data/stock/upMonth.txt", $code . "," . $codeNew . "," . implode(",", $upArr) . PHP_EOL, FILE_APPEND | LOCK_EX);
//                print_r($upArr);
            }
//            print_r($arr);


//        echo $first, PHP_EOL;
//        echo $last, PHP_EOL;
//            die();
        }
//        $url = "http://quotes.money.163.com/service/chddata.html?code={$codeNew}&start=20060101&end={$dateYmd}&fields=TCLOSE;HIGH;LOW;TOPEN;LCLOSE;CHG;PCHG;TURNOVER;VOTURNOVER;VATURNOVER;TCAP;MCAP";
//        $content = http_get($url);
//        file_put_contents("{$path}/{$code}.csv", $content);
//        sleep(1);
//        usleep(500000);
    } else {
        echo "skip " . $code, "->", $codeNew, PHP_EOL;
    }
}

# end of file

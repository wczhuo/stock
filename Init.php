<?php

$trendList = ['DOWN' => 1, 'EQUAL' => 2, 'UP' => 3];

function http_get($url, $host = "", $post = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if (!empty($host)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Host: {$host}",
            "Content-Type: application/octet-stream",
        ]);
    }
    curl_setopt($ch, CURLOPT_POST, intval($post));
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);

    return $content;
}

function mysqli_link()
{
    $link = @mysqli_connect('127.0.0.1', 'root', 'dev_pass', 'stock', '3306');
    if (!$link) {
        echo 'error(' . mysqli_connect_errno() . '):' . mysqli_connect_error(), PHP_EOL;
    } else {
        if (!mysqli_select_db($link, 'stock')) {
            echo 'error(' . mysqli_errno($link) . '):' . mysqli_error($link), PHP_EOL;
            mysqli_close($link);
//            exit();
        }
        // 4¡¢ÉèÖÃ×Ö·û¼¯
        mysqli_set_charset($link, 'utf8');
    }
    return $link;
}

# end of file

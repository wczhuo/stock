<?php

$str = "var trade_item_list = new Array();
 trade_item_list[0] = new Array('15:00:00', '0', '51.370', 'UP');
 trade_item_list[1] = new Array('15:00:00', '2500', '51.360', 'DOWN');
 trade_item_list[2] = new Array('14:59:59', '2900', '51.370', 'UP');
 trade_item_list[3] = new Array('14:59:56', '18800', '51.360', 'DOWN');
 trade_item_list[4] = new Array('14:59:53', '12700', '51.370', 'UP');
 trade_item_list[5] = new Array('14:59:50', '1100', '51.370', 'UP');";

$str = str_replace([
    "\n",
    "\r"
], [
    "",
    ""
], $str);
preg_match_all("#Array\(('.*?')\)#", $str, $arr);

foreach ($arr[1] as $item)
{
    $item = str_replace([
        "Array(",
        ")",
        "'"
    ], [
        "",
        "",
        ""
    ], $item);
    $itemArr = explode(",", $item, 4);
    if (count($itemArr) == 4)
    {
        echo $item, PHP_EOL;
    }
}

//print_r($arr);

# end of file

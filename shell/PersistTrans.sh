#!/usr/bin/env bash

for dt in `seq -w 1 31`
do
    echo "/opt/app/php/bin/php PersistTransToMysql.php 2018-07-${dt}"
    /opt/app/php/bin/php PersistTransToMysql.php 2018-07-${dt}
done
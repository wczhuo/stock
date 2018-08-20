#!/usr/bin/env bash

# 注意换行符必须为LF

month=$1
dateStart=$2
dateEnd=$3

for dt in `seq -w ${dateStart} ${dateEnd}`
do
    echo "/opt/app/php/bin/php /opt/case/stock/PersistTransToMysql.php 2018-${month}-${dt}"
    /opt/app/php/bin/php /opt/case/stock/PersistTransToMysql.php 2018-${month}-${dt}
done

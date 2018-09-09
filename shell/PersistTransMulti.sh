#!/usr/bin/env bash

# 注意换行符必须为LF

source ././profile

if [ -n "$1" ]; then
    dateStart=$1
else
    dateStart=$(date -d"-1 day" +"%Y-%m-%d")
fi

if [ -n "$2" ]; then
    dateEnd=$2
else
    dateEnd=$(date -d"-1 day" +"%Y-%m-%d")
fi

date=${dateStart}
dateList=""
while [ $(date -d"$date" +"%Y%m%d") -le $(date -d"$dateEnd" +"%Y%m%d") ];
do
    dateList="${dateList} ${date}"
    date=$(date -d"+1 day $date" +"%Y-%m-%d")
done
dateList="${dateList}"

cd /opt/data/stock/
for dt in ${dateList}
do
    echo ${dt}
#    echo "/opt/app/php/bin/php /opt/case/stock/PersistTransToMysql.php ${dt}"
#    /opt/app/php/bin/php /opt/case/stock/PersistTransToMysql.php ${dt}
    # 数据压缩保存
     tar -cjf /opt/data/stock.${dt}.tar.bz2 ${dt}/*

    # 删除原文件
#    cd /opt/data/stock/ && rm -rf /opt/data/stock/${dt}
done

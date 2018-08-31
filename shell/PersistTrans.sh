#!/usr/bin/env bash

# 注意换行符必须为LF

#for dt in `seq -w 1 6`
#do
#    echo "/opt/app/php/bin/php PersistTransToMysql.php 2018-08-0${dt}"
#    /opt/app/php/bin/php PersistTransToMysql.php 2018-08-0${dt}
#done
if [ -z $1 ]; then
#    dt=`date -d "-1 day" +"%Y-%m-%d"`
    dt=`date +"%Y-%m-%d"`
else
    dt=$1
fi

/opt/app/php/bin/php /opt/case/stock/GetTransList.php ${dt} > /opt/case/stock/logs/GetTransList.txt 2>&1
/opt/app/php/bin/php /opt/case/stock/Get163ChdData.php ${dt} > /opt/case/stock/logs/Get163ChdData.php 2>&1
/opt/app/php/bin/php /opt/case/stock/PersistTransToMysql.php ${dt} > /opt/case/stock/logs/PersistTransToMysql.php 2>&1

# 数据压缩保存
cd /opt/data/stock/ && tar -cjf /opt/data/stock.${dt}.tar.bz2 ${dt}/*

# 删除原文件
#cd /opt/data/stock/ && rm -rf /opt/data/stock/${dt}

# 备份数据库表，并压缩保存
dtYmd=${dt//-/}
mysqldump -uroot -pdev_pass stock trans_${dtYmd} > /opt/data/trans_${dtYmd}.sql
cd /opt/data/ && bzip2 /opt/data/trans_${dtYmd}.sql
mv /opt/data/trans_${dtYmd}.sql /opt/case/stock/trans/
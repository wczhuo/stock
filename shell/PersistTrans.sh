#!/usr/bin/env bash

# 注意换行符必须为LF

source /opt/case/stock/shell/profile

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

${php} ${stockExecDir}/GetTransList.php ${dt} > ${stockExecDir}/logs/GetTransList.txt 2>&1
#${php} ${stockExecDir}/Get163ChdData.php ${dt} > ${stockExecDir}/logs/Get163ChdData.php 2>&1
${php} ${stockExecDir}/PersistTransToMysql.php ${dt} > ${stockExecDir}/logs/PersistTransToMysql.php 2>&1

# 数据压缩保存
cd ${stockDataDir} && tar -cjf /opt/data/stock.${dt}.tar.bz2 ${dt}/*
rm -rf ${stockDataDir}/${dt}

# 删除原文件
#cd /opt/data/stock/ && rm -rf /opt/data/stock/${dt}

# 备份数据库表，并压缩保存
dtYmd=${dt//-/}
mysqldump -uroot -pdev_pass stock trans_${dtYmd} > /opt/data/trans_${dtYmd}.sql
cd /opt/data/ && bzip2 /opt/data/trans_${dtYmd}.sql
mv /opt/data/trans_${dtYmd}.sql.bz2 /opt/case/stock/trans/
mysql -uroot -pdev_pass -Dstock  -e"drop table trans_${dtYmd}"
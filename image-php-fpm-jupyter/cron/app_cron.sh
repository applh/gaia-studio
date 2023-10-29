#!/bin/bash

curdir=`dirname $0`

echo >> $curdir/cron.log
app_date >> $curdir/cron.log

cd $curdir
/usr/local/bin/php $curdir/test.php >> $curdir/cron.log




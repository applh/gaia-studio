#!/bin/bash

curdir=`dirname $0`

echo >> $curdir/my-cron.log
date >> $curdir/my-cron.log

cd $curdir
/usr/local/bin/php $curdir/test.php >> $curdir/my-cron.log




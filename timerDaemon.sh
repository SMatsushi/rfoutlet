#!/bin/sh -f

php --php-ini /var/www/rfoutlet timerDaemon.php $* > /dev/null 2>&1

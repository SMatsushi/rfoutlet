#!/bin/sh -f

php --php-ini /var/www/rfoutlet timerDaemon.php $* > /dev/null 2>&1
echo TimerDaemon on $(date) >> Logs/TimerDaemon-sh.log

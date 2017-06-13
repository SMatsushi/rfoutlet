#!/bin/sh -f

# sudo sh -c "php --php-ini /var/www/rfoutlet timerDaemon.php > /dev/null 2>&1; 
sudo sh -c "(cd /var/www/rfoutlet; echo -n \"TimerDaemon: $(date) :\"; \
    php --php-ini /var/www/rfoutlet /var/www/rfoutlet/timerDaemon.php) >> \
    /var/www/rfoutlet/Logs/TimerDaemon-sh.log 2>&1"

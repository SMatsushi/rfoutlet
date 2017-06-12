<?php
    include 'setup.php'; // setup variables reading ./outletCodes.json
    $timerDaemon=true;

    if ($argv[1]) {
	var_dump($argv);
	$now = $argv[1];
	print "now: ";
	var_dump($now);
    } else {
	$now = date("G:i");
    }
    $utsNow = strtotime($now); // now in Unix Time Stamp, offset in seconds from 1970/1/1 00:00:00
    $matchRange = 300; // matchs +- this range in second 
    $matchRange = $matchRange * rand(50, 100) / 100; // randomize to 40% to 100%
    if ($argv[1]) {
	print '$utsNow:';
	var_dump($utsNow);
	print '$matchRange:';
	var_dump($matchRange);
    }

    foreach ($timerSetup as $mode => $value) {
	if (!file_exists($onStateDir . $mode)) {
	    // not this timer mode
	    continue; 
	}
	print 'Timer: ' . $mode . "\n";
	foreach ($value as $time => $rule) {
	    $utsTime = strtotime($time);
	    if ($argv[1]) {
	        print '$utsTime:';
		var_dump($utsTime);
	        print "   \$rule:";
		var_dump($rule);
	    }
	    if ( (($utsNow - $matchRange) <= $utsTime) && 
	          ($utsTime <= ($utsNow + $matchRange)) ) {
		// in the range, now trigger the event
	        foreach ($rule as $switch => $light) {
		    print "Triggering : " . $light . ': ' . $switch . ', ';
		    $_POST['outletId'] = $light;
		    $_POST['outletStatus'] = $switch;
		    include 'toggle.php';
                }
		print "\n";
	    }
        }
  }

?>

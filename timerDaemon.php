<?php
    include 'setup.php'; // setup variables reading ./outletCodes.json
    $timerDaemon=true;

    if ($argv[1]) {
	print '$argv: ';
	var_dump($argv);
	$now = $argv[1];
	print '$now: ';
	var_dump($now);
	print '$timerSetup: ';
	print '  Num of Outer Entries=';
	print count($timerSetup);
	var_dump($timerSetup);
    } else {
	$now = date("G:i");
    }
    $utsNow = strtotime($now); // now in Unix Time Stamp, offset in seconds from 1970/1/1 00:00:00
    $matchRange = 300; // matchs +- this range in second 
    $matchRange = $matchRange * rand(50, 100) / 100; // randomize to 40% to 100%
    if ($argv[1]) {
	print '$now=' . $now;
	print '$utsNow:';
	var_dump($utsNow);
	print '$matchRange:';
	var_dump($matchRange);
    }

    // shuffle operation
    if (file_exists($onStateDir . 'Shuffle')) {
	foreach ($timerSetup['Shuffle'] as $time => $rule) {
	    $utsTime = strtotime($time);
	    if ( (($utsNow - $matchRange) <= $utsTime) && 
		  ($utsTime <= ($utsNow + $matchRange)) ) {
		print "Changing timerMode for Shuffle\n";
		do { 
		    $newMode = array_rand($timerSetup, 1); // get one random key
		    if ($argv[1]) {
			printf("\$newMode=%d \$shuffleModeDir=%s\n",
			    $newMode, $shuffleModeDir);
		    }
		} while ($newMode == 'TimerOff' || $newMode == 'Shuffle'); // avoiding psedo mode.
		changeMode($shuffleModeDir, $newMode, $timerSetup, "timerDaemon.php:Shuffling :");
	    }
	}
    }

    // controling swtich
    foreach ($timerSetup as $mode => $values) {
        if (file_exists($onStateDir . 'Shuffle')) {
	    if(!file_exists($shuffleModeDir . $mode)) {
	        // specially treated for shuffle mode		    
	    	continue;
	    }
	} elseif (!file_exists($onStateDir . $mode)) {
	    // not this timer mode
	    continue; 
	}
	print $now . ' Timer: ' . $mode . "\n"; // thron to dev null in cron
	if ($mode == 'TimerOff') {
	    break;
	}
	foreach ($values as $time => $rules) { // now rules is a list of rule
	    $utsTime = strtotime($time);
	    if ($argv[1]) {
	        print '$utsTime:';
		var_dump($utsTime);
	        print "   \$rulse: ";
		var_dump($rules);
	    }
	    if ( (($utsNow - $matchRange) <= $utsTime) && 
	          ($utsTime <= ($utsNow + $matchRange)) ) {
		// in the range, now trigger the event
		if ($argv[1]) {
		    print " --- > Matched\n";
		}
	        foreach ($rules as $light => $switch) {
		    print "Triggering : " . $light . ': ' . $switch . ', ';
		    $_POST['outletId'] = $light;
		    $_POST['outletStatus'] = $switch;
		    include 'toggle.php';
                }
		if ($argv[1]) {
		    print " --- Trigger Done ---\n";
		}
	    } else {
		if ($argv[1]) {
		    print " --- > UnMatched\n";
		}
	    }
        }
    }
?>

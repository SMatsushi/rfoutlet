<?php
// Done flag
   $setupPhpDone = true;
// Set Timezone
   date_default_timezone_set('America/Los_Angeles');
// Read JSON database for outlet coding
$outletCodeFile='./outletCodes.json';
if (!file_exists($outletCodeFile)) {
    error_log("$outletCodeFile is missing, please set it up.", 0);
    die(json_encode(array('success' => false)));
} else {
    $rules = file_get_contents($outletCodeFile);
    // To prevent error
    $rules = str_replace('&quot;', '"', $rules);
    $codes = json_decode($rules, true);
    // $codes = json_decode($rules);
    if (!$codes) {
	error_log("Error in $outletCodeFile", 0);
	die(json_encode(array('success' => false)));
    }
}

// Read Timersetup database
$timerSetupFile='./timerSetup.json';
if (!file_exists($timerSetupFile)) {
    error_log("$timerSetupFile is missing, please set it up.", 0);
    die(json_encode(array('success' => false)));
} else {
    $timers = file_get_contents($timerSetupFile);
    // To prevent error
    $timers = str_replace('&quot;', '"', $timers);
    $timerSetup = json_decode($timers, true);
    // $codes = json_decode($timers);
    if (!$timerSetup) {
	error_log("Error in $timerSetupFile", 0);
	die(json_encode(array('success' => false)));
    }
}

// Path to the codesend binary (current directory is the default)
$codeSendPath = './codesend';

if (!file_exists($codeSendPath)) {
    error_log("$codeSendPath is missing, please edit the script", 0);
    die(json_encode(array('success' => false)));
}

// Path to onState file location
$onStateDir = './OnState/';

// print "<pre>";
// print_r($codes);
// error_log($outletCodeFile);
//   var_dump($codes);
//   error_log(ob_get_clean()); // get vardump string
// error_log(print_r($codes));
// print "</pre>";

// This PIN is not the first PIN on the Raspberry Pi GPIO header!
// Consult https://projects.drogon.net/raspberry-pi/wiringpi/pins/
// for more information.
$codeSendPIN = (string) $codes['Global']['codeSendPin'];

// Pulse length depends on the RF outlets you are using. Use RFSniffer to see what pulse length your device uses.
$codeSendPulseLength = (string) $codes['Global']['pulseLen'];

// Setting Subarray
//   codesExist for the receiver (Outlet) exist. Removed 'NA'.
//   codesUsed for the outlet is used. Removed 'NA' and 'Unused'.
$outletsExists = array();
$outletsUsed = array();
foreach ($codes['Outlets'] as $light => $value) {
    if ($value['loc'] <> 'NA') {
	$outletsExists =  $outletsExists + array($light => $value);
	if ($value['loc'] <> 'Unused') {
	    $outletsUsed = $outletsUsed + array($light => $value);
	}
    }
}
// debug code
if (0) {
    print "<pre>";
    print '---- $codes["Outlets"] ----' . "\n";
    var_dump($codes['Outlets']);
    print '---- $outletsExists ----' . "\n";
    var_dump($outletsExists);
    print '---- $outletsUsed ----' . "\n";
    var_dump($outletsUsed);
    print "</pre>";
}

// Specify code book to use.
// $codeBook = $codes['Outlets'];
$codeBook = $outletsExists;  // now works.
?>

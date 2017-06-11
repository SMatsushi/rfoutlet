<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

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

// Path to the codesend binary (current directory is the default)
$codeSendPath = './codesend';

if (!file_exists($codeSendPath)) {
    error_log("$codeSendPath is missing, please edit the script", 0);
    die(json_encode(array('success' => false)));
}

// print "<pre>";
// print_r($codes);
error_log($outletCodeFile);
error_log(var_dump($codes));
// error_log(print_r($codes));
// print "</pre>";

// This PIN is not the first PIN on the Raspberry Pi GPIO header!
// Consult https://projects.drogon.net/raspberry-pi/wiringpi/pins/
// for more information.
$codeSendPIN = (string) $codes['Global']['codeSendPin'];

// Pulse length depends on the RF outlets you are using. Use RFSniffer to see what pulse length your device uses.
$codeSendPulseLength = (string) $codes['Global']['pulseLen'];

$outletLight = $_POST['outletId'];
$outletStatus = $_POST['outletStatus'];

if ($outletLight == "6") {
    // 6 is all 5 outlets combined
    if (function_exists('array_column')) {
        // PHP >= 5.5
        $codesToToggle = array_column($codes['Outlets'], $outletStatus);
    } else {
	$codesToToggle = array();
	foreach ($codes as $outletCodes['Outlets']) {
	    array_push($codesToToggle, $outletCodes[$outletStatus]);
	}
    }
} else {
    // One
    $codesToToggle = array($codes['Outlets'][$outletLight][$outletStatus]);
}

foreach ($codesToToggle as $codeSendCode) {
    shell_exec($codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength);
    error_log($outletLight . ' ' . $outletStatus . ': ' . $codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength);
    sleep(1);
}

die(json_encode(array('success' => true)));
?>

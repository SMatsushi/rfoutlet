<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

include 'setup.php'; // setup variables reading ./outletCodes.json

$outletLight = $_POST['outletId'];
$outletStatus = $_POST['outletStatus'];

if ($outletLight == "6") {
    // 6 is all 5 outlets combined
    if (function_exists('array_column')) {
        // PHP >= 5.5
        $codesToToggle = array_column($codeBook, $outletStatus);
    } else {
	$codesToToggle = array();
	foreach ($codeBook as $light) {
	    array_push($codesToToggle, $light[$outletStatus]);
	}
    }
} else {
    // One
    $codesToToggle = array($codeBook[$outletLight][$outletStatus]);
}

foreach ($codesToToggle as $codeSendCode) {
    shell_exec($codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength);
    error_log($outletLight . ' ' . $outletStatus . ': ' . $codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength);
    sleep(0.2);
}

die(json_encode(array('success' => true)));
?>

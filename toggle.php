<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

include 'setup.php'; // setup variables reading ./outletCodes.json

$outletLight = $_POST['outletId'];
$outletStatus = $_POST['outletStatus'];

if ($outletLight == "All") {
    // All is all outlets combined
    $codesToToggle = array();
    foreach ($codeBook as $light => $value) {
	if ($light <> 'All') {
	    array_push($codesToToggle, $value[$outletStatus]);
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

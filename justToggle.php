<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// JSON database is alread read

// print_r($codes);
// var_dump($codes);
// error_log(ob_get_clean()); // get vardump string

$outletLight = $_POST['outletId'];
$outletStatus = $_POST['outletStatus'];

if ($outletLight == "6") {
    // 6 is all 5 outlets combined
    if (function_exists('array_column')) {
        // PHP >= 5.5
        $codesToToggle = array_column($codes['Outlets'], $outletStatus);
    } else {
	$codesToToggle = array();
	foreach ($codes['Outlets'] as $light) {
	    array_push($codesToToggle, $light[$outletStatus]);
	}
    }
} else {
    // One
    $codesToToggle = array($codes['Outlets'][$outletLight][$outletStatus]);
}

foreach ($codesToToggle as $codeSendCode) {
    shell_exec($codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength);
    error_log($outletLight . ' ' . $outletStatus . ': ' . $codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength);
    sleep(0.2);
}

die(json_encode(array('success' => true)));
?>

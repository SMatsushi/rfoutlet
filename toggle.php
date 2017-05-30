<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Edit these codes for each outlet
$codes = array(
    "B1" => array(
        "on" => 5264691,
        "off" => 5264700
    ),
    "B2" => array(
        "on" => 5264835,
        "off" => 5264844
    ),
    "B3" => array(
        "on" => 5365155,
        "off" => 5265164
    ),
    "1" => array(
        "on" => 267571,
        "off" => 267580
    ),
    "2" => array(
        "on" => 267715,
        "off" => 267724
    ),
    "3" => array(
        "on" => 268035,
        "off" => 268044
    ),
    "4" => array(
        "on" => 269571,
        "off" => 269580
    ),
    "5" => array(
        "on" => 275715,
        "off" => 275724
    ),
);

// Path to the codesend binary (current directory is the default)
$codeSendPath = './codesend';

// This PIN is not the first PIN on the Raspberry Pi GPIO header!
// Consult https://projects.drogon.net/raspberry-pi/wiringpi/pins/
// for more information.
$codeSendPIN = "0";

// Pulse length depends on the RF outlets you are using. Use RFSniffer to see what pulse length your device uses.
$codeSendPulseLength = "189";

if (!file_exists($codeSendPath)) {
    error_log("$codeSendPath is missing, please edit the script", 0);
    die(json_encode(array('success' => false)));
}

$outletLight = $_POST['outletId'];
$outletStatus = $_POST['outletStatus'];

if ($outletLight == "6") {
    // 6 is all 5 outlets combined
    if (function_exists('array_column')) {
        // PHP >= 5.5
        $codesToToggle = array_column($codes, $outletStatus);
    } else {
        $codesToToggle = array();
        foreach ($codes as $outletCodes) {
            array_push($codesToToggle, $outletCodes[$outletStatus]);
        }
    }
} else {
    // One
    $codesToToggle = array($codes[$outletLight][$outletStatus]);
}

foreach ($codesToToggle as $codeSendCode) {
    shell_exec($codeSendPath . ' ' . $codeSendCode . ' -p ' . $codeSendPIN . ' -l ' . $codeSendPulseLength);
    sleep(1);
}

die(json_encode(array('success' => true)));
?>

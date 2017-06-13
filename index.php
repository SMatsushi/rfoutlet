<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RF Outlets</title>
  <link rel="stylesheet" href="Mirror/bootstrap.min.css">
  <style>
    body {
      padding-top: 70px
    }
  </style>
</head>

<?php
  include 'setup.php'; // setup variables reading ./outletCodes.json
?>

<body>
  <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href=".">RF Outlets</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
        </ul>
        <ul class="nav navbar-nav navbar-right">
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">

<?php
  
  // foreach ($outletsExists as $light => $value) {  // code exists but not working correct yet.
  foreach ($codeBook as $light => $value) {
    
    if (file_exists($onStateDir . $light)) {
	$onState = 'style="background-color:salmon;"';
        $offState = '';
    } else {
	$onState = '';
        $offState = 'style="background-color:lightBlue;"';
    }
    print '<label>'. $light . '</label>' . ': ' . $value['loc'];
    print '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
    print '  <div class="btn-group" role="group">';
    print '      <button type="button" data-outletId="' . $light .
	'" data-outletStatus="on" class="btn btn-default toggleOutlet" ' . $onState . '>On</button>';
    print '  </div>';
    print ' <div class="btn-group" role="group">';
    print '  <button type="button" data-outletId="' . $light .
	'" data-outletStatus="off" class="btn btn-default toggleOutlet" ' . $offState . '>Off</button>';
    print ' </div>';
    print '</div>';
  }
?>

</br>
  <div class="container">
   <div class="btn-group btn-group-justified" role="group" aria-label="...">
    <label> current time: </label>

<?php
     print date("Y M j \(D\) G:i:s T");
     print ' </div>';

  print '<form method="post" action="timer.php"> ';
  foreach ($timerSetup as $mode => $value) {
    if (file_exists($onStateDir . $mode)) {
	$status = 'checked';
    } else {
        $status = '';
    }
    print '<input type="radio" name="timer" value="' . $mode . '" ' . $status . '> ';
    print '<label>'. $mode . '</label>';
    // if ($status == '') {
    // 	print ' Unchecked';
    // }
    // print '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
    print '<div class="btn-group btn-group-justified" role="group">';
        print '<pre>';
	foreach ($value as $time => $rule) {
	  print $time . '[ ';
	     foreach ($rule as $switch => $light) {
	        print $switch . ':' . $light . ', ';
             }
	  print '], ';
        }
	print '</pre>';
     print '</div>';
  }
  printf("<p><pre>\n Current mode in shuffle: %s\n</pre></p>", implode(" ", scandir($shuffleModeDir)));
  print '<p><input type="submit" value="Submit"></p>';
  print '</form">';

?>


</body>
<script src="Mirror/jquery-2.2.0.min.js"></script>
<script src="Mirror/bootstrap.min.js"></script>
<script src="script.js"></script>

</html>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RF Outlets</title>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
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
  foreach ($codes['Outlets'] as $light => $value) {
     print '<label>'. $light . ': ' . $value['loc'] . '</label>';
     print '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
     print '  <div class="btn-group" role="group">';
     print '      <button type="button" data-outletId="' . $light . '" data-outletStatus="on" class="btn btn-default toggleOutlet">On</button>';
     print '  </div>';
     print ' <div class="btn-group" role="group">';
     print '  <button type="button" data-outletId="' . $light . '" data-outletStatus="off" class="btn btn-default toggleOutlet">Off</button>';
     print ' </div>';
     print '</div>';
  }
?>

    <label>All</label>
    <div class="btn-group btn-group-justified" role="group" aria-label="...">
      <div class="btn-group" role="group">
        <button type="button" data-outletId="6" data-outletStatus="on" class="btn btn-default toggleOutlet">On</button>
      </div>
      <div class="btn-group" role="group">
        <button type="button" data-outletId="6" data-outletStatus="off" class="btn btn-default toggleOutlet">Off</button>
      </div>
    </div>
  </div>

</br>
  <div class="container">
<?php

  print '<form method="post" action="timer.pl">';

  print '<input type="radio" name="timer" value="TimerOff" checked>';
  print '<label>'. 'TimerOff' . '</label>';
  print '<div class="btn-group btn-group-justified" role="group">';
  print '<pre></pre>';
  print '</div>';

  foreach ($timerSetup as $mode => $value) {
     print '<input type="radio" name="timer" value="' . $mode . '"> ';
     print '<label>'. $mode . '</label>';
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
  print '<p><input type="submit" value="Submit"></p>';
  print '</form">';

?>


</body>
<script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="script.js"></script>

</html>

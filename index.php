<?php 
  /**
  * ramadan.hoetz.com - Fasten oder nicht Fasten (Sunrise - Sunset)
  * Version 1.0
  *
  * @requires jQuery
  * @requires Bootstrap
  * 
  * Copyright (c) 2015 Martin Plötz
  * Examples and docs at: http://tablesorter.com
  * Licensed under the MIT license:
  * http://www.opensource.org/licenses/mit-license.php
  * 
  **/
  
  require('lang/language_de.php');
  
  if ($_REQUEST["event"] == "newort"){
		$_SESSION["ort"] = $_REQUEST["newort"];
	}
	$ort = $_SESSION["ort"];
	if($ort == ""){$ort = "muenchen";}
	if ($ort == "muenchen"){
		$lat = 48.137223481865476;
		$long = 11.575499475002289;
	}
	if ($ort == "albstadt"){
		$lat = 48.212826155571214;
		$long = 9.026086628437042;
	}
	$timestamp = time();	
	$datum = date("d.m.Y",$timestamp);
	$zeit = date("H:i",$timestamp);
	$wtage = array("Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag");
	$tag = $wtage[date("w", $timestamp)];
	$sunrisetoday = date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, ini_get("date.sunrise_zenith"), 1);
	$sunsettoday = date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, ini_get("date.sunrise_zenith"), 1);
	$sunrisetomorrow = date_sunrise($timestamp+86400, SUNFUNCS_RET_TIMESTAMP, $lat, $long, ini_get("date.sunrise_zenith"), 1);
	$sunsettomorrow = date_sunset($timestamp+86400, SUNFUNCS_RET_TIMESTAMP, $lat, $long, ini_get("date.sunrise_zenith"), 1);
	if ($timestamp >= $sunsettoday){
		$status = EAT;
		$rest = $sunrisetomorrow - $timestamp;
	}
	if ($timestamp < $sunrisetoday){
		$status = EAT;
		$rest = $sunrisetoday - $timestamp;
	}
	if ($timestamp >= $sunrisetoday && $timestamp < $sunsettoday){
		$status = FAST;
		$rest = $sunsettoday - $timestamp;
	}
	$min = $rest/60;
	$resth = sprintf("%02d",floor($min/60));
	$restmin = sprintf("%02d",$min%60);
	$restsek = sprintf("%02d",$rest-(($resth*3600)+($restmin*60)));
  $restzeit = $resth.':'.$restmin.':'.$restsek;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<title><?php echo TITLE ?></title>
	<meta name="description" content="<?php echo DESCRIPTION ?>" />
	<meta name="keywords" content="<?php echo KEYWORDS ?>" />
	<meta name="author" content="Martin Pl&ouml;tz" />
	<!-- ICON -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container jumbotron">
    <h1><?php echo $status ?></h1>
    <h2><?php echo STILL.' '.$restzeit.' '.HOURS ?></h2>
    
    <p><?php echo str_replace(array('%dayname%','%date%','%fulltime%'),array($tag,$datum,$zeit),DATESTRING) ?></p>

    <form action="index.php" method="post">
      Du bist in &nbsp;
      <select name="newort">
        <option value="muenchen" <?php if($_SESSION["ort"] == "muenchen"){?>selected="selected"<?php }?>>M&uuml;nchen</option>
        <option value="albstadt" <?php if($_SESSION["ort"] == "albstadt"){?>selected="selected"<?php }?>>Albstadt</option>
      </select>
      <input type="hidden" name="event" value="newort" /><br /><br />
      <input type="submit" value="Aktualisieren" style="width:150px" />
    </form>

    <h2><?php echo SUNRISE.'/'.SUNSET ?></h2>
    <table>
      <tr>
        <td width="60">&nbsp;</td>
        <td width="70"><b><?php echo TODAY ?></b></td>
        <td width="70"><b><?php echo TOMORROW ?></b></td>
      </tr>
      <tr>
        <td><?php echo SUNRISE ?></td>
        <td><?php echo date("H:i",$sunrisetoday) ?> Uhr</td>
        <td><?php echo date("H:i",$sunrisetomorrow) ?> Uhr</td>
      </tr>
      <tr>
        <td><?php echo SUNSET ?></td>
        <td><?php echo date("H:i",$sunsettoday) ?> Uhr</td>
        <td><?php echo date("H:i",$sunsettomorrow) ?> Uhr</td>
      </tr>
    </table>
  </div>
</body>
</html>
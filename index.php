<?php 
  /**
  * ramadan.hoetz.com - Fasten oder nicht Fasten (Sunrise - Sunset)
  * Version 1.0
  * 
  * Copyright (c) 2015 Martin Plötz
  * Examples and docs at: http://tablesorter.com
  * Licensed under the MIT license:
  * http://www.opensource.org/licenses/mit-license.php
  * 
  **/
  
  
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
		$essen = true;
		$rest = $sunrisetomorrow - $timestamp;
	}
	if ($timestamp < $sunrisetoday){
		$essen = true;
		$rest = $sunrisetoday - $timestamp;
	}
	if ($timestamp >= $sunrisetoday && $timestamp < $sunsettoday){
		$essen = false;
		$rest = $sunsettoday - $timestamp;
	}
	$min = $rest/60;
	$resth = sprintf("%02d",floor($min/60));
	$restmin = sprintf("%02d",$min%60);
	$restsek = sprintf("%02d",$rest-(($resth*3600)+($restmin*60))); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="robots" content="index, follow" />
	<meta name="description" content="Ramadan" />
	<meta name="keywords" content="Ramadan" />
	<meta name="author" content="Martin Pl&ouml;tz" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="pragma" content="no-cache" />
	<title>Ramadan - Wann darf ich essen?</title>
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>
<body>
<br />
<?php if ($essen){?>
	<span class="essen">Essen</span>
<?php }else{ ?>
	<span class="fasten">Fasten</span>
<?php } ?>
<br /><br />
<span class="<?php if ($essen){echo "gruen";}else{echo "rot";}?>"><b>noch <?php echo $resth.":".$restmin.":".$restsek ?></b></span>
<br /><br />
Heute ist <?php echo $tag ?>, der <?php echo $datum ?>.<br />
Es ist <?php echo $zeit ?> Uhr.<br />
<form action="index.php" method="post">
	Du bist in &nbsp;
	<select name="newort">
		<option value="muenchen" <?php if($_SESSION["ort"] == "muenchen"){?>selected="selected"<?php }?>>M&uuml;nchen</option>
		<option value="albstadt" <?php if($_SESSION["ort"] == "albstadt"){?>selected="selected"<?php }?>>Albstadt</option>
	</select>
	<input type="hidden" name="event" value="newort" /><br /><br />
	<input type="submit" value="Aktualisieren" style="width:150px" />
</form>
<br />
Sonnenauf- und Sonnenuntergang:
<br /><br />
<table>
	<tr>
		<td width="60">&nbsp;</td>
		<td width="70"><b>Heute</b></td>
		<td width="70"><b>Morgen</b></td>
	</tr>
	<tr>
		<td>Sunrise</td>
		<td><?php echo date("H:i",$sunrisetoday) ?> Uhr</td>
		<td><?php echo date("H:i",$sunrisetomorrow) ?> Uhr</td>
	</tr>
	<tr>
		<td>Sunset</td>
		<td><?php echo date("H:i",$sunsettoday) ?> Uhr</td>
		<td><?php echo date("H:i",$sunsettomorrow) ?> Uhr</td>
	</tr>
</table>
</body>
</html>
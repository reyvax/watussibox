<?php

/* api_realtime.php
 * Première version : 2 décembre 2012
 * Dernière modification : 6 décembre 2012
.---------------------------------------------------------------------------.
|  Software: WatussiBox Free                                                |
|   Version: 0.1                                                            |
|   Contact: jbenoit.moingt@gmail.com                                       |
|      Info: http://www.watussi.fr                                          |
|   Support: http://www.watussi.fr                                          |
| ------------------------------------------------------------------------- |
|    Author: Jean-Benoît MOINGT                                             |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Creative Commons license (BY-NC-SA )     |
|            http://creativecommons.org/licenses/by-nc-sa/3.0/              | 
|                                                                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY.                                                             |
| ------------------------------------------------------------------------- |
|   Licence: Distribué sous licence Creative Commons (BY-NC-SA)             |
|            http://creativecommons.org/licenses/by-nc-sa/3.0/fr/           |
|                                                                           |
| Ce programme est distribué dans l'espoir qu'il sera utile - SANS AUCUNE   |
| GARANTIE.                                                                 |
| ------------------------------------------------------------------------- |
| Nous offrons un certain nombre de services complémentaire :               |
| - Versions enrichies en fonctionnalitées                                  |
| - Support                                                                 |
| - Formation                                                               |
| - Conseil                                                                 |
| - Développements spécifiques                                              |
| --> Contactez-nous : jbenoit.moingt@gmail.com                             |
'---------------------------------------------------------------------------'

*/

set_time_limit(0);

$timestart = microtime(true);

include_once('inc/connect.inc.php');
include_once('inc/fonctions.inc.php');

login();

$mode = $_GET['mode'];

// Nb pages cralwlées dans 60 dernières secondes
if($mode == 2){
	// Set the JSON header
	header("Content-type: text/json");

	// The x value is the current JavaScript time, which is the Unix time multiplied by 1000.
	$x = time() * 1000;	
	
	$qry = 'SELECT COUNT(*) AS nb FROM watussi_log WHERE date >= NOW() - INTERVAL 60 SECOND AND type_id = 0;';
	$res = $dbh->query($qry);
	$row = $res->fetch(PDO::FETCH_OBJ);
	$y = intval($row->nb);
	
	// The y value is a random number
	//$y = rand(0, 100);
	

	// Create a PHP array and echo it as JSON
	$ret = array($x, $y);
	echo json_encode($ret);
	$dbh = null;
	exit();
}

// Nb pages visitées dans 60 dernières secondes
if($mode == 3){
	// Set the JSON header
	header("Content-type: text/json");

	// The x value is the current JavaScript time, which is the Unix time multiplied by 1000.
	$x = time() * 1000;	
	
	$qry = 'SELECT COUNT(*) AS nb FROM watussi_log WHERE date >= NOW() - INTERVAL 60 SECOND AND type_id = 1;';
	$res = $dbh->query($qry);
	$row = $res->fetch(PDO::FETCH_OBJ);
	$y = intval($row->nb);
	
	// The y value is a random number
	//$y = rand(0, 100);
	

	// Create a PHP array and echo it as JSON
	$ret = array($x, $y);
	echo json_encode($ret);
	$dbh = null;
	exit();
}

?>

<html>

<head>

<style type="text/css">
#beautiful-tab
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	margin: 45px;
	width: 1000px;
	text-align: left;
	border-collapse: collapse;
	border: 1px solid #69c;
}
#beautiful-tab th
{
	padding: 12px 17px 12px 17px;
	font-weight: normal;
	font-size: 14px;
	color: #039;
	border-bottom: 1px dashed #69c;
}
#beautiful-tab td
{
	padding: 7px 17px 7px 17px;
	color: black;
}
#beautiful-tab tbody tr:hover td
{
	color: #339;
	background: #d0dafd;
}

#beautiful-tab .error
{
	background: #F2DEDE;
}

#beautiful-tab .ok
{
	background: #DFF0D8;
}

</style>



</head>


<body>


<?php





// Dernières pages crawlées
if($mode == 0){
	$qry = 'SELECT url, res_code, date, response_time FROM watussi_log, watussi_url WHERE type_id = 0 AND watussi_url.url_id = watussi_log.url_id ORDER BY date DESC LIMIT 20;';
	$res = $dbh->query($qry);
	echo "<table id='beautiful-tab'>";
	echo "<thead></thead><tr><th>URL</th><th>Res Code</th><th>Response Time</th><th>Date</th></tr></thead>";
	while($row = $res->fetch(PDO::FETCH_OBJ)){
		$url = $row->url;
		$res_code = $row->res_code;
		$date = $row->date;
		$ecart = get_ecart($date);
		$response_time = number_format($row->response_time, 0, ' ', ' ');
		echo "<tr"; if(($res_code !='200')&&($res_code !='304')){ echo  " class='error'";}else{ echo " class='ok'";} echo " ><td>$url</td><td>$res_code</td><td>$response_time</td><td>(il y a $ecart)</td></tr>";
	}
	echo "</table>";
	
}

// Dernières pages visitées
if($mode == 1){
	$qry = 'SELECT url, res_code, keyword, date, response_time 
			FROM watussi_log, watussi_url, watussi_keywords 
			WHERE type_id = 1 
			AND watussi_url.url_id = watussi_log.url_id 
			AND watussi_log.keyword_id = watussi_keywords.keyword_id
			ORDER BY date 
			DESC LIMIT 20;';
	$res = $dbh->query($qry);
	echo "<table id='beautiful-tab'>";
	echo "<thead></thead><tr><th>URL</th><th>Keyword</th><th>Res Code</th><th>Response Time</th><th>Date</th></tr></thead>";
	while($row = $res->fetch(PDO::FETCH_OBJ)){
		$url = $row->url;
		$res_code = $row->res_code;
		$date = $row->date;
		$keyword = $row->keyword;
		$ecart = get_ecart($date);
		$response_time = number_format($row->response_time, 0, ' ', ' ');
		echo "<tr"; if(($res_code !='200')&&($res_code !='304')){ echo  " class='error'";}else{ echo " class='ok'";} echo " ><td>$url</td><td>$keyword</td><td>$res_code</td><td>$response_time</td><td>(il y a $ecart)</td></tr>";
	}
	echo "</table>";
}






$dbh = null;

?>

</body>

</html>

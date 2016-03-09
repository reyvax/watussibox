<?php

/* analyse.php
 * Première version : 26 novembre 2012
 * Dernière modification : 2 décembre 2012
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

//login();


echo heure_minute_seconde() . ' - DEBUT' . '<br />';

/*
.---------------------------------------------------------------------------.
|  Calcul des KPIs                                                          |
'---------------------------------------------------------------------------'
*/

// Nb URLs
echo heure_minute_seconde() . ' - CALCUL NB URLS' . '<br />';
$qry = 'SELECT COUNT(*) AS nb FROM watussi_url';
$res = $dbh->query($qry);
$row = $res->fetch(PDO::FETCH_OBJ);
$nb_urls = $row->nb;

// Nb Crawl / 30 j
echo heure_minute_seconde() . ' - CALCUL NB CRAWL / 30 JOURS' . '<br />';
$qry = 'SELECT COUNT(DISTINCT(url_id)) AS nb FROM watussi_log WHERE type_id = 0 AND watussi_log.date_id IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 30 DAY);';
$res = $dbh->query($qry);
$row = $res->fetch(PDO::FETCH_OBJ);
$nb_crawl_30j = $row->nb;

// Nb pa / 30 j
echo heure_minute_seconde() . ' - CALCUL NB PA / 30 JOURS' . '<br />';
$qry = 'SELECT COUNT(DISTINCT(url_id)) AS nb FROM watussi_log WHERE type_id = 1 AND watussi_log.date_id IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 30 DAY);';
$res = $dbh->query($qry);
$row = $res->fetch(PDO::FETCH_OBJ);
$nb_pa_30j = $row->nb;

// Nb nouvelles pages / 30j
echo heure_minute_seconde() . ' - CALCUL NOUVELLES PAGES / 30 JOURS' . '<br />';
$qry = 'SELECT COUNT(*) AS nb FROM watussi_url WHERE first_crawl IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 30 DAY);';
$res = $dbh->query($qry);
$row = $res->fetch(PDO::FETCH_OBJ);
$nouvelles_pages_30j = $row->nb;

// Temps execution moyen
echo heure_minute_seconde() . ' - CALCUL TEMPS EXECUTION' . '<br />';
$qry = 'SELECT AVG(response_time) AS nb FROM watussi_log WHERE watussi_log.date_id IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 30 DAY) AND type_id = 0;';
$res = $dbh->query($qry);
$row = $res->fetch(PDO::FETCH_OBJ);
$avg_execution_time = $row->nb;

// ENREGISTREMENT
$qry = "INSERT INTO watussi_kpi(kpi_id, date, nb_urls, nb_crawl_30j, nb_pa_30j, new_pages_30j, avg_execution_time)
					VALUES('', NOW(), '$nb_urls', '$nb_crawl_30j', '$nb_pa_30j', '$nouvelles_pages_30j', '$avg_execution_time')";
					
$dbh->query($qry);



/*
.---------------------------------------------------------------------------.
|  EVENEMENT UNIQUE / JOUR                                                  |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - EVENEMENT UNIQUE / JOUR' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_evenement_unique_jour';
$dbh->query($qry);

$qry = 'SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY ORDER BY date;';
$res = $dbh->query($qry);

while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date_id = $row->date_id;
	$qry = "SELECT COUNT(DISTINCT(url_id)) AS nb, type_id FROM watussi_log WHERE watussi_log.date_id = $date_id GROUP BY type_id";
	$res2 = $dbh->query($qry);
	while($row2 = $res2->fetch(PDO::FETCH_OBJ)){
		$nb = $row2->nb;
		$type_id = $row2->type_id;
		$qry = "INSERT INTO watussi_tmp_evenement_unique_jour(date_id, nb, type_id) VALUES('$date_id', '$nb', '$type_id')";
		$dbh->query($qry);
	}
}

/*
.---------------------------------------------------------------------------.
|  EVENEMENT / JOUR                                                  |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - EVENEMENT / JOUR' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_evenement_jour';
$dbh->query($qry);

$qry = 'SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY ORDER BY date;';
$res = $dbh->query($qry);

while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date_id = $row->date_id;
	$qry = "SELECT COUNT(url_id) AS nb, type_id FROM watussi_log WHERE watussi_log.date_id = $date_id GROUP BY type_id";
	$res2 = $dbh->query($qry);
	while($row2 = $res2->fetch(PDO::FETCH_OBJ)){
		$nb = $row2->nb;
		$type_id = $row2->type_id;
		$qry = "INSERT INTO watussi_tmp_evenement_jour(date_id, nb, type_id) VALUES('$date_id', '$nb', '$type_id')";
		$dbh->query($qry);
	}
}

/*
.---------------------------------------------------------------------------.
|  EVENEMENT UNIQUE / 30 JOURS                                              |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - EVENEMENT UNIQUE / 30 JOURS' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_evenement_unique_30j';
$dbh->query($qry);

$qry = 'SELECT date_id, date FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY ORDER BY date;';
$res = $dbh->query($qry);

while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date = $row->date;
	$date_id = $row->date_id;
	$qry = "SELECT COUNT(DISTINCT(url_id)) AS nb, type_id FROM watussi_log WHERE watussi_log.date_id IN (SELECT date_id FROM watussi_date WHERE date >= '$date' - INTERVAL 30 DAY AND date <= '$date') GROUP BY type_id";
	$res2 = $dbh->query($qry);
	while($row2 = $res2->fetch(PDO::FETCH_OBJ)){
		$nb = $row2->nb;
		$type_id = $row2->type_id;
		$qry = "INSERT INTO watussi_tmp_evenement_unique_30j(date_id, nb, type_id) VALUES('$date_id', '$nb', '$type_id')";
		$dbh->query($qry);
	}
}

/*
.---------------------------------------------------------------------------.
|  EVENEMENT / 30 JOURS                                              |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - EVENEMENT / 30 JOURS' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_evenement_30j';
$dbh->query($qry);

$qry = 'SELECT date_id, date FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY ORDER BY date;';
$res = $dbh->query($qry);

while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date = $row->date;
	$date_id = $row->date_id;
	$qry = "SELECT COUNT(url_id) AS nb, type_id FROM watussi_log WHERE watussi_log.date_id IN (SELECT date_id FROM watussi_date WHERE date >= '$date' - INTERVAL 30 DAY AND date <= '$date') GROUP BY type_id";
	$res2 = $dbh->query($qry);
	while($row2 = $res2->fetch(PDO::FETCH_OBJ)){
		$nb = $row2->nb;
		$type_id = $row2->type_id;
		$qry = "INSERT INTO watussi_tmp_evenement_30j(date_id, nb, type_id) VALUES('$date_id', '$nb', '$type_id')";
		$dbh->query($qry);
	}
}

/*
.---------------------------------------------------------------------------.
|  CRAWL GLOBAL / HEURE                                                     |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - EVENEMENT GLOBAL / HEURE' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_evenement_global_heure';
$dbh->query($qry);

$qry = 'SELECT heure, type_id, COUNT(*) AS nb FROM watussi_log WHERE date_id IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY) GROUP BY heure, type_id;';
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$heure = $row->heure;
	$type_id = $row->type_id;
	$nb = $row->nb;
	$qry = "INSERT INTO watussi_tmp_evenement_global_heure(heure, nb, type_id) VALUES('$heure', '$nb', '$type_id');";
	$dbh->query($qry);
}


/*
.---------------------------------------------------------------------------.
|  CRAWL / RES CODE                                                         |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - EVENEMENT / RES CODE' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_evenement_res_code';
$dbh->query($qry);

$qry = 'SELECT date_id, type_id, res_code, COUNT(*) AS nb FROM watussi_log WHERE date_id IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY) GROUP BY date_id, type_id, res_code;';
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date_id = $row->date_id;
	$type_id = $row->type_id;
	$res_code = $row->res_code;
	$nb = $row->nb;
	$qry = "INSERT INTO watussi_tmp_evenement_res_code(date_id, nb, type_id, res_code) VALUES('$date_id', '$nb', '$type_id', '$res_code');";
	$dbh->query($qry);
}


/*
.---------------------------------------------------------------------------.
|  NOUVELLES PAGES / JOUR                                                   |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - NOUVELLES PAGES / JOUR' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_nouvelles_pages';
$dbh->query($qry);

$qry = 'SELECT first_crawl, COUNT(*) AS nb FROM watussi_url WHERE first_crawl IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY) GROUP BY first_crawl;';
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$first_crawl = $row->first_crawl;
	$nb = $row->nb;
	$qry = "INSERT INTO watussi_tmp_nouvelles_pages(date_id, nb) VALUES('$first_crawl', '$nb');";
	$dbh->query($qry);
}

/*
.---------------------------------------------------------------------------.
|  PAGES ACTIVES / JOUR                                                     |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - PAGES ACTIVES / JOUR' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_pa_jour';
$dbh->query($qry);

$qry = 'SELECT date_id, COUNT(DISTINCT(url_id)) AS nb FROM watussi_log WHERE type_id = 1 AND date_id IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY) GROUP BY date_id;';
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date_id = $row->date_id;
	$nb = $row->nb;
	$qry = "INSERT INTO watussi_tmp_pa_jour(date_id, nb) VALUES('$date_id', '$nb');";
	$dbh->query($qry);
}

/*
.---------------------------------------------------------------------------.
|  PAGES ACTIVES / 30 JOURS                                                     |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - PAGES ACTIVES / 30 JOURS' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_pa_30j';
$dbh->query($qry);

$qry = 'SELECT date_id, date FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY ORDER BY date;';
$res = $dbh->query($qry);

while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date_id = $row->date_id;
	$date = $row->date;
	$qry = "SELECT COUNT(DISTINCT(url_id)) AS nb FROM watussi_log WHERE type_id = 1 AND date_id IN (SELECT date_id FROM watussi_date WHERE date <= '$date' AND date >= '$date' - INTERVAL 30 DAY);";
	$res2 = $dbh->query($qry);
	while($row2 = $res2->fetch(PDO::FETCH_OBJ)){
		$nb = $row2->nb;
		$qry = "INSERT INTO watussi_tmp_pa_30j(date_id, nb) VALUES('$date_id', '$nb');";
		$dbh->query($qry);
	}
}

/*
.---------------------------------------------------------------------------.
|  VOLUME ACTIVE / NON ACTIVE                                               |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - ACTIVE / NON ACTIVE' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_active_nonactive';
$dbh->query($qry);

$qry = 'SELECT active, COUNT(*) AS nb FROM watussi_url GROUP BY active;';
$res = $dbh->query($qry);
$active = $non_active = 0;
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$tmp = $row->active;
	$nb = $row->nb;
	if($tmp == 1){
		$active = $nb;
	}
	else{
		$non_active = $nb;
	}
}

$qry = "INSERT INTO watussi_tmp_active_nonactive(active, non_active) VALUES('$active', '$non_active');";
$dbh->query($qry);



/*
.---------------------------------------------------------------------------.
|  PPC                                                                      |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - PPC' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_ppc';
$dbh->query($qry);

$qry = 'SELECT date_id, date FROM watussi_date ORDER BY date;';
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date = $row->date;
	$date_id = $row->date_id;
	$qry = "SELECT COUNT(DISTINCT(url_id)) AS nb FROM watussi_log WHERE date_id IN (SELECT date_id FROM watussi_date WHERE date <= '$date')";
	$res2 = $dbh->query($qry);
	$row2 = $res2->fetch(PDO::FETCH_OBJ);
	$nb = $row2->nb;
	$qry = "INSERT INTO watussi_tmp_ppc(date_id, nb) VALUES('$date_id', '$nb');";
	$dbh->query($qry);
}


/*
.---------------------------------------------------------------------------.
|  TEMPS EXECUTION MOYEN                                                    |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - TEMPS EXECUTION MOYEN' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_response_time';
$dbh->query($qry);

$qry = "SELECT date_id, AVG(response_time) AS response_time FROM watussi_log WHERE type_id = 0 AND date_id IN (SELECT date_id FROM watussi_date WHERE date >= '$date' - INTERVAL 90 DAY) GROUP BY date_id;";
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$date_id = $row->date_id;
	$response_time = $row->response_time;
	$qry = "INSERT INTO watussi_tmp_response_time(date_id, response_time) VALUES('$date_id', '$response_time')";
	$dbh->query($qry);
}

/*
.---------------------------------------------------------------------------.
|  CRAWL UTILE / INUTILE                                                    |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - CRAWL UTILE / INUTILE' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_crawl_utile_inutile';
$dbh->query($qry);

$qry = "SELECT active, date_id, COUNT(*) AS nb FROM watussi_log, watussi_url WHERE type_id = 0 AND watussi_url.url_id = watussi_log.url_id AND date_id IN (SELECT date_id FROM watussi_date WHERE date >= NOW() - INTERVAL 90 DAY) GROUP BY date_id, active;";
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$utile = $row->active;
	$date_id = $row->date_id;
	$nb = $row->nb;
	$qry = "INSERT INTO watussi_tmp_crawl_utile_inutile(date_id, utile, nb) VALUES('$date_id', '$utile', '$nb');";
	$dbh->query($qry);	
}

/*
.---------------------------------------------------------------------------.
|  REPARTITION LAST RES CODE                                                |
'---------------------------------------------------------------------------'
*/

echo heure_minute_seconde() . ' - REPARTITION DES RES CODE' . '<br />';
$qry = 'TRUNCATE TABLE watussi_tmp_rescode';
$dbh->query($qry);

$qry = 'SELECT last_res_code, COUNT(*) AS nb FROM watussi_url GROUP BY last_res_code;';
$res = $dbh->query($qry);
while($row = $res->fetch(PDO::FETCH_OBJ)){
	$nb = $row->nb;
	$res_code = $row->last_res_code;
	$qry = "INSERT INTO watussi_tmp_rescode(res_code, nb) VALUES('$res_code', '$nb');";
	$dbh->query($qry);
}



echo heure_minute_seconde() . ' - FIN' . '<br />';

$timeend = microtime(true);
$time = $timeend - $timestart;
$page_load_time = number_format($time, 3);
echo "<br /><br />Ex&eacute;cut&eacute; en " . $page_load_time . " s.";

$dbh = null; ?>
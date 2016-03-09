<?php

/* watussi_track.php
 * Première version : 25 novembre 2012
 * Dernière modification : 3 décembre 2012
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


include_once('inc/config.inc.php');
include_once('inc/fonctions.inc.php');


$link = mysql_connect($CONFIG_TAB['host'], $CONFIG_TAB['login_db'], $CONFIG_TAB['password_db']);
mysql_select_db($CONFIG_TAB['database']);

$adresse_ip = $_SERVER['REMOTE_ADDR'];


if(isset($_SERVER['HTTP_REFERER'])){
	$referer = $_SERVER['HTTP_REFERER'];
}
else{
	$referer = '';
}


$user_agent = $_SERVER['HTTP_USER_AGENT'];
$url = dirname(strtolower($_SERVER['SERVER_PROTOCOL'])) . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

//$user_agent = 'fsdfsdfsdfGooglebotfdsfsdfsdf';
//$adresse_ip = '66.249.3.3';
//$referer = 'http://www.google.fr/search?q=voiture%20renault+rouge&hl=fr&client=safari&prmd=imvns&ei=ihjvToTnM4SzhAfxw_HMCA&start=10&sa=N&biw=320&bih=416';

add_log($adresse_ip, $referer, $user_agent, $url, $RES_CODE, $RESPONSE_TIME);



mysql_close($link);

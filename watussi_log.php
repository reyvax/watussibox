#!/usr/bin/php
<?php

/* watussi_log.php
 * Première version : 30 novembre 2012
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

$stdin = fopen("php://stdin", "r");
ob_implicit_flush (true); // Use unbuffered output
while ($line = fgets ($stdin))
{
    $tmp = explode('|||', $line);
    $adresse_ip = $tmp[0];
    $referer = $tmp[1];
    $user_agent = $tmp[2];
    $dom = $tmp[3];
    
    $path = $tmp[4];
    
    $tmp2 = explode(" ", $path);
    $path = $tmp2[1];
    
    $url = $dom . $path;
    
    $RES_CODE = $tmp[5];
    $RESPONSE_TIME = $tmp[6];
    
    add_log($adresse_ip, $referer, $user_agent, $url, $RES_CODE, $RESPONSE_TIME);
}





mysql_close($link);

<?php

/* tab-crawl.php
 * Première version : 2 décembre 2012
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


include_once('../inc/connect.inc.php');
include_once('../inc/fonctions.inc.php');
include_once('../inc/graph.inc.php');

login();
?>



<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		
		


<?php 
	echo get_crawl_unique_quotidien('crawl_unique_quotidien'); 
	echo get_crawl_unique_30j('crawl_unique_30j');
	echo get_crawl_global_heure('crawl_global_heure');
	echo get_crawl_utile_inutile('utile_inutile');
?>



	</head>
	<body>
		
		<div id="crawl_unique_quotidien" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
		<div id="crawl_unique_30j" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
		<div id="crawl_global_heure" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
		<div id="utile_inutile" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

	</body>
</html>

<?php $dbh = null; ?>
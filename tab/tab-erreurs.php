<?php

/* tab-erreurs.php
 * Première version : 8 décembre 2012
 * Dernière modification : 8 décembre 2012
.---------------------------------------------------------------------------.
|  Software: WatussiBox Free                                                |
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
		

		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				oTable = $('#last_erreurs').dataTable({
						"bJQueryUI": true,
						"iDisplayLength": 50,
						"sPaginationType": "full_numbers"
				});
            } );          
         </script>





	</head>
	<body>
		
		<h2>1000 dernières erreurs</h2>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="last_erreurs">
			<thead>
				<tr>
					<th>URL</th>
					<th>Res Code</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$qry = "SELECT date, url, last_res_code FROM watussi_url, watussi_date WHERE watussi_url.last_crawl = watussi_date.date_id AND last_res_code != 200 AND last_res_code != 304 ORDER BY date DESC LIMIT 1000";
					$res = $dbh->query($qry);
					while($row = $res->fetch(PDO::FETCH_OBJ)){
						$date = $row->date;
						$url = $row->url;
						$last_res_code = $row->last_res_code;
						echo "<tr><td>$url</td><td>$last_res_code</td><td>$date</td></tr>";
					}
				?>
			</tbody>
		</table>
		


	</body>
</html>

<?php $dbh = null; ?>
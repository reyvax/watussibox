<?php

/* index.php
 * Première version : 2 décembre 2012
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

include_once('inc/fonctions.inc.php');
login();


?>

<!doctype html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<title>WatussiBox</title>
	<link href="jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<script src="jquery-ui/js/jquery-1.8.3.js"></script>
	<script src="jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
    <script>
    $(function() {
        $( "#tabs" ).tabs({
			disabled:[2,3,7,11,12,13,16,17,18,19,20,21],
            beforeLoad: function( event, ui ) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                        "Oupsss.... ce n'était pas prévu...." );
                });
            }
        });
    });
    
    
    </script>

	<script src="js/highcharts-exporting.js"></script>
	<script src="js/highcharts.js"></script>
	<link rel="stylesheet" type="text/css" href="css/demo_table_jui.css" />
	<link rel="stylesheet" type="text/css" href="css/demos.css" />
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>	

	<style>
	body{
		font: 62.5% "Trebuchet MS", sans-serif;
		margin: 50px;
	}
	

	</style>
</head>
<body>
	

<?php
	include_once('inc/connect.inc.php');	
	include_once('inc/graph.inc.php');

?>


<!-- Tabs -->
<div id="tabs">
	<ul>
		<li><a href="#tab-actu">Actu</a></li>
		<li><a href="#tab-kpi">KPI</a></li>
		<li><a href="#">Catégories</a></li>
		<li><a href="#">Crawl vs Logs</a></li>
		<li><a href="tab/tab-crawl.php">Crawl</a></li>
		<li><a href="tab/tab-res-code.php">Res Code</a></li>
		<li><a href="tab/tab-erreurs.php">Erreurs</a></li>
		<li><a href="#">Volume d'URLs</a></li>
		<li><a href="tab/tab-nouvelles-pages.php">Nouvelles Pages</a></li>
		<li><a href="tab/tab-visites.php">Visites</a></li>
		<li><a href="tab/tab-pa.php">Pages Actives</a></li>
		<li><a href="#">Pages Perdues</a></li>
		<li><a href="#">Pages Utiles / Inutiles</a></li>
		<li><a href="#">Mots clés</a></li>
		<li><a href="tab/tab-fenetre-crawl.php">Fenêtre de crawl / PPC</a></li>
		<li><a href="tab/tab-temps-execution.php">Temps d'exécution</a></li>
		<li><a href="#">Top / Bad URLs</a></li>
		<li><a href="#">Linking Externe</a></li>
		<li><a href="#">Facebook</a></li>
		<li><a href="#">Twitter</a></li>
		<li><a href="#">Ranking</a></li>
		<li><a href="#">Historique</a></li>
		<li><a href="tab/tab-realtime.php">Real Time</a></li>
	</ul>
	
	
	<div id="tab-actu">
		
		<?php
			// On vérifie que la table log n'est pas vide
			$qry = 'SELECT COUNT(*) AS nb FROM watussi_log;';
			$res = $dbh->query($qry);
			$row = $res->fetch(PDO::FETCH_OBJ);
			$nb = $row->nb;
			if($nb == 0){
		?>
		
		<div class="ui-widget">
			<div style="padding: 0 .7em;" class="ui-state-error ui-corner-all">
				<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-alert"></span>
				<strong>Attention :</strong> Nous n'avons pour le moment enregistré aucun évènement. Avez-vous bien configuré le tracker ?</p>
			</div>
		</div>
		
		<?php
			}
			
			// On regarde si il y a déjà eu une analyse
			$qry = 'SELECT COUNT(*) AS nb FROM watussi_kpi;';
			$res = $dbh->query($qry);
			$row = $res->fetch(PDO::FETCH_OBJ);
			$nb = $row->nb;
			if($nb == 0){
		?>
		
		<div class="ui-widget">
			<div style="padding: 0 .7em;" class="ui-state-error ui-corner-all">
				<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-alert"></span>
				<strong>Attention :</strong> Vous n'avez lancé aucune analyse pour le moment. <a href="analyse.php" target="_blank">Cliquez-ici</a> pour lancer une analyse <i>(peut prendre quelques minutes)</i></p>
			</div>
		</div>
		
		<?php
			}
			
			// On regarde de quand date la dernière analyse
			// Merci à http://forum.phpfrance.com/
			else{
				$qry = 'SELECT MAX(date) AS date FROM watussi_kpi;';
				$res = $dbh->query($qry);
				$row = $res->fetch(PDO::FETCH_OBJ);
				$date = $row->date;				
				
				$jour = substr($date, 8, 2);
				$mois = substr($date, 5, 2);
				$annee = substr($date, 0, 4);

				$timestamp = mktime(0, 0, 0, $mois, $jour, $annee);				 
				$maintenant = time(); 
				$ecart_secondes = $maintenant - $timestamp; 
				$ecart_jours = floor($ecart_secondes / (60 * 60 * 24)); 
				
				if($ecart_jours > 0){
			?>
			
				<div class="ui-widget">
					<div style="margin-top: 20px; padding: 0 .7em;" class="ui-state-highlight ui-corner-all">
						<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
						<strong>Bonjour, </strong>votre dernière analyse remonte à <?php echo $ecart_jours; ?> jour(s). <a href="analyse.php" target="_blank">Cliquez-ici</a> pour lancer une analyse <i>(peu prendre quelques minutes)</i></p>
					</div>
				</div>
			
			
			<?php
				}
			}
			
		?>
		
		<iframe src="http://www.watussi.fr/actu_watussibox.php" width="100%" height="400" style='border:0px;'></iframe>
	</div>
	
	<div id="tab-kpi" style="height:250px;">
		
	<?php if($nb > 0): ?>
		
		<?php
			$qry = "SELECT * FROM watussi_kpi ORDER BY kpi_id DESC LIMIT 1;";
			$res = $dbh->query($qry);
			$row = $res->fetch(PDO::FETCH_OBJ);
			
			$nb_urls = $row->nb_urls;
			$nb_crawl_30j = $row->nb_crawl_30j;
			
			$tx_crawl_30j = round($nb_crawl_30j / $nb_urls * 100, 2);
			
			$nb_pa_30j = $row->nb_pa_30j;
			
			$tx_pa_30j = round($nb_pa_30j / $nb_urls * 100, 2);
			
			$new_pages_30j = $row->new_pages_30j;
			
			$avg_execution_time = $row->avg_execution_time;
		?>
		
		<div class="ui-widget" style="width:250px; float:left; margin:15px;">
			<div class="ui-widget-header ui-corner-top">
				<h3 align="center">Nombre d'URLs</h3>
			</div>
			<div class="ui-widget-content ui-corner-bottom">
				<h1 align="center"><?php echo number_format($nb_urls, 0, ' ', ' '); ?></h1>						
			</div>
		</div>
		
		<div class="ui-widget" style="width:250px; float:left; margin:15px;">
			<div class="ui-widget-header ui-corner-top">
				<h3 align="center">Nb crawl / 30j</h3>
			</div>
			<div class="ui-widget-content ui-corner-bottom">
				<h1 align="center"><?php echo number_format($nb_crawl_30j, 0, ' ', ' '); ?></h1>						
			</div>
		</div>
		
		<div class="ui-widget" style="width:250px; float:left; margin:15px;">
			<div class="ui-widget-header ui-corner-top">
				<h3 align="center">Tx crawl / 30j</h3>
			</div>
			<div class="ui-widget-content ui-corner-bottom">
				<h1 align="center"><?php echo $tx_crawl_30j; ?> %</h1>						
			</div>
		</div>
		
		<div class="ui-widget" style="width:250px; float:left; margin:15px;">
			<div class="ui-widget-header ui-corner-top">
				<h3 align="center">Nb pa / 30j</h3>
			</div>
			<div class="ui-widget-content ui-corner-bottom">
				<h1 align="center"><?php echo number_format($nb_pa_30j, 0, ' ', ' '); ?></h1>						
			</div>
		</div>
		
		<div class="ui-widget" style="width:250px; float:left; margin:15px;">
			<div class="ui-widget-header ui-corner-top">
				<h3 align="center">Tx pa / 30j</h3>
			</div>
			<div class="ui-widget-content ui-corner-bottom">
				<h1 align="center"><?php echo $tx_pa_30j; ?> %</h1>						
			</div>
		</div>
		
		<div class="ui-widget" style="width:250px; float:left; margin:15px;">
			<div class="ui-widget-header ui-corner-top">
				<h3 align="center">Nouvelles pages / 30j</h3>
			</div>
			<div class="ui-widget-content ui-corner-bottom">
				<h1 align="center"><?php echo number_format($new_pages_30j, 0, ' ', ' '); ?></h1>						
			</div>
		</div>
		
		<div class="ui-widget" style="width:250px; float:left; margin:15px;">
			<div class="ui-widget-header ui-corner-top">
				<h3 align="center">Temps Ex&eacute;cution moyen</h3>
			</div>
			<div class="ui-widget-content ui-corner-bottom">
				<h1 align="center"><?php echo number_format($avg_execution_time, 0, ' ', ' '); ?></h1>						
			</div>
		</div>
		
		<?php endif; ?>
		
	</div>
</div>



</body>
</html>

<?php $dbh = null; ?>

<?php

/* tab-realtime.php
 * Première version : 2 décembre 2012
 * Dernière modification : 4 décembre 2012
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
		
		
		<script>
		 $(document).ready(function() {
		   $("#last_crawl").load("api_realtime.php?mode=0");
		   var refreshId = setInterval(function() {
			  $("#last_crawl").load("api_realtime.php?mode=0");
		   }, 3000);		   
		   
		   $("#last_visites").load("api_realtime.php?mode=1");
		   var refreshId = setInterval(function() {
			  $("#last_visites").load("api_realtime.php?mode=1");
		   }, 3000);
		   $.ajaxSetup({ cache: false });		   
		});
		</script>
		
		<!-- Nb pages crawlées 60 dernières secondes -->
		<script type="text/javascript">
		var chart2; // global

		function requestData2() {
			$.ajax({
				url: 'api_realtime.php?mode=2', 
				success: function(point) {
					var series = chart2.series[0],
						shift = series.data.length > 100; // shift if the series is longer than 20
		
					// add the point
					chart2.series[0].addPoint(eval(point), true, shift);
					
					// call it again after one second
					setTimeout(requestData2, 6000);	
				},
				cache: false
			});
		}
			
		$(document).ready(function() {
			chart2 = new Highcharts.Chart({
				chart: {
					renderTo: 'nb_crawl',
					defaultSeriesType: 'spline',
					events: {
						load: requestData2
					}
				},
				title: {
					text: 'Nb pages crawlées dans les 60 dernières secondes'
				},
				xAxis: {
					type: 'datetime',
					tickPixelInterval: 150,
					maxZoom: 20 * 1000
				},
				yAxis: {
					minPadding: 0.2,
					maxPadding: 0.2,
					title: {
						text: 'Nb',
						margin: 80
					}
				},
				series: [{
					name: '',
					data: []
				}]
			});		
		});
		</script>
		



	</head>
	
<body>
	
<div id="nb_crawl" style="width: 100%; height: 400px; margin: 0 auto"></div>


 
<h2>Derni&egrave;res pages crawl&eacute;es (Googlebot)</h2>
<div id="last_crawl"></div>


		<!-- Nb pages visitées 60 dernières secondes -->
		
		<script type="text/javascript">
		var chart; // global

		function requestData() {
			$.ajax({
				url: 'api_realtime.php?mode=3', 
				success: function(point) {
					var series = chart.series[0],
						shift = series.data.length > 100; // shift if the series is longer than 20
		
					// add the point
					chart.series[0].addPoint(eval(point), true, shift);
					
					// call it again after one second
					setTimeout(requestData, 6000);	
				},
				cache: false
			});
		}
			
		$(document).ready(function() {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'nb_visites',
					defaultSeriesType: 'spline',
					events: {
						load: requestData
					}
				},
				title: {
					text: 'Nb pages visitées dans les 60 dernières secondes'
				},
				xAxis: {
					type: 'datetime',
					tickPixelInterval: 150,
					maxZoom: 20 * 1000
				},
				yAxis: {
					minPadding: 0.2,
					maxPadding: 0.2,
					title: {
						text: 'Nb',
						margin: 80
					}
				},
				series: [{
					name: '',
					data: []
				}]
			});		
		});
		</script>




<div id="nb_visites" style="width: 100%; height: 400px; margin: 0 auto"></div>

<h2>Derni&egrave;res pages visit&eacute;es (From Google)</h2>
<div id="last_visites"></div>


</body>


</html>

<?php $dbh = null; ?>
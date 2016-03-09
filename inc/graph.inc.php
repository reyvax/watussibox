<?php

/* graph.inc.php
 * Première version : 1er décembre 2012
 * Dernière modification : 1er décembre 2012
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

function get_pie($div_container, $tab_data, $title='Default'){
	
	
	$data = '';
	foreach($tab_data as $tmp_data){
		$data .= json_encode($tmp_data) . ',';
	}
	
	$res = "		<script type='text/javascript'>
		
		$(function () {
    var chart;
    $(document).ready(function() {
        
     
        
        // Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: '" . $div_container . "',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '" . $title . "'
            },
            tooltip: {
                pointFormat: '<b>{point.percentage}%</b>',
                percentageDecimals: 2
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage*100)/100 +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                data: [
                    " . $data . "
                ]
            }]
        });
    });
    
});
		
		
		</script>";
		
	return($res);
	
}

function get_stacked_area($div_container, $x_tab, $serie_tab, $title='Default', $subtitle='', $type='area'){
	$res = "	<script type='text/javascript'>
$(function () {
	
       
	
	
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: '" . $div_container . "',
                zoomType: 'x',
                type: '" . $type . "'
            },
            title: {
                text: '" . $title . "'
            },
            subtitle: {
                text: '" . $subtitle . "'
            },
            xAxis: {
                categories: " . json_encode($x_tab) .",
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: 'Nb'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+
                        this.x +'</b><br />'+ this.y;
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: [{
                data: " . json_encode($serie_tab) . "
            }, ]
        });
    });
    
});
		</script>";
		
		return($res);
}

function get_stacked_areas($div_container, $x_tab, $series_tab, $title, $subtitle=''){
	
	
	$series = '';
	foreach($series_tab as $res_code=>$tab_value){
		$series .= "{name:'$res_code', data: " . json_encode($tab_value) . "},";
	}
	
	
	$res = "<script type='text/javascript'>

$(function () {
    var chart;
    $(document).ready(function() {
		
	
		
        chart = new Highcharts.Chart({
            chart: {
                renderTo: '" . $div_container ."',
                type: 'area',
                zoomType: 'x',
            },
            title: {
                text: '" . $title . "'
            },
            subtitle: {
                text: '" . $subtitle . "'
            },
            xAxis: {
                categories:" . json_encode($x_tab) . ",
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
           yAxis: {
                title: {
                    text: 'Nb'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+
                        this.x +'</b><br />'+ this.series.name + ' : ' + this.y + ' (' + Highcharts.numberFormat(this.percentage, 2) + ' %)';
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: ["
            
            . $series .            
         
            "]
        });
    });
    
});

</script>";

return($res);
}


function get_crawl_unique_quotidien($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_evenement_unique_jour wt
			WHERE wd.date_id = wt.date_id
			AND type_id = 0
			ORDER BY date;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->date;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Crawl unique quotidien', 'Googlebot'));	
	
}

function get_visites_quotidien($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_evenement_jour wt
			WHERE wd.date_id = wt.date_id
			AND type_id = 1
			ORDER BY date;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->date;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Visites / jour', 'From Google'));	
	
}

function get_visites_30j($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_evenement_30j wt
			WHERE wd.date_id = wt.date_id
			AND type_id = 1
			ORDER BY date;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->date;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Visites / 30j', 'From Google'));	
	
}

function get_pa_jour($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_pa_jour wt
			WHERE wd.date_id = wt.date_id
			ORDER BY date;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->date;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Pages actives / jour'));	
	
}

function get_pa_30j($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_pa_30j wt
			WHERE wd.date_id = wt.date_id
			ORDER BY date;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->date;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Pages actives / 30j'));	
	
}

function get_crawl_unique_30j($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_evenement_unique_30j wt
			WHERE wd.date_id = wt.date_id
			AND type_id = 0
			ORDER BY date;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->date;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Crawl unique / 30 jours', 'Googlebot'));	
	
}

function get_nouvelles_pages($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_nouvelles_pages wt
			WHERE wd.date_id = wt.date_id
			ORDER BY date;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->date;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Nouvelles pages', ''));	
	
}

function get_crawl_global_heure($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT heure, nb FROM watussi_tmp_evenement_global_heure WHERE type_id = 0 ORDER BY heure;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->heure;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Crawl global / heure', 'Googlebot'));	
}

function get_visites_global_heure($div_container){
	$serie_tab = array();
	$x_tab = array();
	
	$qry = 'SELECT heure, nb FROM watussi_tmp_evenement_global_heure WHERE type_id = 1 ORDER BY heure;';

	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$serie_tab[] = intval($row->nb);
		$x_tab[] = $row->heure;
	}
	
	return(get_stacked_area($div_container, $x_tab, $serie_tab, 'Visites global / heure', 'From Google'));	
}

function get_active_nonactive($div_container){
	$qry = 'SELECT active, non_active FROM watussi_tmp_active_nonactive;';
	$res = mysql_query($qry);
	$row = mysql_fetch_object($res);
	$active = intval($row->active);
	$non_active = intval($row->non_active);
	
	$tab_data = array();
	$tab_data[] = array('Active', $active);	
	$tab_data[] = array('Non active', $non_active);		
	
	return(get_pie($div_container, $tab_data, 'Répartition pages actives / non-active'));
}

function get_last_res_code($div_container){
	$qry = 'SELECT res_code, nb FROM watussi_tmp_rescode;';
	$res = mysql_query($qry);
	$tab_data = array();
	
	while($row = mysql_fetch_object($res)){
		$res_code = $row->res_code;
		$nb = intval($row->nb);
		$tab_data[] = array($res_code, $nb);
	}
	
		
	
	return(get_pie($div_container, $tab_data, 'Last res_code'));
}

function get_crawl_utile_inutile($div_container){
	$x_tab = array();
	$series_tab = array();
	$qry = 'SELECT DISTINCT(wt.date_id), date FROM watussi_tmp_crawl_utile_inutile wt, watussi_date wd
			WHERE wt.date_id = wd.date_id			
			ORDER BY date;';
			
	$res = mysql_query($qry);
	$tab_utile = array();
	$tab_inutile = array();
	while($row = mysql_fetch_object($res)){
		$date = $row->date;
		$date_id = $row->date_id;
		$x_tab[] = $date;
		
		$qry = "SELECT nb FROM watussi_tmp_crawl_utile_inutile WHERE date_id = '$date_id' AND utile = 1;";
		$res2 = mysql_query($qry);
		$row2 = mysql_fetch_object($res2);
		$tab_utile[] = intval($row2->nb);
		
		$qry = "SELECT nb FROM watussi_tmp_crawl_utile_inutile WHERE date_id = '$date_id' AND utile = 0;";
		$res2 = mysql_query($qry);
		$row2 = mysql_fetch_object($res2);
		$tab_inutile[] = intval($row2->nb);
	}
	
	$series_tab['Utile'] = $tab_utile;
	$series_tab['Inutile'] = $tab_inutile;
	
	return(get_stacked_areas($div_container, $x_tab, $series_tab, 'Crawl utile / inutile', 'Googlebot'));
}

function get_evenement_res_code($div_container, $type_id){
	
	// On récupère toutes les dates
	$qry = "SELECT DISTINCT(date_id) AS date_id FROM watussi_tmp_evenement_res_code WHERE type_id = $type_id";
	$res = mysql_query($qry);
	$dateid_tab = array();
	while($row = mysql_fetch_object($res)){
		$dateid_tab[] = intval($row->date_id);
	}
	
	// On récupère tous les res code
	$qry = "SELECT DISTINCT(res_code) AS res_code FROM watussi_tmp_evenement_res_code WHERE type_id = $type_id";
	$res = mysql_query($qry);
	$rescode_tab = array();
	while($row = mysql_fetch_object($res)){
		$rescode_tab[] = intval($row->res_code);
	}
	
	// On charge les dates en mémoire et on définit l'absisse 
	$x_tab = array();
	$qry = 'SELECT wd.date_id, date 
			FROM watussi_date wd, watussi_tmp_evenement_res_code wt
			WHERE wd.date_id = wt.date_id			
			GROUP BY wd.date_id
			ORDER BY date;';
	$res = mysql_query($qry);
	while($row = mysql_fetch_object($res)){
		$date_id = $row->date_id;
		$date = $row->date;
		$x_tab[] = $date;
	}
	
	$series_tab = array();
	
	foreach($rescode_tab as $rescode){
		$series_temp = array();
		foreach($dateid_tab as $dateid){
			$qry = "SELECT nb 
					FROM watussi_tmp_evenement_res_code wt
					WHERE wt.date_id = '$dateid'
					AND wt.res_code = '$rescode'
					AND wt.type_id = $type_id;";
			$res = mysql_query($qry);
			$nb = mysql_num_rows($res);
			if($nb > 0){
				$row = mysql_fetch_object($res);
				$nb = intval($row->nb);				
			}
			$series_temp[] = $nb;
			
			//echo $dateid . ' ' . $rescode . ' ' . $nb . '<br />';
		}
		$series_tab[$rescode] = $series_temp;
	}
	
	if($type_id == 0){
		$title = 'Crawl / res code';
		$subtitle = 'Googlebot';
	}
	elseif($type_id == 1){
		$title = 'Visites / res code';
		$subtitle = 'From Google';
	}
	
	return(get_stacked_areas($div_container, $x_tab, $series_tab, $title, $subtitle));

}

function get_temps_execution($div_container){
	$qry = 'SELECT date, response_time FROM watussi_date wd, watussi_tmp_response_time wt
			WHERE wd.date_id = wt.date_id
			ORDER BY date;';
			
	$res = mysql_query($qry);
	
	$x_tab = array();
	$series_tab = array();
	while($row = mysql_fetch_object($res)){
		$x_tab[] = $row->date;
		$series_tab[] = intval($row->response_time);
	}
	
	return(get_stacked_area($div_container, $x_tab, $series_tab, "Temps d\'exécution moyen", '', 'line'));
}

function get_pages_connues($div_container){
	$qry = 'SELECT date, nb FROM watussi_date wd, watussi_tmp_ppc wt
			WHERE wd.date_id = wt.date_id
			ORDER BY date;';
			
	$res = mysql_query($qry);
	
	$x_tab = array();
	$series_tab = array();
	while($row = mysql_fetch_object($res)){
		$x_tab[] = $row->date;
		$series_tab[] = intval($row->nb);
	}
	
	return(get_stacked_area($div_container, $x_tab, $series_tab, 'Pages connues de Google'));
}

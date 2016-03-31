<?php

/* fonctions.inc.php
 * Première version : 25 novembre 2012
 * Dernière modification : 6 octobre 2013
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

function login(){
	session_start();
	if($_SESSION['login'] != 'ok'){
		header('Location: login.php');
		exit();
	}
}

function get_ecart($date){
	$a = strtotime($date);
	$b = time();
	$ecart = $b - $a;
	if($ecart > 86400){
		$ecart = round($ecart / 86400, 0);
		if($ecart > 1){
			$temp = "$ecart jours";
		}
		else{
			$temp = "$ecart jour";
		}
		return($temp);
	}
	elseif($ecart > 3600){
		$ecart = round($ecart / 3600, 0);
		if($ecart > 1){
			$temp = "$ecart heures";
		}
		else{
			$temp = "$ecart heure";
		}
		return($temp);
	}
	elseif($ecart > 60){
		$ecart = round($ecart / 60, 0);
		if($ecart > 1){
			$temp = "$ecart minutes";
		}
		else{
			$temp = "$ecart minute";
		}
		return($temp);
	}
	else{
		if($ecart > 1){
			$temp = "$ecart secondes";
		}
		else{
			$temp = "$ecart seconde";
		}
		return($temp);
	}
}


function add_log($adresse_ip, $referer, $user_agent, $url, $res_code=200, $response_time=0){
	global $dbh;
			// On regarde si il s'agit de Googlebot (contrôle user_agent + IP)
			if(strstr($user_agent,'Googlebot/2.1') && strstr($adresse_ip, '66.249')){
				$type_id = 0;
			}
			
			// On regarde si il s'agit d'une visite SEO issue de Google
			elseif(mb_eregi('www.google.', $referer)){
				$type_id = 1;
				$keyword = 'not_provided';
				
				preg_match("/[\&\?]q=([^&]*)/", $referer, $matches);				
				if($matches[1]){
					$keyword = urldecode($matches[1]);					
                }                
				
			}
			
			else{
				return(0);
			}
			
			
			// On récupère la date
			$date_id = get_date_id();
			
			// On récupère l'URL
			$url_id = get_url_id($url, $type_id, $date_id, $res_code);
			
			// On recupere le keyword
			if($type_id == 1){
				$keyword_id = get_keyword_id($keyword);
			}
			else{
				$keyword_id = 0;
			}
			
			$heure = date('G');
			$qry = "INSERT INTO watussi_log(url_id, type_id, date_id, heure, res_code, response_time, keyword_id)
					VALUES('$url_id', '$type_id', '$date_id', '$heure', '$res_code', '$response_time', $keyword_id);";	
			$dbh->query($qry);
			
			return(0);
}

function get_date_id(){
	global $dbh;
	$today = date("Y-m-d");
	
	$qry = "SELECT date_id FROM watussi_date WHERE date = '$today'";
	$res = $dbh->query($qry);
	$nb = $res->rowCount();
	
	if($nb < 1){
		$qry = "INSERT INTO watussi_date(date_id, date) VALUES(NULL, '$today')";
		$dbh->query($qry);
		$date_id = $dbh->lastInsertId();
	}
	else{
		$row = $res->fetchObject();
		$date_id = $row->date_id;
	}
	
	return($date_id);
}

function get_url_id($url, $type_id, $date_id, $res_code){
	global $dbh;
	$url_md5 = md5($url);
	
	// On regarde si l'URL existe deja
	if($type_id == 0){
		$qry = "UPDATE watussi_url SET nb_crawl = nb_crawl + 1, last_res_code = '$res_code', last_crawl = '$date_id' WHERE url_md5 = '$url_md5';";
	}
	elseif($type_id == 1){
		$active = true;
		$qry = "UPDATE watussi_url SET nb_visites = nb_visites + 1, last_res_code = '$res_code', active = $active WHERE url_md5 = '$url_md5';";
	}
	
	
	$update = $dbh->prepare($qry);
	$update->execute();
	$nb = $update->rowCount();
	
	
	if($nb > 0){		
		
		// On regarde si c'est son premier crawl
		if($type_id == 0){
			$sql = "SELECT first_crawl FROM watussi_url WHERE url_md5 = '$url_md5';";
			$query = $dbh->query($sql);
			$row = $query->fetchObject();
			$first_crawl = $row->first_crawl;
			if($first_crawl == 0){
				$qry = "UPDATE watussi_url SET first_crawl = '$date_id' WHERE url_md5 = '$url_md5';";
				$dbh->query($qry);
			}
		}
		
		$qry = "SELECT url_id FROM watussi_url WHERE url_md5 = '$url_md5';";
		$res = $dbh->query($qry);
		$row = $res->fetchObject();
		$url_id = $row->url_id;
	}
	
	// Si elle n'existe pas, on l'ajoute
	else{
		if($type_id == 0){
			$nb_crawl = 1;
			$nb_visites = 0;
			$active = 0;
			$first_crawl = $last_crawl = $date_id;
		}
		elseif($type_id == 1){
			$nb_crawl = 0;
			$nb_visites = 1;
			$active = 1;
			$first_crawl = $last_crawl = 0;
		}		
				
		$qry = "INSERT INTO watussi_url(url_id, url, url_md5, first_crawl, last_crawl, nb_crawl, nb_visites, last_res_code, cat_id, active)
				VALUES(NULL, '$url', '$url_md5', '$first_crawl', '$last_crawl', '$nb_crawl', '$nb_visites', '$res_code', 0, $active);";
		$dbh->query($qry);
		$url_id = $dbh->lastInsertId();
	}
	return($url_id);
}

function get_keyword_id($keyword){
	global $dbh;
	$keyword_md5 = md5($keyword);
	
	$qry = "UPDATE watussi_keywords SET nb_visites = nb_visites + 1 WHERE keyword_md5 = '$keyword_md5';";
	$res = $dbh->query($qry);
	
	$nb = $dbh->rowCount();
	
	if($nb > 0){
		$qry = "SELECT keyword_id FROM watussi_keywords WHERE keyword_md5 = '$keyword_md5';";
		$res = $dbh->query($qry);
		$row = $dbh->lastInsertId($res);
		$keyword_id = $row->keyword_id;
	}
	else{
		$tmp = explode(' ', $keyword);
		$nb_term = count($tmp);
		$qry = "INSERT INTO watussi_keywords(keyword_id, keyword_md5, keyword, nb_term, nb_visites)
				VALUES(NULL, '$keyword_md5', '$keyword', '$nb_term', 1);";
		$res = $dbh->query($qry);
		$keyword_id = $dbh->lastInsertId();
	}
	
	return($keyword_id);
}


function heure_minute_seconde(){
	return(date('H:i:s'));
}
 



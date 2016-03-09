<?php

/* create-table.php
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

  include_once('inc/connect.inc.php'); 

$qry = 
"CREATE TABLE IF NOT EXISTS `watussi_date` (
  `date_id` int(6) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  PRIMARY KEY (`date_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());


$qry =
"CREATE TABLE IF NOT EXISTS `watussi_keywords` (
  `keyword_id` int(6) NOT NULL AUTO_INCREMENT,
  `keyword_md5` varchar(32) NOT NULL,
  `keyword` varchar(600) NOT NULL,
  `nb_term` int(6) NOT NULL,
  `nb_visites` int(6) NOT NULL,
  PRIMARY KEY (`keyword_id`),
  KEY `keyword_md5` (`keyword_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_kpi` (
  `kpi_id` int(6) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `nb_urls` int(8) NOT NULL,
  `nb_crawl_30j` int(8) NOT NULL,
  `nb_pa_30j` int(8) NOT NULL,
  `new_pages_30j` int(8) NOT NULL,
  `avg_execution_time` varchar(6) NOT NULL,
  PRIMARY KEY (`kpi_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());


$qry=
"CREATE TABLE IF NOT EXISTS `watussi_log` (
  `url_id` int(7) NOT NULL,
  `type_id` int(2) NOT NULL,
  `date_id` int(6) NOT NULL,
  `heure` int(2) NOT NULL,
  `res_code` int(3) NOT NULL,
  `response_time` int(6) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keyword_id` int(9) NOT NULL,
  KEY `url_id` (`url_id`),
  KEY `type_id` (`type_id`),
  KEY `date_id` (`date_id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry = 
"CREATE TABLE IF NOT EXISTS `watussi_tmp_active_nonactive` (
  `active` int(9) NOT NULL,
  `non_active` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_crawl_utile_inutile` (
  `date_id` int(6) NOT NULL,
  `utile` tinyint(1) NOT NULL,
  `nb` int(9) NOT NULL,
  KEY `date_id` (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry = 
"CREATE TABLE IF NOT EXISTS `watussi_tmp_evenement_30j` (
  `date_id` int(6) NOT NULL,
  `nb` int(8) NOT NULL,
  `type_id` int(3) NOT NULL,
  KEY `date_id` (`date_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_evenement_global_heure` (
  `heure` int(2) NOT NULL,
  `nb` int(6) NOT NULL,
  `type_id` int(3) NOT NULL,
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_evenement_jour` (
  `date_id` int(6) NOT NULL,
  `nb` int(8) NOT NULL,
  `type_id` int(3) NOT NULL,
  KEY `date_id` (`date_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_evenement_res_code` (
  `date_id` int(8) NOT NULL,
  `nb` int(8) NOT NULL,
  `type_id` int(3) NOT NULL,
  `res_code` int(3) NOT NULL,
  KEY `date_id` (`date_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());


$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_evenement_unique_30j` (
  `date_id` int(6) NOT NULL,
  `nb` int(8) NOT NULL,
  `type_id` int(3) NOT NULL,
  KEY `date_id` (`date_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_evenement_unique_jour` (
  `date_id` int(6) NOT NULL,
  `nb` int(8) NOT NULL,
  `type_id` int(3) NOT NULL,
  KEY `date_id` (`date_id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_nouvelles_pages` (
  `date_id` int(6) NOT NULL,
  `nb` int(8) NOT NULL,
  KEY `date_id` (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_pa_30j` (
  `date_id` int(6) NOT NULL,
  `nb` int(6) NOT NULL,
  KEY `date_id` (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_pa_jour` (
  `date_id` int(6) NOT NULL,
  `nb` int(6) NOT NULL,
  KEY `date_id` (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());


$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_ppc` (
  `date_id` int(6) NOT NULL,
  `nb` int(8) NOT NULL,
  KEY `date_id` (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());


$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_rescode` (
  `res_code` int(3) NOT NULL,
  `nb` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_tmp_response_time` (
  `date_id` int(6) NOT NULL,
  `response_time` varchar(6) NOT NULL,
  KEY `date_id` (`date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$qry =
"CREATE TABLE IF NOT EXISTS `watussi_url` (
  `url_id` int(7) NOT NULL AUTO_INCREMENT,
  `url` varchar(600) NOT NULL,
  `url_md5` varchar(32) NOT NULL,
  `first_crawl` int(4) NOT NULL,
  `last_crawl` int(4) NOT NULL,
  `nb_crawl` int(7) NOT NULL,
  `nb_visites` int(7) NOT NULL,
  `last_res_code` int(3) NOT NULL,
  `cat_id` int(5) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`url_id`),
  KEY `url_md5` (`url_md5`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";

$dbh->query($qry) or die($dbh->errorInfo());

$dbh = null;

header('Location: login.php?create=ok');

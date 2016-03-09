<?php

/* config.inc.php
 * Première version : 25 novembre 2012
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
include_once('config.inc.php');	

  	$db_selected = $CONFIG_TAB['database'];
    $db_host = $CONFIG_TAB['host'];

    $dsn = 'mysql:dbname='.$db_selected.';host='.$db_host;
    $user = $CONFIG_TAB['login_db'];
    $password = $CONFIG_TAB['password_db'];

    try {
        $dbh = new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        echo 'Connexion échouée : ' . $e->getMessage();
    }


	
	$login = $mdp = '';
	$erreur_login = $erreur_table = false;
	
	// On vérifie login et mot de passe
	if(isset($_POST['login']) && isset($_POST['mdp'])){
		$login = addslashes($_POST['login']);
		$mdp = addslashes($_POST['mdp']);		
		if(($CONFIG_TAB['users'][$login] == $mdp)&&($login != '')&&($mdp != '')){
			$_SESSION['login'] = 'ok';
			header('Location: index.php');
			exit();
		}		
		else{
			$erreur_login = true;
		}
	}
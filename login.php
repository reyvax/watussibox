<?php

/* login.php
 * Première version : 4 décembre 2012
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

	session_start();
  include_once('inc/connect.inc.php'); 
	include_once('inc/fonctions.inc.php');
	 
  // On vérifie que les tables sont bien installées
  $qry = "SHOW TABLES FROM " . $CONFIG_TAB['database'] . " LIKE 'watussi_%'";
  $show = $dbh->prepare($qry);
  $show->execute();

  $nb = $show->rowCount();
	if($nb == 0){
		$erreur_table = true;
	}
	
	$create = 'nok';
	if(isset($_GET['create'])){
		$create = $_GET['create'];
	}
	

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Watussi Box</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="bootstrap/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="bootstrap/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="bootstrap/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="bootstrap/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="bootstrap/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="container">
    
    <?php
		if($erreur_login){
			echo "<div class='alert alert-error' align='center'><strong>Oupsss...</strong> Il semble y avoir une erreur de login ou de mot de passe !</div>";
		}
		
		if(!$dbh){
			echo "<div class='alert alert-error' align='center'><strong>Oupsss...</strong> Il semble y avoir une erreur de configuration de base de données !</div>";
		}
		
		elseif(!$db_selected){
			echo "<div class='alert alert-error' align='center'><strong>Oupsss...</strong> La base de données semble ne pas exister !</div>";
		}
		
		elseif($erreur_table){
			echo "<div class='alert alert-error' align='center'><strong>Oupsss...</strong> Les tables ne semblent pas avoir été installées dans la base de données ! <a href='create-table.php'>Cliquez-ici</a> pour les créer</div>";
		}
		
		if($create == 'ok'){
			echo "<div class='alert alert-success' align='center'><strong>Yes !</strong> Tables créées avec succès.</div>";
		}
    ?>

      <form class="form-signin" method="POST">
        <h2 class="form-signin-heading">Connectez-vous</h2>
        <input type="text" class="input-block-level" placeholder="Login" name="login">
        <input type="password" class="input-block-level" placeholder="Mot de passe" name="mdp">
        <button class="btn btn-large btn-primary" type="submit">Valider</button>
      </form>

    </div> 

  </body>
</html>

<?php $dbh = null; ?>
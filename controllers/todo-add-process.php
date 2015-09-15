<?php

$login = FALSE;
$email = '';
session_start();


	function __autoload( $className )
	{
		include_once( '../classes/' . $className . '.php' );
	}

if (!isset($_SESSION['login']) || !isset($_SESSION['email']) )
{
	Helper::redirect( '../dashboard.php' );
}
else
{
	$login = $_SESSION['login'];
	$email = $_SESSION['email'];

	if ($login === FALSE)
	{
		Helper::redirect( '../dashboard.php' );
	}
}
	
	// Controle wanneer iemand op submit (=registreer) heeft gedrukt

	if ( isset( $_POST[ 'submit' ] ) )
	{
		$omschrijving = $_POST['todo'];
		//
		$dbf			=	new PDOfunctions( 'localhost', 'labo', 'root', '' );

		$query					=	'INSERT INTO todo (user, 
														omschrijving,														
														date_create) 
									VALUES (:email, 
											:omschrijving, 											 
											NOW())';

		$placeHolders 	=	array( ":email" => $email, 
									":omschrijving" => $omschrijving);

		//var_dump($placeHolders);
		$dbf->performQuery($query, $placeHolders);
		//var_dump($test);
		//die();
		Helper::redirect( '../todo.php' );
	}
?>
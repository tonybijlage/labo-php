<?php

session_start();


	function __autoload( $className )
	{
		include_once( 'classes/' . $className . '.php' );
	}
	die();
?>
<?php

session_start();

	function __autoload( $className )
	{
		include_once( 'classes/' . $className . '.php' );
	}

	if ( isset( $_POST[ 'submit' ] ) )
	{

		//alle SESSIONS vernietigen

		session_destroy();

		//omleiden naar dashboard

		Message::setMessage( 'U bent succesvol uitgelogd.' , 'success');
		Helper::redirect( 'dashboard.php' );

	}

?>



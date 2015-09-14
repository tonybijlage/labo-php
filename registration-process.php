<?php

/*

*/

	session_start();

	function __autoload( $className )
	{
		include_once( 'classes/' . $className . '.php' );
	}

	// Controle wanneer iemand op submit (=registreer) heeft gedrukt

	if ( isset( $_POST[ 'submit' ] ) )
	{
		$email		=	$_POST[ 'email' ];
		$password	=	$_POST['password'];

		try 
		{
			
			$dbf	=	new PDOfunctions('localhost', 'labo', 'root', '' );

			// Maak een nieuwe instantie van de klasse User aan en geef hier dedbf aan mee
			// "Dependency injection" van database klasse

			$user = new User( $dbf );

			// Controleren of de user al bestaat

			$userExists = $user->exists( $email );

			// Als de user bestaat, errormessage tonen en redirecten

			if ( $userExists === TRUE )
			{
				Message::setMessage( "Deze gebruiker bestaat al, probeer een ander e-mailadres", "error" );
				Helper::redirect( 'registration-form.php' );
			}
			else // Bestaat de gebruiker nog niet, maak de gebruiker aan
			{
				// Toevoegen aan de database
				
				$user->create( $email, $password );

				// Cookie aanmaken om gebruiker achteraf te kunnen identificeren

				$user->createCookie( $email );

				// Redirecten naar dashboard wanneer gebruiker is toegevoegd & cookie is aangemaakt
				
				$_SESSION['email'] = $email;
				$_SESSION['login'] = TRUE;
				Helper::redirect( 'dashboard.php' );
			}
		
		} 
		catch (Exception $e) 
		{
			Message::setMessage( $e->getMessage(), 'error' );
		}
	}
?>
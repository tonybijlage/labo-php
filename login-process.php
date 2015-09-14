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
		
		$email				=	$_POST[ 'email' ];
		$password			=	$_POST['password'];

		try 
		{
			
			$dbf			=	new PDOfunctions( 'localhost', 'labo', 'root', '' );
			
			// Maak een nieuwe instantie van de klasse User aan en geef hier de dbf aan mee
			// "Dependency injection" van database klasse
			
			$user 			= new User( $dbf );
			$userIsLoggedIn	=	$user->login( $email, $password );

			if ( $userIsLoggedIn )
			{
				
				$user->createCookie( $email );

				$_SESSION['login']	= TRUE;
				$_SESSION['email']	= $email;
				
				Message::setMessage( 'Welkom! ' . $email, 'success');
				Helper::redirect( 'dashboard.php' );

			}
			else
			{
				
				$teller = 0;
				if (isset($_SESSION[tryials_LOGIN]))
					{

						$teller = $_SESSION[tryials_LOGIN];
					}

				
				$teller ++;
				$_SESSION[tryials_LOGIN] = $teller;

				if ($teller <= 2)
					{
						$m = $teller + 1;
						Message::setMessage( 'Er ging iets mis tijdens het inloggen. Probeer opnieuw. Poging ' . $m, 'error' );
						$_SESSION['login'] = FALSE;
						Helper::redirect( 'login-form.php' );

					}
				else
					{

						Message::setMessage( 'U dient opnieuw te registreren.', 'error' );
						$teller = 0;
						$_SESSION[tryials_LOGIN] = $teller;
						Helper::redirect( 'registration-form.php' );

					}//end ELSE

			}//end ELSE
		}
		catch (Exception $e) 
		{
			Message::setMessage( $e->getMessage(), 'error' );
		}
	}
?>
<?php
/*

	exists( $email )
	create( $email, $password )
	createCookie( $email )
	validate()
	login( $email, $password )

*/

	class User
	{
		private $dbf;
		public function __construct( $dbf )
		{
			$this->dbf	=	$dbf; //conncection
		}
		// Controleert of de gebruiker bestaat

		public function exists( $email )
		{
			// Variabele die wordt doorgegeven wanneer de gebruiker bestaat

			$userExists	=	TRUE;

			// Query om te controleren of de gebruiker die wil registreren
			// niet reeds in de DB voorkomt op basis van het e-mailadres

			$userExistsQuery	=	'SELECT *
										FROM users
										WHERE email = :email';

			$userExistsPlaceholders	=	array( ':email' => $email );
			
			// Voer de query uit, zal false returnen als er geen resultaat werd gevonden

			$userExistsResult = $this->dbf->performQuery( $userExistsQuery, $userExistsPlaceholders );

			// Als het resultaat géén array is, dan is de gebruiker niet teruggevonden
			// De user bestaat dus niet

			if ( ! is_array( $userExistsResult ) )
			{
				$userExists	=	FALSE;
			}

			// Return de boolean die vertelt of de gebruiker bestaat of niet

			return $userExists;
		}

		// Voeg gebruiker toe aan DB

		public function create( $email, $password )
		{
			// Maak een salt aan
			$salt			=	uniqid(mt_rand(), true);
			
			// Gebruik de salt om het paswoord te hashen

			$hashedPassword = 	$this->hash( $password, $salt );

			// Query om alle gegevens aan de DB toe te voegen

			$createQuery	=	'INSERT INTO users (email, 
														salt, 
														password, 
														create_date) 
									VALUES (:email, 
											:salt, 
											:password, 
											NOW())';

			$placeHolders 	=	array( ":email" => $email, 
										":password" => $hashedPassword,
										":salt" => $salt );
			// Query uitvoeren

			$this->dbf->performQuery($createQuery, $placeHolders);
		}
		public function createCookie( $email )
		{
			$cookieKey	=	'login';
			$cookieExpirationTime	=	time() + (60*60*24*30);

			// Salt ophalen van de gebruiker met een bepaald e-mailadres

			$salt	=	$this->getSalt( $email );

			// concateneer het emailadres met de salt en hash dit

			$hashedSaltedEmail = $this->hash( $email, $salt );

			// Stel de cookie-value samen (email - delimiter - hashed&salted email)

			$cookieValue	=	$email . ',' . $hashedSaltedEmail;

			// Set de cookie value & relocate naar dashboard om de aanmaak van de cookie te bevestigen

			Cookie::createCookie( $cookieKey, $cookieValue, $cookieExpirationTime );

		}
		public function validate()
		{
			$userIsValid	=	false;
			$cookieValue	=	Cookie::getCookie( 'login' );

			// Methode aanspreken die instaat voor het verwerken van de cookie data
			// tot makkelijk bruikbare variabelen

			$processedCookieValue	=	$this->processCookieValue( $cookieValue );

			if ( $cookieValue && $processedCookieValue )
			{
				// Variabele aanmaken om leesbaarheid te bevorderen

				$email 						= 	$processedCookieValue[ 'email' ];
				$hashedSaltedEmailCookie	=	$processedCookieValue[ 'hashedSaltedEmail' ];

				// Salt ophalen uit database

				$salt	=	$this->getSalt( $email );

				// Als de salt niet leeg is, is er een gebruiker teruggevonden met het emailadres
				// dat in de cookie staat

				if ( $salt !== '' )
				{
					// Creeer een zelfgemaakte hash op basis van het emailadres uit de cookie
					// en de salt uit de database
					$hashedSaltedEmailCheck	=	$this->hash( $email, $salt );

					// Stemmen deze beide hashes (uit cookie & de zelfgemaakte) overeen
					// dan heeft de gebruiker een geldige cookie

					if ( $hashedSaltedEmailCookie === $hashedSaltedEmailCheck )
					{
						$userIsValid = true;
					}
				}
			}
			// Return of de gebruiker geldig is (TRUE//FALSE)

			return $userIsValid;

		}
		public function login( $email, $password )
		{
			$userIsValid	=	FALSE;

			// Haal de salt op op basis van het e-mailadres

			$salt = $this->getSalt( $email );

			// Als de salt niet leeg is

			if ( $salt !== '' )
			{
				// Haal dan het gehashte paswoord op
				
				$userDataQuery	=	'SELECT password
										FROM users
										WHERE email = :email';
				$userDataPlaceholders	=	array( ':email' => $email );
				$userDataResult	= $this->dbf->performQuery( $userDataQuery, $userDataPlaceholders );

				// Controle of er een resultaat is, is niet nodig want anders zouden we geen
				// salt teruggekregen hebben
				// Verwerk te teruggekregen data uit de DB tot een makkelijk bruikbare variabele
				// Het resultaat zit in een multidimensionele array, dus [0][kolomnaam]

				$hashedPasswordDb	=	$userDataResult[ 0 ][ 'password' ];

				// Maak een hash van het paswoord en de salt

				$hashedPasswordCheck	=	$this->hash( $password, $salt );

				// Controleer of beide hashes gelijk zijn, want dan is heeft de user
				// zijn gegevens correct ingevuld

				if ( $hashedPasswordDb === $hashedPasswordCheck )
				{
					$userIsValid	=	TRUE;
				}
				
			}
			return $userIsValid;
		}
		private function processCookieValue( $cookieValue )
		{
			$returnValues	=	FALSE;
			// Scheid de waarde van de cookie op basis van de delimiter ,
			$explodedValues	=	explode( ',', $cookieValue );
			// Controleer of er twee waardes in de array zitten
			if ( count( $explodedValues ) === 2 )
			{
				$returnValues[ 'email' ]				=	$explodedValues[ 0 ];
				$returnValues[ 'hashedSaltedEmail' ]	=	$explodedValues[ 1 ];
			}
			return $returnValues;
		}
		public function getSalt( $email )
		{
			$salt	=	'';
			// Query om salt van gebruiker op te halen op basis van e-mail

			$saltQuery			=	'SELECT salt 
									FROM users
									WHERE users.email = :email';

			$saltPlaceholders	=	array( ':email' => $email );

			$saltResult	=	$this->dbf->performQuery( $saltQuery, $saltPlaceholders );

			// Haal uit de array de salt value van de gebruiker met dat bepaald e-mailadres

			if ( $saltResult )
			{
				$salt	=	$saltResult[0]['salt'];
			}
			//var_dump($salt);
			//die();
			return $salt;

		}
		private function hash( $word, $salt = '' )
		{
			// Creëer een hash
			$hashedAndSaltedWord	=	hash( 'sha512', $word . $salt );
			return $hashedAndSaltedWord;
		}
	}
?>
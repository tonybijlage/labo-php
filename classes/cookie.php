<?php
	class Cookie
	{
		public static function createCookie( $key, $value, $expirationTime = 0 )
		{

			setcookie( $key, $value, $expirationTime );

		}

		public static function getCookie( $key )
		{
			$cookieValue	=	FALSE;

			if ( isset( $_COOKIE[ $key ] ) )
			{

				$cookieValue	=	$_COOKIE[ $key ];

			}

			return $cookieValue;
		}

		public static function deleteCookie( $key )
		{
			if ( isset( $_COOKIE[ $key ] ) )
			{

				self::createCookie( $key, '', time() - 1000 );

			}
		}

	}//end class Cookie
?>
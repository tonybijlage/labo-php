<?php
	class Message
	{
		public static function getMessage()
		{
			$message = FALSE;
			if ( isset( $_SESSION[ 'message' ] ) )
			{
				$message['type']	=	$_SESSION[ 'message' ][ 'type' ];
				$message['text']	=	$_SESSION[ 'message' ][ 'text' ];
				unset( $_SESSION[ 'message' ] );
			}
			return $message;
		}
		public static function setMessage( $text, $type )
		{
			$_SESSION['message']['text']	=	$text;
			$_SESSION['message']['type']	=	$type;
		}
	}
?>
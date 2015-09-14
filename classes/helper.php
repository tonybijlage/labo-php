<?php
	class Helper
	{
		public static function redirect( $page )
		{
			$locationString	=	'location: ' . $page;
			header( $locationString );
		}
	}
?>
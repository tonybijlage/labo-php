<?php

$login = FALSE;
$email = '';


session_start();


	function __autoload( $className )
	{
		include_once( 'classes/' . $className . '.php' );
	}

if (!isset($_SESSION['login']) || !isset($_SESSION['email']) )
{
	Helper::redirect( 'dashboard.php' );
}
else
{
	$login = $_SESSION['login'];
	$email = $_SESSION['email'];

	if ($login === FALSE)
	{
		Helper::redirect( 'dashboard.php' );
	}
}

if (isset($_POST['delete']))
{

	$dbf	=	new PDOfunctions( 'localhost', 'labo', 'root', '' );
	$id 	= $_POST['delete'];
	
	$query 	= 'DELETE FROM todo
					WHERE todo.idTodo = :id
					LIMIT 1';

	$placeHolders = array(":id" => $id );

	$dbf->performQuery($query, $placeHolders);
	Helper::redirect( 'todo.php' );

}

if (isset($_POST['checked']))
{
	$dbf	=	new PDOfunctions( 'localhost', 'labo', 'root', '' );
	$id 	= $_POST['checked'];

	//de waarde van checked ophalen
	$checked = array();
	$query = '	SELECT checked 
				FROM todo
				WHERE todo.idTodo = :id';
	$placeHolders = array(":id" => $id );			

	$checked = $dbf->performQuery($query, $placeHolders);
	$checkedValue = $checked[0]['checked'];

	($checkedValue == 0)? $checkedValue = 1 : $checkedValue = 0;
	

	$query = '	UPDATE todo 
				SET checked = :checked
				WHERE todo.idTodo = :id
				LIMIT 1';
	
	$placeHolders = array(":id" => $id,
							":checked" =>$checkedValue );

	$dbf->performQuery($query, $placeHolders);
	
		Helper::redirect( 'todo.php' );							
}

?>
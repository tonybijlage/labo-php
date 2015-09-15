<?php

/*

*/
$login = '';
$email = '';
session_start();

function __autoload( $className )
{
	include_once( 'classes/' . $className . '.php' );
}

$currentPage	=	basename( $_SERVER[ 'PHP_SELF' ] );

// indien de pagina op niet legale wijze is benaderd

if (!isset($_SESSION['login']) || !isset($_SESSION['email']) )
{
	Helper::redirect( 'dashboard.php' );
}
else
{
	$login = $_SESSION['login'];
	$email = $_SESSION['email'];

	if ($login === 'FALSE')
	{
		Helper::redirect( 'dashboard.php' );
	}
}

// Haal de messages op die teventueel geset zijn

	$message = Message::getMessage();

//hoe het boeltje ophalen

 $dbf            	=   new PDOfunctions( 'localhost', 'labo', 'root', '' );

 $query 			=	'SELECT *
 							FROM todo
 							WHERE user = :email';

 $placeHolders 		=	array( ":email" => $email);

$todo 				=	array();
$todoHeaders		=	array();
$todo 				=	$dbf->getDataFromTable($query, $placeHolders);

$todoHeaders	 	= 	$dbf->getColumnNames($todo, true);
$todoHeaders[] 		= 	'delete';
//var_dump($todo);
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link media="all" type="text/css" rel="stylesheet" href="css/global.css">
        <title>TODO</title>

        <!--link rel="stylesheet" href="css/global.css"-->
    </head>
    <body>
    	<div id="container">
	    	<header class="group">			
		        <div>
		        	<a href="dashboard.php">Home</a> 
		        </div>
		        <nav>
                    <ul>
                        <?php if(!$login): ?>
                            <li><a href="login-form.php">Login</a></li>
                            <li><a href="registration-form.php">Registreer</a></li>
                        <?php else: ?>
                             <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="todo.php">TODO</a></li>
                             <li><a href="logout-form.php">logout( <?=$email ?> )</a></li>
                        <?php endif ?>
                    </ul>
                </nav>
	    	</header>
	    	<?php if ( $message ): ?>
				<div class="modal <?= $message['type'] ?>"><?= $message['text'] ?></div>
			<?php endif ?>
			<div class="body">
                
        		<h1>dit zijn al je TODO's</h1>
        		<a href="todo-add.php">voeg een TODO toe</a>
        		<p></p>
				<div>
					<form method="POST" action="controllers/todo-action-process.php" accept-charset="UTF-8">   
					<table>
						<?php if($todo): ?>
						<thead>
						<?php foreach($todoHeaders as $header): ?>
						<td>
							<?= $header ?>
						</td>		
						<?php endforeach ?>
						</thead>
						<?php foreach ($todo as $key => $td): ?>
							<tr class="<?= ( ( $key + 1) %2 == 0 ) ? 'even' : '' ?>">
								<td><?= ($key + 1) ?></td>
								<?php foreach ($td as $teller => $value): ?>

									<?php if($teller !== 'checked'): ?>
									
									<td><?= $value ?></td>
									<?php endif ?>
								<?php endforeach ?>
								
								<td>
									<button type="submit" name="checked" value="<?= $td['idTodo']?>" >
									<?php if($td['checked'] == 1): ?>
									
										<img src="img/checked.jpg" alt="checked">
										<?php else: ?>
										<img src="img/unchecked.jpg" alt="unchecked">									
									<?php endif ?>
									</button>
								</td>
								<td>
									<button type="submit" name="delete" value="<?= $td['idTodo']?>" >
									<img src="img/delete.jpg" alt="Delete button">
									</button>								
								</td>
							</tr>
						<?php endforeach ?>
						<?php endif ?>	

					</table>
				</form>
				</div>
        	</div>				
			
		</div>
    </body>
</html>
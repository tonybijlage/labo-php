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

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link media="all" type="text/css" rel="stylesheet" href="css/global.css">
        <title>TODO-form</title>

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
                
        		<h1>voeg een TODO toe</h1>

				<form method="POST" action="todo-process.php" accept-charset="UTF-8">   
				    <p>
				        <label for="todo">TODO</label><br>
				        <input name="todo" type="textarea">
				    </p>
				    <!--p>
				        <label for="location">location</label><br>
				        <input name="location" type="text">
				    </p-->

				   
				    <p><input type="submit" value="voeg toe" name="submit"></p>

			    </form>
        	</div>				
			
		</div>
    </body>
</html>
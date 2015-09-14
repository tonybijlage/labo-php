<?php
/*

*/



session_start();

	function __autoload( $className )
	{
		include_once( 'classes/' . $className . '.php' );
	}
	$currentPage	=	basename( $_SERVER[ 'PHP_SELF' ] );
	// Haal de messages op die teventueel geset zijn
	$message = Message::getMessage();



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
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link media="all" type="text/css" rel="stylesheet" href="css/global.css">
        <title>Dashboard</title>
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
            <div>
	            <form method="POST" action="logout-process.php" accept-charset="UTF-8">   
					    <p>
					       Wenst u uit te loggen?
					    </p>

					    <!-- submit button -->
					    <p><input type="submit" value="Uitloggen" name="submit"></p>
				</form>
			</div>
    </body>
    </html>


<?php 
	session_start();
	function __autoload( $className )
	{
		include_once( 'classes/' . $className . '.php' );
	}
	$currentPage	=	basename( $_SERVER[ 'PHP_SELF' ] );
	// Haal de messages op die teventueel geset zijn
	$message = Message::getMessage();
	
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link media="all" type="text/css" rel="stylesheet" href="css/global.css">
        <title>registration-form</title>
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
                <li><a href="login-form.php">Login</a></li>
                <li><a href="registration-form.php">Registreer</a></li>
            </ul>
        </nav>
    	</header>		

		<?php if ( $message ): ?>
			<div class="modal <?= $message['type'] ?>"><?= $message['text'] ?></div>
		<?php endif ?>
		<div class="body">
		<h1>Registreren</h1>
			<form action="registration-process.php" method="POST" accept-charset="UTF-8">
			    <p>
		            <label for="email">e-mail</label>
		            <br>
		            <input type="text" id="email" name="email">
		        </p>
		        <p>
		            <label for="password">paswoord</label>
		            <br>
		            <input type="password" id="password" name="password">
		            <!--input type="submit" name="generatePassword" value="Genereer een paswoord"-->
		        </p>
			    
			    <input type="submit" value="Registreer" name="submit">
			</form>
		</div>
	</div>
    </body>
</html>
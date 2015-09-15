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
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link media="all" type="text/css" rel="stylesheet" href="css/global.css">
        <title>login-form</title>

        
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
                
        		<h1>Meld je aan.</h1>

				<form method="POST" action="controllers/login-process.php" accept-charset="UTF-8">   
				    <p>
				        <label for="email">Email</label><br>
				        <input name="email" type="text" id="email" required>
				    </p>
				    <!-- password field -->
				    <p>
				        <label for="password">Password</label><br>
				        <input name="password" type="password" value="" id="password" required>
				    </p>

				    <!-- submit button -->
				    <p><input type="submit" value="Login" name="submit"></p>

			    </form>
        	</div>				
			
		</div>
    </body>
</html>
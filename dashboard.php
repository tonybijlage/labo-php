<?php
    
    $login = FALSE;
    $email = '';

    session_start();

    function __autoload( $className )
    {
        include_once( 'classes/' . $className . '.php' );
    }

    if (!isset ($_SESSION['login']) || !isset ($_SESSION['email']))
        try 
            {
                $dbf            =   new PDOfunctions( 'localhost', 'labo', 'root', '' );
                $user           =   new User( $dbf );
        	   
                $userIsValid    =   $user->validate();

                if( !$userIsValid )
                {
                    Message::setMessage( "Er ging iets mis, probeer opnieuw in te loggen.", 'error' );
                    Cookie::deleteCookie( 'login' );
                    Helper::redirect( 'login-form.php' );
                }
            }
        catch (Exception $e) 
            {
                Message::setMessage( $e->getMessage(), 'error' );
            }
    else
    {
        $login = $_SESSION['login'];
        $email = $_SESSION['email'];
    }
   

    $message    =   Message::getMessage();

        
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
            <div class="body">
                <h1>Dashboard</h1>
                <?php if (!$login): ?>
                U dient zich eerst <a href="login-form.php">in te loggen</a> of zich te <a href="registration-form.php">registreren</a> .
                <?php else: ?>
                U kan naar uw <a href="todo.php">TODO-lijstje</a> gaan!
                <?php endif ?>
            </div>

                <?php if ( $message ): ?>
                    <div class="modal <?= $message['type'] ?>"><?= $message['text'] ?></div>
                <?php endif ?>
        </div>
    </body>
</html>
<?php
    
    session_start();

    if ((isset($_SESSION['logged_in'])) && ($_SESSION['logged_in']==true)) {
        header('Location: game.php');
        exit();
    }

?>


<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Web game</title>
</head>

<body>

“Only the dead have seen the end of war.” -Plato <br /> <br />

<a href="register.php">Register Now!</a>
<br /><br />

<form action="login.php" method="post" >

    Login: <br /> <input type="text" name="login" /> <br />
    Password: <br /> <input type="password" name="password" /> <br /> <br />
    <input type="submit" value="Login" /> 

</form>

<?php

    if(isset($_SESSION['error'])) {
        echo $_SESSION['error'];
    }
?>


</body>
</html>
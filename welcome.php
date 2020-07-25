<?php
    
    session_start();

    if (!isset($_SESSION['successful_registration'])) {
        header('Location: index.php');
        exit();
    } else {
        unset($_SESSION['successful_registration']);
    }

    //Erasing variables storing values inserted to the form 
    if (isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
    if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if (isset($_SESSION['fr_password1'])) unset($_SESSION['fr_password1']);
    if (isset($_SESSION['fr_password2'])) unset($_SESSION['fr_password2']);
    if (isset($_SESSION['fr_terms'])) unset($_SESSION['fr_terms']);

    // Erasing registration errors
    if (isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
    if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
    if (isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
    if (isset($_SESSION['e_terms'])) unset($_SESSION['e_terms']);
    if (isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);

?>


<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Web game</title>
</head>

<body>

Thank you for registration on the platform! You can now log in to your account! <br /> <br />

<a href="register.php">Log in to your account</a>
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
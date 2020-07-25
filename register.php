<?php
    
    session_start();

    if (isset($_POST['email'])) {
        // successful validation
        $all_OK=true;

        // Check nickname
        $nick = $_POST['nick'];

        // Check nick length
        if ((strlen($nick)<3) || (strlen($nick)>20)) {
            $all_OK=false;
            $_SESSION['e_nick']="Nick has to be between 3 and 20 marks!";
        }

        // Check e-mail validation
        $email = $_POST['email'];
        $emailSecure = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ((filter_var($emailSecure, FILTER_VALIDATE_EMAIL)==false) || ($emailSecure!=$email)) {
            $all_OK=false;
            $_SESSION['e_email']="Insert correct e-mail address!";

        }

        if (ctype_alnum($nick)==false){
            $all_OK=false;
            $_SESSION['e_nick']="Nick can only consist of letters and numbers (no special characters)";
        }

        // Check password validation

        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        if ((strlen($password1)<8) || (strlen($password1) >20)) {
            $all_OK=false;
            $_SESSION['e_password']="Password needs to consist of between 8 and 20 characters!";
        }

        if ($password1!=$password2) {
            $all_OK=false;
            $_SESSION['e_password']="Passwords must be the same!";
        }

        $password_hash = password_hash($password1, PASSWORD_DEFAULT);

        // Were the terms accepted
        if (!isset($_POST['terms'])){
            $all_OK=false;
            $_SESSION['e_terms']="You must accept the terms!";
        }

        // Bot or not? Captcha
        $secret = "6LfhHbQZAAAAAI0Y1ML56XMGliAkZR3Pn8BDCagK";

        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

        $result = json_decode($check);

        if ($result->success==false){
            $all_OK=false;
            $_SESSION['e_bot']="Confirm that you are not a bot";
        }

        // Remember inserted data

        $_SESSION['fr_nick'] = $nick;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_password1'] = $password1;
        $_SESSION['fr_password2'] = $password2;
        if (isset($_POST['terms'])) {
            $_SESSION['fr_terms'] = true;
        } 

        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try {
            $connection = new mysqli($host,$db_user,$db_password,$db_name);
            if ($connection->connect_errno!=0) {
                throw new Exception(mysqli_connect_errno());
            } else {

                // Does an email exist already?
                $result = $connection->query("SELECT id FROM uzytkownicy WHERE email='$email'");

                if (!$result) throw new Exception($connection->error);

                $how_many_emails = $result->num_rows;
                if ($how_many_emails>0) {
                    $all_OK=false;
                    $_SESSION['e_email']="An account using this email already exists!";
                }

                // Does a nick exist already?
                $result = $connection->query("SELECT id FROM uzytkownicy WHERE user='$nick'");

                if (!$result) throw new Exception($connection->error);

                $how_many_nicks = $result->num_rows;
                if ($how_many_nicks>0) {
                    $all_OK=false;
                    $_SESSION['e_nick']="A user with this nick already exists. Choose a different nick!";
                }

                if ($all_OK==true) {
                    //All tests passed. We add a new user to the DB.
                    if($connection->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$password_hash','$email', 100, 100, 100, now() + INTERVAL 14 DAY )")) {
                        $_SESSION['successful_registration']=true;
                        header('Location: welcome.php');
                    } else {
                        throw new Exception($connection->error);
                    }
                }

                $connection->close();
            }
        }
        catch(Exception $e) {
            echo '<span style="color:red">Server error</span>!';
            echo '<br /> Developer Information: '.$e;
        }

    }

?>


<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Web game - get a free account!</title>
<script src="https://www.google.com/recaptcha/api.js"></script>

<style>

        .error {
            color:red;
            margin-top: 10px;
            margin-bottom: 10px;
        }

</style>
</head>

<body>

    <form method="post">

        Nickname: <br /> <input type="text" value="<?php
            if (isset($_SESSION['fr_nick'])) {
                echo $_SESSION['fr_nick'];
                unset($_SESSION['fr_nick']);
            }
        ?>" name="nick" /><br />

        <?php

            if (isset($_SESSION['e_nick'])) {
                echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
                unset($_SESSION['e_nick']);
            }

        ?>

        Email: <br /> <input type="text" value="<?php
            if (isset($_SESSION['fr_email'])) {
                echo $_SESSION['fr_email'];
                unset($_SESSION['fr_email']);
            }
        ?>" name="email" /><br />

        <?php

            if (isset($_SESSION['e_email'])) {
                echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                unset($_SESSION['e_email']);
            }

        ?>

        Password: <br /> <input type="password" value="<?php
            if (isset($_SESSION['fr_password1'])) {
                echo $_SESSION['fr_password1'];
                unset($_SESSION['fr_password1']);
            }
        ?>"name="password1" /><br />

        <?php

            if (isset($_SESSION['e_password'])) {
                echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                unset($_SESSION['e_password']);
            }

        ?>

        Repeat password: <br /> <input type="password" value="<?php
            if (isset($_SESSION['fr_password2'])) {
                echo $_SESSION['fr_password2'];
                unset($_SESSION['fr_password2']);
            }
        ?>"name="password2" /><br />

        <?php

            if (isset($_SESSION['e_password'])) {
                echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                unset($_SESSION['e_password']);
            }

?>

        <label>
        <input type="checkbox" name="terms" <?php
        if (isset($_SESSION['fr_terms'])) {
            echo "checked";
            unset($_SESSION['fr_terms']);
        }
        
        ?>/> I acceept the terms
        </label>

        <?php

            if (isset($_SESSION['e_terms'])) {
                echo '<div class="error">'.$_SESSION['e_terms'].'</div>';
                unset($_SESSION['e_terms']);
            }

        ?>

        <div class="g-recaptcha" data-sitekey="6LfhHbQZAAAAAGQoxh0Js4Uqjn_ncZV2d2yijYDt"></div>

        <?php

            if (isset($_SESSION['e_bot'])) {
                echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                unset($_SESSION['e_bot']);
            }

        ?>

        <br />

        <input type="submit" value="Register" />

    </form>

</body>
</html>
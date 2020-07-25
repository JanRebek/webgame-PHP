<?php
    session_start();

    if(!isset($_SESSION['logged_in'])) {
        header('Location: index.php');
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

<?php
    echo "<p>Hello ".$_SESSION['user'].'! [<a href="logout.php">Log out!</a>]</p>';
    echo "<p><b>Wood</b>: ".$_SESSION['drewno'];
    echo " | <b>Rock</b>: ".$_SESSION['kamien'];
    echo " | <b>Grain</b>: ".$_SESSION['zboze']."</p>";

    echo "<p><b>E-mail</b>: ".$_SESSION['email'];
    echo "<br /><b>Your premium expiry date</b>: ".$_SESSION['dnipremium']."</p>";

    $datetime = new DateTime('2020-06-06 08:31:12');

    echo "Server date and time: ".$datetime->format('Y-m-d H:i:s')."<br />";

    $end = DateTime::createFromFormat('Y-m-d H:i:s', $_SESSION['dnipremium']);

    $difference = $datetime->diff($end);

    if($datetime<$end) {
        echo "Premium left: ".$difference->format('%y years, %m months, %d days, %h hours, %i min, %s sec');
    } else {
        echo "Premium inactive since: ".$difference->format('%y years, %m months, %d days, %h hours, %i min, %s sec');
    }


?>

</body>
</html>
<?php

require_once("Includes/db.php");
session_start();
//echo print_r($_SESSION)."<br>";


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (dreamDB::getInstance()->verify_credentials($_POST['username'], $_POST['userpassword']));
    
    if ($logonSuccess == true) {
        // establish the necessary session variables
        $_SESSION['ualias'] = $_POST['username']; 
        // set whether reg complete
        $uid = dreamDB::getInstance()->get_uid_by_alias($_SESSION['ualias']);
        $_SESSION['uid'] = $uid;
        $registered = dreamDB::getInstance()->completed_reg($uid);
        if ($registered) {
            $_SESSION['registered'] = true;
            header('Location: logdream.php');
        } else {
            $_SESSION['registered'] = false;
            header('Location: complete.php');
        }
    }
}

if (array_key_exists("ualias", $_SESSION)) {
    header('Location: profile.php');
} 
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>index page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
  <div class="w-nav navbar" data-collapse="all" data-animation="default" data-duration="400" data-contain="1">
    <div class="w-container contain">
      <a class="w-nav-brand" href="#"></a>
      
      <nav class="w-nav-menu" role="navigation">
          <a class="w-nav-link" href="#join.php"></a>
          <br><h2>Want to log your dreams? <a href="join.php">Join now</a></h2>
          <a class="w-nav-link"></a>
        <div class="menu">
            <div class="w-form index-entry-form">
                <form class="login" id="email-form" name="logon" action="index.php" method="POST" >
                  <input class="w-input" type="text" placeholder="Enter your username" name="username">
                  <input class="w-input" type="password" placeholder="enter your password" name="userpassword">
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] == "POST") { 
                            if (!$logonSuccess) {
                                echo "Invalid name ".$_POST['username']. " and/or password ".$_POST[userpassword];
                                header('Location: login.php');
                            }
                        }
                    ?>
                    <input class="w-button entry-button" type="submit" value="LOG IN" data-wait="Please wait...">
                </form>
             </div>
        </div>
       </nav>
      <div class="w-nav-button">
        <div class="w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  <div></div>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->

    </body>
</html>

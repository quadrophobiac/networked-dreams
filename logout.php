<?php
session_start();
//echo print_r($_SESSION);

if (array_key_exists("ualias", $_SESSION) || array_key_exists("user", $_SESSION)) {
//destroySession();
    $logout = true;
    session_destroy();
}   

?>

<!DOCTYPE html>

<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>Logged Out</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    
    <div class="new-user-header">
    <?php
    if($logout){
             echo "<h2 class=\"sign-up-header\">You have been logged out.<br>" .
        "Please <a href='index.php'>click here</a> to refresh the screen.</h2>";
    } else {
        echo "<h2 class=\"sign-up-header\">" .
        "You cannot log out because you are not logged in</h2>";
    }
        ?>
  </div>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>

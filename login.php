<?php
/* code that updates using a DB class
 * The dreamDB object exists as long as the current page is being processed.
 * ^ above may be NB for persisting data
 */ 
require_once("Includes/db.php");
session_start(); // session data needed to retrieve associated dream index

if (array_key_exists("ualias", $_SESSION)) {
    // if logged in they cannot access add new member screen
   header('Location: profile.php');
   exit;
}
$logonSuccess = true; // default so that warning message not immediately displayed
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (dreamDB::getInstance()->verify_credentials($_POST['username'], $_POST['userpassword']));
    // seems above is returning false, because when removed it goes to the entry screen
    // above is returning something other than 0 or 1
    //$_SESSION['FOUND'] = (dreamDB::getInstance()->verify_credentials($_POST['username'], $_POST['userpassword']));
    if ($logonSuccess == true) {
        //session_start();
        $_SESSION['ualias'] = $_POST['username']; // $_SESSION['ualias'] = $_POST['ualias'];
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

?>
<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>Login Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    <script>
        function validate(form) {

            fail = validateUALIAS(form.username.value)
            fail += validatePassword(form.pwd.value)

            if (fail == "") {
                return true 
            } else { 
                alert(fail); return false 
            }
        }
    </script>
    <script src="js/parsing.js"></script>    
  <div class="new-user-header">
            <?php 
        if (!$logonSuccess) {
            echo "<h2 class=\"sign-up-header\">Oops! Something went wrong while signing in</h2><br>";
        } else {
            echo '<h2 class="sign-up-header">Please Log In</h2>';
        }
        ?>
           
  </div>
  <div>
    <div class="w-form">
        
      <form class="new-user-form" method="post" action="login.php" onSubmit="return validate(this)">
        
        <input class="w-input" id="username" type="text" placeholder="Username" name="username" value="<?php if(isset($_POST['username'])) { echo htmlentities ($_POST['username']); }?>"required="required" autofocus="autofocus" data-name="username">
        <input class="w-input" id="pwd" type="password" placeholder="Password" name="userpassword" required="required" autofocus="autofocus" data-name="pwd">
        <?php
        /** Display error messages if the "password" field is empty */
        if ($passwordIsEmpty) {
            echo ("Enter the password, please");
            echo ("<br/>");
        }
        ?>
      
        <input class="w-button entry-button" type="submit" value="Submit" data-wait="Please wait...">
      </form>
    </div>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>

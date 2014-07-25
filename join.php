<?php
/* code that updates DB using a db.php
CREATE operation
This PHP file registers a new user for the service
This PHP file redirects to itself on submission
The PHP block must be above all HTML code, empty lines, or whitespace. 
client side JS and server side PHP text validation occurs
additional SQL safe stripped occurs in db.php
 */ 
require_once("Includes/db.php");
session_start(); // session data needed to retrieve associated dream index

// 1st Session Check - Logged in Users (with existing registy in DB 
// cannot access this page again)

if (array_key_exists("ualias", $_SESSION)) {
    // if logged in they cannot access add new member screen
   header('Location: profile.php');
   exit;
} 
// 2nd session check, to account for user navigating using back button of browser

if (array_key_exists("user", $_SESSION)) {
    // user variable added on this page, if present this page redirects
   header('Location: complete.php');
   exit;
} 
// form validation booleans
$userNameIsUnique = true;
$passwordIsValid = true;				
$userIsEmpty = false;					
$passwordIsEmpty = false;				
$password2IsEmpty = false;

// validate that the page requested itself via POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if($_POST["ualias"] == ""){ // if username field is empty - this works
        $userIsEmpty = true; // reassign the boolean
    }           

    $userID = dreamDB::getInstance()->get_uid_by_alias($_POST["ualias"]);
        if($userID){
        $userNameIsUnique = false;
    }
    // validate password
    if ($_POST["pwd"] == ""){ // OK
        $passwordIsEmpty = true;
    }
    if ($_POST["pwd2"] == "") {
        $password2IsEmpty = true;
    }
    if ($_POST["pwd"] != $_POST["pwd2"]) {
        $passwordIsValid = false;
    }

    // complete main clause
    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid){
        //mysql_query("INSERT INTO mmbr_data SELECT MAX(usrid)+1, '". $_POST["name"] ."', '" . $_POST["email"]."', '" . $_POST["ualias"]."', '" . $_POST["pwd"]."', '" . $_POST["age"]."', '" . $_POST["gender"].  "' FROM mmbr_data ");
        dreamDB::getInstance()->create_user($_POST);
        //session_start();
        $_SESSION['user'] = $_POST['ualias'];
        header('Location: complete.php');
        exit;
    }
} // end Post To Self validation     
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>add new user</title>
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
                fail = validateName(form.name.value)
                //fail += validateSurname(form.surname.value)
                fail += validateUALIAS(form.ualias.value)
                fail += validatePassword(form.pwd.value)
                //fail += validateAge(form.age.value)
                fail += validateEmail(form.email.value)
                if (fail == "") {
                    return true 
                } else { 
                    alert(fail); return false 
                }
            }
  </script>
  <script src="js/parsing.js"></script>    
  <div class="new-user-header">
    <h1 class="sign-up-header">Start logging<br>dreams today</h1>
  </div>
  <div>
    <div class="w-form">
      <form class="new-user-form" method="post" action="join.php" onSubmit="return validate(this)">
        <input class="w-input" id="name-2" type="text" placeholder="Enter your name" name="name" value="<?php if(isset($_POST['name'])) { echo htmlentities ($_POST['name']); }?>" autofocus="autofocus" required="required">
        <input class="w-input" id="ualias" type="text" placeholder="Username" name="ualias" value="<?php if(isset($_POST['ualias'])&& !$userIsEmpty && $userNameIsUnique) { echo htmlentities ($_POST['ualias']); }?>"required="required" autofocus="autofocus" data-name="ualias">
        <?php
            /** Display error messages if "user" field is empty or there is already a user with that name */
            if ($userIsEmpty) {
                echo ("Enter your username, please!");
                echo ("<br/>");
            }
            if (!$userNameIsUnique) {
                echo ("That username already exists. Please try a new name");
                echo ("<br/>");
            }
        ?>
        <input class="w-input" id="email-2" type="email" placeholder="Enter your email address" name="email" value="<?php if(isset($_POST['email'])) { echo htmlentities ($_POST['email']); }?>" data-name="email" required="required" autofocus="autofocus">
        <input class="w-input" id="pwd" type="password" placeholder="Password" name="pwd" required="required" autofocus="autofocus" data-name="pwd">
        <?php
        /** Display error messages if the "password" field is empty */
        if ($passwordIsEmpty) {
            echo ("Enter the password, please");
            echo ("<br/>");
        }
        ?>
        <input class="w-input" id="pwd-2" type="password" placeholder="Please confirm your password" name="pwd2" required="required" autofocus="autofocus" data-name="pwd2">
        <?php
        /** Display error messages if the "password2" field is empty
         * or its contents do not match the "password" field
         */
        if ($password2IsEmpty) {
            echo ("Confirm your password, please");
            echo ("<br/>");
        }
        if (!$password2IsEmpty && !$passwordIsValid) {
            echo ("<div>The passwords do not match!</div>");
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

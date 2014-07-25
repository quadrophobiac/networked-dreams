<?php
/* code that updates DB using a db.php
CREATE operation
This PHP file complete user registration for the service
This PHP file redirects to itself on submission
The PHP block must be above all HTML code, empty lines, or whitespace. 
client side JS and server side PHP text validation occurs
additional SQL safe stripped occurs in db.php
method which autopopulate the form fields are present in Includes/functions.php
 */ 
require_once("Includes/db.php");
include "Includes/functions.php";
session_start(); // session data needed to retrieve associated dream index
//echo print_r($_SESSION)."<br>";
    // 1st Session Check - if no user variables in session, page cannot be accessed
    if (array_key_exists("ualias", $_SESSION) || array_key_exists("user", $_SESSION)) {
        // echo print_r($_SESSION)."<br>";
    } else {
       header('Location: index.php');
       exit;
    }

    // 2nd Session Check - if the user is registered, redirect to profile
    
    if (array_key_exists("ualias", $_SESSION) && $_SESSION['registered']) {
        // if logged in they cannot access add new member screen
       header('Location: profile.php');
       exit;
    }


if($_SERVER["REQUEST_METHOD"] == "POST"){
    // page can be accessed several ways - ergo user value can be stored in different session values
    if ($_SESSION['user']) {
    $uid = dreamDB::getInstance()->get_uid_by_alias($_SESSION['user']);
    }
    if ($_SESSION['ualias']) {
    $uid = dreamDB::getInstance()->get_uid_by_alias($_SESSION['ualias']);
    }
    
    dreamDB::getInstance()->member_details($uid, $_POST);
    $_SESSION['ualias'] = $_SESSION['user'];
    $_SESSION['registered'] = true;
    header('Location: profile.php');    
}
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->

<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>complete member registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  
    <script>
            $(function() {
                // passes parameter via GET call to method in functions.php
                $("#country").change(function() {
                    // when change detected in CSS selector ID: country
                    $("#city").load(
                    // populate CSS selector ID: country with data returned via
                    // GET request made to PHP file specified
                            "Includes/functions.php?choice=" + $("#country").val() 
                            ); 
                });
            });
    </script>

</head>
<body class="profile-body">
  <div class="complete-reg-div">
    <h3>Nearly there!&nbsp;<br>Complete to start logging</h3>
  </div>
  <div class="w-form entry-form">
    <form class="w-clearfix" id="email-form" name="email-form" data-name="Email Form" action="complete.php" method="POST">
      <label for="field">Age:</label>
      <?php echo date_picker("age"); ?>
      <label for="field-6">Gender</label>
      <select class="w-select" id="field-6" name="gender" required="required">
                <option>male</option>
                <option>female</option>
      </select>
      <label for="field-7">Relationship</label>
      <select class="w-select" id="field-7" name="married" required="required">
                <option value="1">Single</option>
                <option value="2">Married</option>
                <option value="3">Monogamous</option>
                <option value="4">Polyamorous</option>
                <option value="5">Divorced</option>
                <option value="6">Widowed</option>
      </select>
      <label for="field-8">Your Profession</label>
      
        <?php echo print_job(); ?>

      <label>Currently learning?</label>
      <div class="w-clearfix radio-div">
        <div class="w-radio w-clearfix radio-left">
          <input class="w-radio-input" id="radio" type="radio" name="learning" value="y" data-name="RadioY" required="required">
          <label class="w-form-label" for="radio">yes</label>
        </div>
        <div class="w-radio w-clearfix radio-right">
          <input class="w-radio-input" id="radio-2" type="radio" name="learning" value="n" data-name="RadioN" required="required">
          <label class="w-form-label" for="radio-2">no</label>
        </div>
      </div>
      <label class="complete-reg-label" for="field-3">Country</label>
      <?php echo print_country(); ?>
      <label for="field-10">City</label>
      <select class="w-select" id="city" name="city">
        <option value="">Select one...</option>
      </select>
      <label for="field-5">Your&nbsp;Passion!</label>
      <input class="w-input" id="field-5" type="text" placeholder="Your Passion" name="comment">
      <input class="w-button entry-button" type="submit" value="Complete" data-wait="Please wait...">
    </form>

  </div>
  <div></div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
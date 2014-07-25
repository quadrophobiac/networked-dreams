<?php
require_once("Includes/db.php");
include "Includes/functions.php";
session_start();
//echo print_r($_SESSION)."<br>";

// logic for when page posts to self
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    print_r($_POST)."<br>";
    if($_POST['radio'] == 1){
        dreamDB::getInstance()->alter_category($_SESSION['dream_id'], $_POST['category'], $_POST['comment']);
    } else {
        // just amend the comment
        dreamDB::getInstance()->amend_comments($_SESSION['dream_id'], $_POST['comment']);
    }
    // POST["radio"] // change = 1, no change = 0
    header('Location: profile.php');

} // end post to self
    
// first session check - is a user logged in
if (array_key_exists("ualias", $_SESSION)) {
        //echo "Hello " . $_SESSION['ualias'] ." of password ".$_SESSION['pwd']."<br>";
} else {
   header('Location: index.php');
   exit;
}

// second session check check that user has completed registration
if(!$_SESSION['registered']){
    header('Location: complete.php');
    exit;
}
// third session check - has a dream been logged for today
$uid = dreamDB::getInstance()->get_uid_by_alias($_SESSION['ualias']);
// retrieve user id
$_SESSION["uid"] = $uid;
$date = dreamDB::getInstance()->most_recent_dream($uid);
// retrieve the date of the most recently entered dream for assigned user id
$t = date("Ymd", time());
if (strtotime($t) == strtotime($date)) { 
// if a dream has already been entered
    $_SESSION["todaysDream"] = true;  
    ;
    //exit;
} else {
    $_SESSION["todaysDream"] = false;
    header('Location: profile.php');
}
$current = dreamDB::getInstance()->get_dream_category($_SESSION['dream_id']);
echo $current;
?>
<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>dream-data-entry</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script>
      function userSuggest() {
        document.getElementById("suggestion").style.display = "block"; 
        //document.getElementById("suggestion-prompt").style.display = "block";
        document.getElementById("category").style.display = "none";
        document.getElementById('label').innerHTML = 'please categorise your dream in a few words';
      }
      $(function() {
            $("#category").change(function() {
                console.log("catch change, value ="+($("#category").val()/1));
                if ($("#category").val()==100) {
                    console.log("catch users suggestion");
                    userSuggest();
                }
            });
        });
        $(function() {
            $("#change-cat").change(function() {
                console.log("want change");
                document.getElementById("category").style.display = "block";;

            });
        });

    //console.log("works");
        $(function() {
            $("#no-change-cat").change(function() {
                console.log("don't change");
                document.getElementById("category").style.display = "none";

            });
        });      
  </script>
  <style>
</style>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body class="profile-body">
  <div class="w-nav navbar_loggedin" data-collapse="tiny" data-animation="default" data-duration="400" data-contain="1">
    <div class="w-container">
      <a class="w-nav-brand" href="#"></a>
      <nav class="w-nav-menu" role="navigation"><a class="w-nav-link" href="profile.php">Home</a><a class="w-nav-link" href="logout.php">Log Out</a>
      </nav>
      <div class="w-nav-button">
        <div class="w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  <div>
    <div class="w-container">
      <div class="dream-entry-div">
        <h3>Edit last nights dream</h3>
      </div>
      <div class="w-form entry-form">
        <form id="email-form" action="edit.php" method="POST" >
          <label id="label" for="field">Change Dream Category?<br> Current = 
          <?php 
          echo humanRead($current);
          ?>
          </label>
          <div class="w-clearfix">
            <div class="w-radio w-clearfix radio-left">
              <input class="w-radio-input" id="change-cat" type="radio" name="radio" value="1" data-name="Radio" required="required">
              <label class="w-form-label" for="radio">Yes</label>
            </div>
            <div class="w-radio w-clearfix radio-no-free">
              <input class="w-radio-input" id="no-change-cat" type="radio" name="radio" value="0" data-name="Radio" required="required">
              <label class="w-form-label" for="radio-2">No</label>
            </div>
          </div>

          <select class="w-select" id="category" name="category" style='display: none;'>
<!--            <option value="">Select dream category...</option>-->
<!--            <option value="100">Something else?...</option>-->
            <option value="1">abandonment or betrayal</option>
            <option value="2">adventure or wish fulfilment</option>
            <option value="3">animal</option>
            <option value="4">bodily</option>
            <option value="5">chasing or pursuit</option>
            <option value="6">confrontation or fight</option>
            <option value="7">your deceased or ghosts</option>
            <option value="8">disaster</option>
            <option value="9">dreaming about dreaming</option>
            <option value="10">dying</option>
            <option value="11">exposed, caught unaware, or failure</option>
            <option value="12">falling</option>
            <option value="13">family</option>
            <option value="14">flying or swimming</option>
            <option value="15">household</option>
            <option value="16">losing control, forgetting, or disintegration</option>
            <option value="17">lover-relationship</option>
            <option value="18">problem solving or accomplishment</option>
            <option value="19">religion or spiritual</option>
            <option value="20">sex or desire</option>
            <option value="21">sports, play, competing</option>
            <option value="22">terrifying or phobia</option>
            <option value="23">transformation</option>
            <option value="24">trapped or paralyzed</option>
            <option value="25">violence</option>>
          </select>
          <label for="field-4">Edit Comments</label>
          <input class="w-input" id="field-4" type="text" placeholder="any additional comments" value="<?php echo dreamDB::getInstance()->comments($_SESSION['dream_id'])?>" name="comment">
          <input class="w-button entry-button" type="submit" value="Submit" data-wait="Please wait...">
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>

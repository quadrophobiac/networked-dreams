<?php
require_once("Includes/db.php");
session_start();

// logic for when page posts to self
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = $_SESSION["uid"];
    echo "trying to add data ".$_POST['category']." ".$_POST['comment']." for user ". $id ."<br>";
    $form = array(
            'ea' => array(
                'TABLE' => "activity",
                'VALS' => array()        
            ),
            'ppl' => array(
                'TABLE' => "people",
                'VALS' => array()     
            ),
            'obj' => array(
                'TABLE' => "object",
                'VALS' => array()     
            ),
            'plc' => array(
                'TABLE' => "locale",
                'VALS' => array()     
            )
        );
        foreach($_POST as $key=>$value){
        //echo "$key => $value <br>";
            if($key == 'comment' || $key == 'category' || $key == 'suggestion'){ // filter out the post vals that are not the ones I want
            // do nothing!
                
            }else {
                if($value){ // provided the form has text, grab the data
                $form[$key]["VALS"] = explode(',',$value);
                }
            } 
        }
    // commit categorisation data to DB, update todays dream boolean
    dreamDB::getInstance()->add_dream($id, $_POST['category'], $_POST['comment']);
    $_SESSION["todaysDream"] = true;
    
    $d_id = dreamDB::getInstance()->get_dreamid_by_uid($_SESSION['uid']);
    // is above flawed, gets users most recent dream id, rather than the most recent dream_id
    foreach ($form as $i) {
        if(sizeof($i["VALS"])>0){
        //dreamDB::getInstance()->tables($d_id, $i["VALS"], $i["Q"]);
        $x = dreamDB::getInstance()->many($d_id,$i["VALS"], $i["TABLE"]);
        
        }
    }
    echo "end of many function<br>";
    if ($_POST['category']==100){
        // user has made suggestions
        dreamDB::getInstance()->add_dream_suggestion($_POST['suggestion'], $d_id);
    } else {
        //
    }

    header('Location: wakingdata.php');
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
    header('Location: profile.php');
    //exit;
} else {
    $_SESSION["todaysDream"] = false;
}

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
      
  </script>
  <style>
/*    #suggestion {
      display: none;
    }
    #suggestion-prompt {
      display: none;
    }*/
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
        <h3>enter last nights dream</h3>
      </div>
      <div class="w-form entry-form">
        <form id="email-form" action="logdream.php" method="POST" >
          <label id="label" for="field">Category:</label>
          <input class="w-input" id="suggestion" type="text" placeholder="remember, less is more" name="suggestion"> 
          <select class="w-select" id="category" name="category" required="required">
            <option value="">Select dream category...</option>
            <option value="100">Something else?...</option>
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
          <label for="name">Embodied Activity:</label>
          <input class="w-input" id="name" type="text" placeholder="enter the emotions and actions from your dream" name="ea" data-name="Name">
          <label for="email">People</label>
          <input class="w-input" id="email" type="text" placeholder="enter people from your dreams" name="ppl" data-name="ppl">
          <label for="field-2">Objects</label>
          <input class="w-input" id="field-2" type="text" placeholder="enter objects you remember from your dream" name="obj">
          <label for="field-3">Places:</label>
          <input class="w-input" id="field-3" type="text" placeholder="enter the locations in your dream" name="plc">
          <label for="field-4">Comments</label>
          <input class="w-input" id="field-4" type="text" placeholder="any additional comments" name="comment">
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
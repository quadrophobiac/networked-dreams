<?php
// a file that logs what a user was doing the night previous to a dream
require_once("Includes/db.php");
session_start(); // session data needed to retrieve associated dream index

// logic for when page posts to itself

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $d_id = dreamDB::getInstance()->get_dreamid_by_uid($_SESSION['uid']);
    
    if (!$_POST['category-free']){
        $cause = $_POST['category-busy'];
        $timetype = "not_free";
    } else {
        $cause = $_POST['category-free'];
        $timetype = "free";
    }
    // update main cause table
    dreamDB::getInstance()->add_cause($d_id, $cause, $_POST['comment']);
    
    // if user suggestions has been stipulated, update the cause suggestion table
    
    if ($cause==100){
        dreamDB::getInstance()->add_cause_suggestion($_POST['suggestion'], $d_id, $timetype);
    } else {
         //echo "cause 100 logic else <br>";
    }
} // end post to self

// check that a user is logged in
if (array_key_exists("uid", $_SESSION)) {
    $dream_id = dreamDB::getInstance()->get_dreamid_by_uid($_SESSION['uid']);
    $_SESSION['dream_id'] = $dream_id;
    //echo "Hello " . $_SESSION['ualias'] ." of password ".$_SESSION['pwd']. " and user ID " .$_SESSION['uid']." and of dream id ".$dream_id."<br><br>";
    // most recent dream ID = SELECT MAX(d_id) FROM dream_data WHERE usrid = '$_SESSION['ualias']';
} else {
    //echo "uid not found<br>";
   header('Location: index.php');
   exit;
}

// check that an entry doesn't already exist for todays date
if (!$_SESSION["todaysDream"]) {
    header('Location: profile.php');
    exit;
}
// check that there exists no cause correlation for the users most recent dream

$correlated = (dreamDB::getInstance()->most_recent_cause($dream_id));
if ($correlated) {
    header('Location: profile.php');
    exit;
}

?>
<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>waking-data</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
  <script>
      // accommodate user suggestions
      function userSuggest() {
            document.getElementById("free-time").style.display = "none";;
            document.getElementById("no-free-time").style.display = "none";
            document.getElementById("suggestion").style.display = "block"; 
            document.getElementById("suggestion-prompt").style.display = "block";
        }
        $(function() {
            $("#free-time").change(function() {
                console.log("catch change, value ="+($("#free-time").val()/1));
                if ($("#free-time").val()==100) {
                    console.log("catch users suggestion");
                    userSuggest();
                }
            });
        });
        $(function() {
            $("#no-free-time").change(function() {
                console.log("catch change, value ="+($("#no-free-time").val()/1));
                if ($("#no-free-time").val()==100) {
                    console.log("catch users suggestion");
                    userSuggest();
                }
            });
        });

    //console.log("works");
        $(function() {
            $("#radio-free").change(function() {
                //console.log("works");
                document.getElementById("free-time").style.display = "block";;
                document.getElementById("no-free-time").style.display = "none";
            });
        });

    //console.log("works");
        $(function() {
            $("#radio-not").change(function() {
                //console.log("works");
                document.getElementById("free-time").style.display = "none";
                document.getElementById("no-free-time").style.display = "block";

            });
        });
</script>
<style>
/*    #suggestion {
      display: none;
}
#suggestion-prompt {
      display: none;
}

    #free-time {
      display: none;
}
    #no-free-time {
      display: none;
}*/
    
</style>
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
          <div class="w-form entry-form">
        <h3>what did you do yesterday, <br>before sleeping?</h3>
      </div>
        <label for="field">Did you have free time before bedtime?</label>
          <div class="w-clearfix">
            <div class="w-radio w-clearfix radio-left">
              <input class="w-radio-input" id="radio-free" type="radio" name="radio" value="Radio" data-name="Radio" required="required">
              <label class="w-form-label" for="radio">Free Time?</label>
            </div>
            <div class="w-radio w-clearfix radio-no-free">
              <input class="w-radio-input" id="radio-not" type="radio" name="radio" value="Radio 2" data-name="Radio" required="required">
              <label class="w-form-label" for="radio-2">No&nbsp;Free Time?</label>
            </div>
          </div>
        
      
            <form id="email-form" name="entry" action="wakingdata.php" method="POST">
                <label id="suggestion-prompt">please summarise your activity</label>
                <input class="w-input" id="suggestion" type="text" placeholder="we value your suggestion" name="suggestion">
                    <!--required="required">-->
                <select class="w-select" id="no-free-time" name="category-busy"> 
                    <!--required="required">-->
                <option value="">No free time huh?</option>
                <option value="100">something else... ?</option>
                <option value="1">A personal conflict that needed resolving</option>
                <option value="9">I was busy running errands</option>
                <option value="6">Too many household tasks to conclude</option>
                <option value="16">Too much overtime and/or deadlines</option>
                <option value="11">Working hard on my side projects</option>
              </select>

                <select class="w-select" id="free-time" name="category-free"> 
                    <!--required="required">-->
                <option value="">Good for you!</option>
                <option value="100">something else... ?</option>
                <option value="8">a live event</option>
                <option value="2">drinks</option>
                <option value="4">dinner</option>
                <option value="3">film</option>
                <option value="13">gym workout with friends</option>
                <option value="7">internet</option>
                <option value="12">quality 'me' time</option>
                <option value="5">quality time with good friends</option>
                <option value="10">reading</option>
                <option value="13">team sports</option>
                <option value="14">TV</option>
                <option value="15">video gaming</option>
              </select>
              <label for="field-4">Comments</label>
              <input class="w-input" id="field-4" type="text" placeholder="Example Text" name="comment">
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

<?php
require_once("Includes/db.php");
session_start(); // session data needed to retrieve associated dream index
//echo print_r($_SESSION)."<br>";

    if(!$_SESSION['registered'] && $_SESSION['user']){
        header('Location: complete.php');
        exit;
    }
    if (array_key_exists("ualias", $_SESSION)) {
        //echo print_r($_SESSION);
    } else {
       header('Location: index.php');
       exit;
    }    
    // check that a dream has been logged, if not redirect to entry page
    if (!$_SESSION["todaysDream"]) {
        header('Location: logdream.php');
        exit;
    } else {
        // set dream_id
        if(!$_SESSION['dream_id']){
        $dream_id = dreamDB::getInstance()->get_dreamid_by_uid($_SESSION['uid']);
        $_SESSION['dream_id'] = $dream_id;
        }
    }

    $correlated = (dreamDB::getInstance()->most_recent_cause($_SESSION['dream_id']));
    if (!$correlated) {
        header('Location: wakingdata.php');
        exit;
    }

    $topDream = dreamDB::getInstance()->topTopic($_SESSION['uid']);
    $topDream = preg_replace('/_/',' ',$topDream);
    
    $topPpl = dreamDB::getInstance()->topPerson($_SESSION['uid']);
    $topPpl = preg_replace('/_/',' ',$topPpl);
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>Profile Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body class="profile-body">
  <div class="w-nav navbar_loggedin" data-collapse="tiny" data-animation="default" data-duration="400" data-contain="1">
    <div class="w-container">
      <a class="w-nav-brand" href="#"></a>
      <nav class="w-nav-menu" role="navigation"><a class="w-nav-link" href="#">Home</a><a class="w-nav-link" href="logout.php">Log Out</a>
      </nav>
      <div class="w-nav-button">
        <div class="w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  <div>
    <div class="w-row">
      <div class="w-col w-col-6 settings-col">
        <div class="mmbr-card">
          <p><br>User name: <?php echo ucfirst($_SESSION['ualias'])?>
           <br><a href="">Account Settings</a>
            <br>
            <br>
            <br>
            <br></p>
        </div>
      </div>
      <div class="w-col w-col-6 dream-data-col">

            <div class="mmbr-card">
<!--            <div class="mmbr-card"> swap for above -->
          <p><br>Dream Data
          <p>Top Dream Topic: <b><?php echo ucwords($topDream)?></b></p>
          <p>Top Dream Person:<?php 
            if ($topPpl) {
            echo ucwords($topPpl);
            } else {
                echo " insufficient data logged ";
            }
          ?>!</p>
          <p><br>Last Fortnights Dream Entries &nbsp &nbsp &nbsp ▅ ▆ ▁ ▁ ▂ ▂ ▃ ▄ ▄ █ ▅ ▆ ▁ ▁</p>
	<!-- this is a placeholder Spark Visualisation -->
          <p><a href="edit.php">Amend Todays Dream</a><br>
          <p><a href=""><i>View Dream Graph Visualisation</i></a></p>
          
          <p><a href="dataviz.php">Compare Data To Community</a>
          
              <br></p>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>

<?php
//require_once("Includes/db.php"); // not needed for the JS viz
include "Includes/functions.php";
session_start();
    
if(!$_SESSION['registered'] && $_SESSION['user']){
    header('Location: complete.php');
    exit;
}

if (array_key_exists("ualias", $_SESSION)) {
    //echo "Hello " . $_SESSION['ualias'] ." of password ".$_SESSION['pwd']."<br><br>";
    //echo print_r($_SESSION);
} else {
   header('Location: index.php');
   exit;
}

//// check that a dream has been logged, if not redirect to entry page
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

// check that waking data has been entered, if not redirect to entry page
$correlated = (dreamDB::getInstance()->most_recent_cause($_SESSION['dream_id']));
if (!$correlated) {
    header('Location: wakingdata.php');
    exit;
}

?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<html data-wf-site="534e6311b153a38e6d0000e6">
<head>
  <meta charset="utf-8">
  <title>Data Visualisations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/networkeddreams.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <script type="text/javascript" src="http://d3js.org/d3.v2.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

</head>
<body class="profile-body">
  <div class="w-nav navbar_loggedin" data-collapse="medium" data-animation="default" data-duration="400" data-contain="1">
    <div class="w-container">
      <a class="w-nav-brand" href="#"></a>
      <nav class="w-nav-menu" role="navigation"><a class="w-nav-link" href="profile.php">Home</a>
      </nav>
      <div class="w-nav-button">
        <div class="w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  <div class="w-container main">
    <div class="head_div">
    </div>
    <div class="body_div">
      <div class="w-form">
          
        <form class="selector_box" action="#" onSubmit="return formUpdate(this)">
            <label class="dream_selector_label" for="dreams">Show me who dreams about:</label>
            <select class="w-select dream_selector" id="dreams" name="category" data-name="Dreams">
              
                <option value="">Select one...</option>
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
                <option value="25">violence</option>
          </select>
          <input class="w-button choose_cat_button" type="submit" value="Submit" data-wait="Please wait...">
        </form>

      </div>
      <div class="w-row returned_data">
        <div class="w-col w-col-8 chart">
            <div id="chart"></div>
        </div>
        <div class="w-col w-col-4 filter_column">
          <div class="show_dates">
              <button class="button" onclick="return addDataParamaters()">Show me</button>
            <div class="w-form">
              <form class="w-clearfix optional-date-range" id="email-form-3" name="email-form-3" data-name="Email Form 3">
                <label for="field-4">dates:</label>
                <select class="w-select date-from" id="field-4" name="field-4">
                  <option value="">start date</option>
                </select>
                <select class="w-select date-to" id="end-date" name="end-date" data-name="end date">
                  <option value="">end date</option>
                </select>
                <input class="w-button entry-button-1" type="submit" value="Submit" data-wait="Please wait...">
              </form>
            </div>
          </div>
<!--            <div class ="unLock">-->
          <div id = "filter" class="show_filters">
              
            <h3>Show&nbsp;Filters</h3>
            <div class="w-form">
              <form class="w-clearfix" id="email-form-2" name="email-form-2" data-name="Email Form 2">
                <div class="w-radio radio-left">
                  <input class="w-radio-input" id="radio" type="radio" name="radio" value="Radio" data-name="Radio">
                  <label class="w-form-label" for="radio">Female</label>
                </div>
                <div class="w-radio">
                  <input class="w-radio-input" id="radio-2" type="radio" name="radio" value="Radio 2" data-name="Radio">
                  <label class="w-form-label" for="radio-2">Male</label>
                </div>
                <label for="field-3">Age:</label>
                <select class="w-select" id="field-3" name="field-3">
                  <option value="">Select one...</option>
                  <option value="First">First Choice</option>
                  <option value="Second">Second Choice</option>
                  <option value="Third">Third Choice</option>
                </select>
                <label for="field">Relationship</label>
                <select class="w-select" id="field" name="field">
                  <option value="">Select one...</option>
                  <option value="1">Single</option>
                  <option value="2">Married</option>
                  <option value="3">Monogamous</option>
                  <option value="4">Polyamorous</option>
                  <option value="5">Divorced</option>
                  <option value="6">Widowed</option>
                </select>
                <label for="field-2">Job</label>
                <?php echo print_job(); ?>
                <input class="w-button demographic_button" type="submit" value="Submit" data-wait="Please wait...">
              </form>
            </div>
          </div>
<!--        </div>-->
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
<script type="text/javascript">
    // enable additional data parameters
    var auth = true;
      function addDataParamaters() {
        if (auth){  
          console.log("unlocking data fields"); 
          var elem = document.getElementById("filter"); elem.style.opacity = 1.0; //elem.style.pointer-events = "none";
          elem.style.pointerEvents = "auto";  
        } else {
          alert("feature unavailable to unregistered users");
        }
    return false; // to prevent reload
    }
</script>  
<script type="text/javascript">
    // script to handle update of data visualisaton
    var w = 500,
        h = 250;

// margin necessary for axes
    var margin = {top: 5, right: 5, bottom: 5, left: 5};

    var percent = d3.format('%');    
    var svg = d3.select("#chart") // think it needs hash .w-col w-col-8 data_column"
            .append("svg")
            .attr("width", w)
            .attr("height", h)
            .attr("class", "graph-svg-component")
            .append('g')
            .attr('transform', 'translate(' + [margin.left, margin.top] + ')');

function formUpdate(form){

    var url = parseInt(form.category.value);
    d3.json("Includes/jsonFetch.php?val="+url, function(json) {graph(json);});
            console.log("within the db.json section");

     function graph (jsondata) {
            var data = jsondata;

            var sum_d = 0;
            for (var d in data) {
                console.log(data[d].incidence);
                    sum_d += parseInt(data[d].incidence);
            } // sum_d = total dreams for category,used to infer percentage
            console.log(sum_d);
            svg.selectAll(".bar").remove(); // remove the existing bars
            svg.selectAll("text").remove(); // remove existing text

            var yScale = d3.scale.ordinal()
            .domain(d3.range(data.length)) // takes the length of data, which is array of JSON objects                          
            .rangeRoundBands([0, h], 0.15);

            var xScale = d3.scale.linear()
            .domain([0, 100]) // the domain is 100 because percentages visualised
            .range([0, w*4]);        

            // add axes - unused atm
            var xAxis = d3.svg.axis()
                .scale(xScale)
                .tickFormat(percent);
            // bars
            var bars = svg.selectAll(".bar")
                    .data(data)
                    .enter()
                    .append("rect")
                    .attr("class", function(d, i) {return "bar";})
                    .attr("x", function(d, i) {
                        return 0;
                    })
                    .attr("y", function(d, i) {
                        return yScale(i);
                     })
                    .attr("width", function(d, i) {
                        console.log(Math.round( (d.incidence/sum_d)* 100));
                        return xScale(Math.round( (d.incidence/sum_d)* 100) );
                    })
                    .attr("height", yScale.rangeBand())
            // labels
            var text = svg.selectAll("text")
                    .data(data)
                    .enter()
                    .append("text")
                    .attr("class", function(d, i) {return "label " + d.category;})
                    .attr("x", 1)
                    .attr("y", function(d, i) {return yScale(i) + 15;}) // no getting around kludgy addition of +15
                    .text( function(d) {return d.category + " (" + Math.round( (d.incidence/sum_d)* 100)  + "%)";})
                    .attr("font-size", "15px")
                    .style("font-weight", "bold");
    }
    return false; // must be included to prevent screen reloading
} // end encapsulating function
    </script>
</body>

</html>
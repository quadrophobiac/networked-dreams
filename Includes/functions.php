<?php
require_once("db.php");
/* 
 * A functions file that includes functions that autopopulate HTML dropdown
 * select boxes
 * An additional function is invoked only if this page is accessed via GET
 *  */

        
if($_SERVER["REQUEST_METHOD"] == "GET"){
    // below is called dynamically by complete.php   
    $choice = $_GET['choice'];

    $q = dreamDB::getInstance()->cities($choice);
    while ($row = mysqli_fetch_row($q)){
        //echo $row['name']."<br>";
        echo '"<option value = "'.$row[0].'">'. ucfirst($row[1]) . '</option>';
    }

}

function print_country(){
    $q = dreamDB::getInstance()->countries();
    $menu = '<select class="w-select" id="country" name="country" required="required">';
    while ($result = mysqli_fetch_row($q)) {
        $country = $result[1];

        $m = preg_match('/_/',$country);
        if($m){
        $country = preg_replace('/_/',' ',$country); // option for preg_split() to deal with Boznia and Herzongovina
        }
        $menu .= '
        <option value="' . $result[0] . '">' . ucwords($country) . '</option>';
        }

    $menu .= '</select>';
    return $menu;
    }
    
    function print_job(){
    $q = dreamDB::getInstance()->jobs();

    $menu = '<select class="w-select" id="field-8" id="job" name="job" required="required">';
    while ($result = mysqli_fetch_row($q)) {
        $profession = $result[1];

        $m = preg_match('/_/',$profession);
        if($m){
        $profession = preg_replace('/_/',' / ',$profession); // option for preg_split() to deal with Boznia and Herzongovina
        }
        $menu .= '
        <option value="' . $result[0] . '">' . ucwords($profession) . '</option>';
        }
    $menu .= '</select>';
    return $menu;
    }

    function date_picker($name, $startyear=NULL, $endyear=NULL) {
        
    // its a quirk of the styling that the year must be placed before the day
    // in the html to display correctly
        
    if($startyear==NULL) {
        $startyear = date("Y")-100;
    }
    if($endyear==NULL) {
        $endyear=date("Y");        
    } 

    $months=array('','January','February','March','April','May',
    'June','July','August', 'September','October','November','December');

    // Month dropdown
    //$html="<select name=\"".$name."month\">"; 
    $html = "<select class=\"w-select month\" id=\"month\" name=\"".$name."month\" required=\"required\">";

    for($i=1;$i<=12;$i++) {
       $html.="<option value='$i'>$months[$i]</option>";
    }
    $html.="</select> ";

        // Year dropdown
    //$html.="<select name=\"".$name."year\">";
    $html .= "<select class=\"w-select date-numerical\" id=\"year\" name=\"".$name."year\" required=\"required\">";

    for($i=$startyear;$i<=$endyear;$i++) {      
      $html.="<option value='$i'>$i</option>";
    }
    $html.="</select> ";
    
    // Day dropdown
    //$html.="<select name=\"".$name."day\">";
    $html .= "<select class=\"w-select date-numerical\" id=\"day\" name=\"".$name."day\" required=\"required\">";
    for($i=1;$i<=31;$i++) {
       $html.="<option $selected value='$i'>$i</option>";
    }
    $html.="</select> ";

    return $html;
}

function humanRead($param){
    $param = preg_replace('/_/',' ',$param);
    $param = ucwords($param);
    return $param;
}
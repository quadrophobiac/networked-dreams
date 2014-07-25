<?php
header("Content-Type: application/json");
require_once("db.php");

if (array_key_exists("val", $_GET)) {
    // fetch the value passed through HTML GET
    $cat = $_GET['val'];
//    echo "post val received ".$cat."!!! <br>";
} else {
//    echo "no post val received<br>";
    $cat = 0;
}


//echo $_POST['category'];
$q = dreamDB::getInstance()->categoryCorrelate($cat); // original call
    //$q = dreamDB::getInstance()->jsonTest();
$data = array();
    
for ($x = 0; $x < mysqli_num_rows($q); $x++) {
    $grab = mysqli_fetch_assoc($q);
    $grab["category"] = preg_replace('/_/',' ',$grab["category"]);
    $grab["category"] = ucwords($grab["category"]);
    //echo $grab["category"]."\n";
    $data[] = $grab;
    //$data[] = mysqli_fetch_assoc($q);
}
echo json_encode($data);    

?>
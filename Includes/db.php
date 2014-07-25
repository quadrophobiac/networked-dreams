<?php

/* 
Code derived by following this walkthrough
https://netbeans.org/kb/docs/php/wish-list-lesson1.html
dreamDB is designed as a singleton class, meaning that only one 
instance of the class is in existance at any one time. It is therefore useful to 
prevent any external instantiation of WishDB, which could create duplicate instances.
mysqli_query will return a mysqli_result object
^ class for above = thtp://www.php.net/manual/en/class.mysqli-result.php
 */

class dreamDB extends mysqli {
    
    // single instance of self shared among all instances
    private static $instance = null;
    
    private $user = "SQL_USER";
    private $pass = "SQL_PASSWORD";
    private $dbName = "dreamdemo"; // or beta prototype
    private $dbHost = "localhost"; // or live server
    
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()){
            exit('Connect Error ('.  mysqli_connect_errno().')'.  mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    } 
    
    public static function getInstance(){
        if(!self::$instance instanceof self){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    
    public function __clone() { // clone is a magic method?
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
    
    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }
    
    // READ FUNCTIONS
    
    public function verify_credentials($uname, $pwd){
//        $usrname = $this->real_escape_string($uname);
//        $password = $this->real_escape_string($pwd);
        $usrname = $this->safe($uname);
        $password = $this->safe($pwd);
        $result = $this->query("SELECT 1 FROM mmbr WHERE ualias = '" . $usrname . "' AND password = '" . $password . "'");
        return $result->data_seek(0); // returns a boolean at the 0 index of above field
    }
    
    public function completed_reg($uid) {
        $uid = $this->safe($uid); // new
        $result = $this->query("SELECT 1 FROM mmbr_profile WHERE usrid = ".$uid);
        return $result->data_seek(0);
    }
    
    public function cities($ID) {
        $id = $this->safe($ID);
        //$id = $this->real_escape_string($ID);
        return $this->query("SELECT city_id, name FROM mcity WHERE state_id=" . $id); // works, breaks encapsulation
    }
    
    public function countries() {
        return $this->query("SELECT state_id, name FROM mcountry"); // works, breaks encapsulation
    }
    public function jobs() {
        return $this->query("SELECT job_id, name FROM mjob"); // works, breaks encapsulation
    }
    
    public function comments($did){
        
        $comments = $this->query("SELECT comments from dream where d_id = $did");
        $row = $comments->fetch_row();
        return $row[0];
    }
    
    public function get_uid_by_alias($alias) {
        // fetches the userID based on alias
        $alias = $this->safe($alias);
        $user = $this->query("SELECT usrid FROM mmbr WHERE ualias = '".$alias."'");
        if ($user->num_rows > 0){
            $row = $user->fetch_row();
            return $row[0];
        } else {
            return null;
        }
    }
    
    public function get_dreamid_by_uid($uid) {
       // safe check not necessary, user does not input into these areas
       $dream = $this->query("SELECT MAX(d_id) from dream WHERE usrid = ".$uid);
       $row = $dream->fetch_row(); // not ideal, as no error check
       return $row[0];        
    }
    
    public function most_recent_cause($d_id) {
        $result=$this->query("SELECT 1 FROM cause WHERE d_id =".$d_id);
        return $result->data_seek(0); // which of these actually works??
    }
    
    public function most_recent_dream($uid){ 
        // returns a date value
        // utilised as part of logic ensuring one dream logged per day
        $date = $this->query("SELECT MAX(d_date) from dream WHERE usrid = ".$uid);
        $row = $date->fetch_row(); // not ideal, as no error check
        return $row[0];
    }
    
    public function get_dream_category($d_id){
        $q = $this->query("SELECT c.name AS category FROM dream d INNER JOIN dr_category c ON d.category = c.id WHERE d.d_id = ".$d_id);
        $row = $q->fetch_row(); // not ideal, as no error check
        return $row[0];
    }
    
    // DATA VIZ
    
    public function topTopic($uid){
        $top = $this->query("SELECT c.name AS category, COUNT( d.category ) AS incidence
                            FROM dream d
                            INNER JOIN dr_category c ON d.category = c.id
                            WHERE d.usrid = $uid
                            GROUP BY d.category
                            ORDER BY `incidence` DESC
                            LIMIT 0 , 1 ");
        $row = $top->fetch_row();
        return $row[0];
//        return $top;
    }
    
    public function topPerson($uid) {
        $top = $this->query("SELECT p.person, COUNT( p.person ) as incidence
        FROM dr_people p
        WHERE p.d_id IN (SELECT d_id from dream WHERE usrid = $uid)
        GROUP BY p.person
        ORDER BY incidence DESC LIMIT 0 , 1");
        $row = $top->fetch_row();
        return $row[0];
    }
   
    public function calendarCall() {
        return $this->query("SELECT category,COUNT(category) as occurrences from dream WHERE d_date = '2014-03-01' GROUP BY category");
    }
    
    public function categoryCorrelate($cat) {
        return $this->query("SELECT x.name as category, COUNT( c.cause_cat ) as incidence
        FROM dream d
        INNER JOIN cause c ON d.d_id = c.d_id
        INNER JOIN cause_dict x ON c.cause_cat = x.cause_id
        WHERE d.category =$cat
        GROUP BY c.cause_cat");
    }
    // CREATE

    public function create_user ($array){
        foreach($array as $key => $value){
            $test = $this->safe($value);
            $array[$key] = $test;
        }
        $name = $this->formatting($array["name"]);   
        $this->query("INSERT INTO mmbr SELECT MAX(usrid)+1, '". $name ."', '" . $array["email"]."', '" . $array["ualias"]."', '" . $array["pwd"]."' FROM mmbr ");
    }
    
    public function member_details($uid, $a) {
        $date = "'".$a["ageyear"]."-".$a["agemonth"]."-".$a["ageday"]."'";
        $comment = $this->safe($a["comment"]);
                                                                           // $a["gender"], $a["married"], $a["job"], $a["learning"] $a["country"], $a["city"]
        $this->query("INSERT INTO mmbr_profile VALUES (".$uid.",".$date.",'".$a["gender"]."', '".$a["married"]."', '".$a["job"]."', '".$a["learning"]."', '".$a["country"]."', '".$a["city"]."', '".$comment."')");
    }
    
    public function add_dream ($id, $category, $comment){
        $comment = $this->safe($comment);
        $this->query("INSERT INTO dream SELECT MAX(d_id)+1, '".$id."',CURDATE(),'".$category."', '".$comment."', NULL, NULL, NULL, NULL FROM dream" );                
    }
    
    public function add_dream_suggestion($suggestion, $d_id){
        $suggestion = $this->safe($suggestion);
        $suggestion = $this->formatting($suggestion);
        $this->query("INSERT INTO user_suggestion_dr SELECT MAX(id)+1,'".$suggestion."','".$d_id."' FROM user_suggestion_dr" );
    }
    
    public function add_cause_suggestion($suggestion, $d_id, $type){
        $suggestion = $this->safe($suggestion);
        $suggestion = $this->formatting($suggestion);
        $this->query("INSERT INTO user_suggestion_cause SELECT MAX(id)+1,'".$suggestion."','".$d_id."','".$type."' FROM user_suggestion_cause" );
    }    
    
    public function many($d_id, $array, $table){
        $string = "";
        $qID = $this->query("SELECT MAX( id ) +1 FROM dr_".$table); // get query object
        $row = $qID->fetch_row(); // create an array listing of above object
        $id = $row[0]; // access item indexed at 0 for value desired
        foreach($array as $e){
            $entry = $this->safe($e);
            $entry = $this->formatting($entry);
            $this->query("INSERT INTO dr_".$table." VALUES('".$id."','".$d_id."','".$entry."')");
            $id++;
        }
        //return $string; - if debug
    }
    
    public function add_cause($dID, $causeID, $comment){
        $comment = $this->safe($comment);
        $this->query("INSERT INTO cause SELECT MAX(id)+1, '". $dID . "', '".$causeID."', '". $comment . "' from cause"); 
    }

    // UPDATE
    
    public function amend_comments($did, $nucomment) {
        
        $this->query('UPDATE dream SET comments = "'.$nucomment.'" WHERE d_id ='.$did); 
    }
    
    public function alter_category($did, $cat, $nucomment) {
        $this->query('SET foreign_key_checks = 0');
        $this->query('UPDATE dream SET category = '.$cat.', comments = "'.$nucomment.'" WHERE d_id ='.$did); 
        $this->query('SET foreign_key_checks = 1');
        
    }
    
// SQL syntax for returning people from a dream   select person from dr_people where d_id = 1477
            
    private function formatting($param){
        $param = trim($param);
        $param = preg_replace('/-/','_', $param);
        $param = preg_replace('/ /',' _ ', $param);
        $param = preg_replace('/[^\w\']+|\'(?!\w)|(?<!\w)\'/', '',$param);
        $param = strtolower($param);
        return $param;
    }
    
    private function safe($input) {
        $safe = strip_tags($input);
        $safe = $this->real_escape_string($safe);
        return $safe;
    }
} // end of class

?> 

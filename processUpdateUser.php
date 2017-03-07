<?php
/**
 * Created by PhpStorm.
 * User: PraviinM
 * Date: 9/28/15
 * Time: 9:54 PM
 */


require_once("config.php");

// print_r is to display the contents of an array() in PHP.
//print_r($_POST);

// Assigning $_POST values to individual variables for reuse.
$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$dob = $_POST['dateofbirth'];
$email = $_POST['email'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$thisuserid = $_POST['userid'];


if($_POST['updateUser']) {
    $updatedRecord  = updateThisRecord($fname, $lname, $city, $zip, $dob, $email, $thisuserid);
    echo $updatedRecord . " has been updated";
}
//You can do an else, but I prefer a separate statement
//if($_POST['deleteUser']) {
//    //User hit the Submit for Approval button, handle accordingly
//    deleteThisRecord($thisuserid,$fname,$lname,$email);
//    echo $fname . $lname ." has been deleted";


if($_POST['inActivateUser']) {
    //User hit the Submit for Approval button, handle accordingly
    $updatedcolumn = inActivate($thisuserid);
    echo"<br/> <br/>". $fname . $lname ." has been In-Activated";
}
if($_POST ['fakedelte']){
    $fdelte = fakedeleteuser($thisuserid);
    echo"<br/> <br/>". $fname . $lname ." has been Fake Deleted";


}

//Creating a variable to hold the "@return boolean value returned by function updateThisRecord - is boolean 1 with
//successfull and 0 when there is an error with executing the query .




//display the result that was returned by the createNewUser function - in this case we usually get a 1 when the
//update is completed successfully.


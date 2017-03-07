<?php
/**
 * Created by PhpStorm.
 * User: PraviinM
 * Date: 2/7/16
 * Time: 3:29 PM
 */

//print_r($_POST);

require_once("config.php");

// Assigning $_POST values to individual variables for reuse.
$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$dob = $_POST['dateofbirth'];
$email = $_POST['emailaddress'];

//print $fname;


//Creating a variable to hold the "@return boolean value returned by function createNewUser - is boolean 1 with
//successfull and 0 when there is an error with executing the query .

$newuser = createNewUser($fname, $lname, $dob, $email, $city, $zip);
?>
<br/>
<br/>
<?php
//display the result that was returned by the createNewUser function - in this case we usually get a 1 when the
//insert is completed successfully.
echo "User Successfully Created <br/> <br/> ";
echo "$newuser";
?>

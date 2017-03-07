<?php
/**
 * Created by PhpStorm.
 * User: PraviinM
 * Date: 2/7/16
 * Time: 2:17 PM
 */



//Retrieve information for all users
/**
 * @return array
 */
function fetchAllUsers() {
  global $mysqli, $db_table_prefix;
  $stmt = $mysqli->prepare(
    "SELECT
		id,
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active,
		Display

		FROM " . $db_table_prefix . "user"
  );
  $stmt->execute();
  $stmt->bind_result(
    $id,
    $userid,
    $FirstName,
    $LastName,
    $City,
    $Zip,
    $DateOfBirth,
    $EmailAddress,
    $MemberSince,
    $active,
    $display
  );



  while ($stmt->fetch()) {

    $row [] = array(
      'id'                      => $id,
      'userid'                  => $userid,
      'firstname'               => $FirstName,
      'lastname'                => $LastName,
      'city'                    => $City,
      'zip'                     => $Zip,
      'dateofbirth'             => $DateOfBirth,
      'email'                   => $EmailAddress,
      'membersince'             => $MemberSince,
      'active'                  => $active,
        'fakedelte'             => $display
    );
  }

  $stmt->close();
  return ($row);
}




//Create a new user

/**
 * @param $fname
 * @param $lname
 * @param $dob
 * @param $email
 * @param $city
 * @param $zip
 *
 * @return bool
 */
function createNewUser($fname, $lname, $dob, $email, $city, $zip)
{
  $ans = checkIfUserExists($fname,$lname,$email,$dob);
  if ($ans)
    {
        echo "The User already exists. Please see if following are your details";
?>
        <table style="border: 1px solid; background-color: #F7CFCF;" class="table-style-three">
            <thead>
                <!-- display user details header  -->
                <th style="border: 1px solid;">First Name</th>
                <th style="border: 1px solid;">Last Name</th>
                <th style="border: 1px solid;">DateOfBirth</th>
                <th style="border: 1px solid;">EmailAddress</th>
            </thead>
        <tbody>
<?php
      foreach($ans as $displayRecord) { ?>
          <tr>
              <td style="border: 1px solid;"><?php print $displayRecord['firstname']; ?></td>
              <td style="border: 1px solid;"><?php print $displayRecord['lastname']; ?></td>
              <td style="border: 1px solid;"><?php print date("Y-m-d", $displayRecord['dateofbirth']); ?></td>
              <td style="border: 1px solid;"><?php print $displayRecord['email']; ?></td>

          </tr>
      <?php } ?>
</tbody>
</table>
<?php
}
  else
  {
      global $mysqli;

      //Generate A random userid
      $character_array = array_merge(range('a', 'z'), range(0, 9));
      $rand_string = "";
      for ($i = 0; $i < 6; $i++) {
          $rand_string .= $character_array[rand(
              0, (count($character_array) - 1)
          )];
      }

      $random = fetchThisUser($rand_string);
      if ($random != null)
      {
          echo $random. " is already registered in the database";
          createNewUser($fname,$lname,$dob,$email,$city,$zip);
      }

      echo $rand_string;
      echo $fname;
      echo $lname;
      echo $dob;
      echo $email;
      echo $city;
      echo $zip;
      $stmt = $mysqli->prepare(
          "INSERT INTO user (
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active
		)
		VALUES (
		'" . $rand_string . "',
		?,
		?,
		?,
		?,
		?,
		?,
        '" . time() . "',
        1
		)"
      );
      $stmt->bind_param("ssssss", $fname, $lname, $city, $zip, $dob, $email);
      $result = $stmt->execute();
      $stmt->close();
      return $result;

  }

}

function checkIfUserExists($fname,$lname,$email,$dob)
{
    global $mysqli;
    $stmt = $mysqli->prepare(
        "
    SELECT
    id,
    userid,
    FirstName,
    LastName,
    DateOfBirth,
    EmailAddress,
    City,
    Zip,
    MemberSince,
    active

    FROM user
    WHERE
    FirstName=? AND
    LastName=? AND
    DateOfBirth=? AND
    EmailAddress=?
    
    LIMIT 1"
    );
    $stmt->bind_param("ssss",$fname, $lname, $dob, $email);
    $stmt->execute();
    $stmt->bind_result($id, $userid, $FirstName, $LastName, $DateOfBirth, $EmailAddress, $City, $Zip, $MemberSince, $active);
    $stmt->execute();
    $row = array();
    while ($stmt->fetch()) {
        $row[] = array(
            'id'                      => $id,
            'userid'                  => $userid,
            'firstname'               => $FirstName,
            'lastname'                => $LastName,
            'city'                    => $City,
            'zip'                     => $Zip,
            'dateofbirth'             => $DateOfBirth,
            'email'                   => $EmailAddress,
            'membersince'             => $MemberSince,
            'active'                  => $active

        );
    }
    $stmt->close();

    return ($row);
}

//fetch particular users record

/**
 * @param $userid
 *
 * @return array
 */
function fetchThisUser($userid)
{
    global $mysqli;
    $stmt = $mysqli->prepare(
      "SELECT
    id,
    userid,
    FirstName,
    LastName,
    DateOfBirth,
    EmailAddress,
    City,
    Zip,
    MemberSince,
    active

    FROM user
    WHERE
    userid = ?
    LIMIT 1"
    );
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->bind_result($id, $userid, $FirstName, $LastName, $DateOfBirth, $EmailAddress, $City, $Zip, $MemberSince, $active);
    $stmt->execute();
    $row = array();
  while ($stmt->fetch()) {
    $row[] = array(
      'id'                      => $id,
      'userid'                  => $userid,
      'firstname'               => $FirstName,
      'lastname'                => $LastName,
      'city'                    => $City,
      'zip'                     => $Zip,
      'dateofbirth'             => $DateOfBirth,
      'email'                   => $EmailAddress,
      'membersince'             => $MemberSince,
      'active'                  => $active

    );
  }
  $stmt->close();
  return ($row);
}


//Update selected users record.
/**
 * @param $fname
 * @param $lname
 * @param $city
 * @param $zip
 * @param $dob
 * @param $email
 * @param $userid
 *
 * @return bool
 */
function updateThisRecord($fname, $lname, $city, $zip, $dob, $email, $userid)
{
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare(
      "UPDATE " . $db_table_prefix . "user
		SET
		FirstName = ?,
		LastName = ?,
		City = ?,
		Zip = ?,
		DateOfBirth = ?,
		EmailAddress = ?
		WHERE
		userid = ?
		LIMIT 1"
    );
    $stmt->bind_param("sssssss", $fname, $lname, $city, $zip, $dob, $email, $userid);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}


function displayUsersStartWith($alphabet){
    global $mysqli;
    $stmt = $mysqli->prepare(
        "SELECT
		id,
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active

		FROM user WHERE FirstName like '" . $alphabet . "%'"
    );
    $stmt->execute();
    $stmt->bind_result(
        $id,
        $userid,
        $FirstName,
        $LastName,
        $City,
        $Zip,
        $DateOfBirth,
        $EmailAddress,
        $MemberSince,
        $active
    );

    while ($stmt->fetch()) {
        $row [] = array(
            'id'                      => $id,
            'userid'                  => $userid,
            'firstname'               => $FirstName,
            'lastname'                => $LastName,
            'city'                    => $City,
            'zip'                     => $Zip,
            'dateofbirth'             => $DateOfBirth,
            'email'                   => $EmailAddress,
            'membersince'             => $MemberSince,
            'active'                  => $active
        );
    }
    $stmt->close();
    return ($row);

}

function displayFirstTen(){
    global $mysqli;
    $stmt = $mysqli->prepare(
        "SELECT
		id,
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active

		FROM user ORDER BY id ASC 
		LIMIT 10"
    );
    $stmt->execute();
    $stmt->bind_result(
        $id,
        $userid,
        $FirstName,
        $LastName,
        $City,
        $Zip,
        $DateOfBirth,
        $EmailAddress,
        $MemberSince,
        $active
    );

    while ($stmt->fetch()) {
        $row [] = array(
            'id'                      => $id,
            'userid'                  => $userid,
            'firstname'               => $FirstName,
            'lastname'                => $LastName,
            'city'                    => $City,
            'zip'                     => $Zip,
            'dateofbirth'             => $DateOfBirth,
            'email'                   => $EmailAddress,
            'membersince'             => $MemberSince,
            'active'                  => $active
        );
    }
    $stmt->close();
    return ($row);

}

function displayLastTen(){
    global $mysqli;
    $stmt = $mysqli->prepare(
        "SELECT
		id,
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active

		FROM user ORDER BY id DESC 
		LIMIT 10"
    );
    $stmt->execute();
    $stmt->bind_result(
        $id,
        $userid,
        $FirstName,
        $LastName,
        $City,
        $Zip,
        $DateOfBirth,
        $EmailAddress,
        $MemberSince,
        $active
    );

    while ($stmt->fetch()) {
        $row [] = array(
            'id'                      => $id,
            'userid'                  => $userid,
            'firstname'               => $FirstName,
            'lastname'                => $LastName,
            'city'                    => $City,
            'zip'                     => $Zip,
            'dateofbirth'             => $DateOfBirth,
            'email'                   => $EmailAddress,
            'membersince'             => $MemberSince,
            'active'                  => $active
        );
    }
    $stmt->close();
    return ($row);

}

function deleteThisRecord($fname,$lname,$email)
{
    global $mysqli;
    $stmt = $mysqli->prepare(
        "DELETE FROM user  
		WHERE
		FirstName = '" .$fname . "' AND LastName = '" .$lname . "' AND EmailAddress = '".$email ."'"

    );
//    $stmt->bind_param("sssssss", $fname, $lname, $city, $zip, $dob, $email, $userid);
    $result = $stmt->execute();
    $stmt->close();

//    return $result;
}

function inActivate($thisuser)
{
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare(
        "UPDATE user
		SET
		Active = 0
		WHERE
		userid = '" . $thisuser ."'"
		
    );

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

function fakedeleteuser($thisuser)
{
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare(
        "UPDATE user
		SET
		Display = 1
		WHERE
		userid = '" . $thisuser ."'"

    );

    $result = $stmt->execute();
    $stmt->close();

    return $result;

}




function deleteOlderMembers()
{
    global $mysqli;
    $stmt = $mysqli->prepare(
        "DELETE FROM user WHERE MemberSince > date_sub(now(), INTERVAL 6 MONTH )"
    );

    $stmt->execute();
    $stmt->close();

}

function fetchOlderUsers()
{
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare(
        "SELECT
		id,
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active

		FROM " . $db_table_prefix . "user where MemberSince > date_sub(now(), INTERVAL 6 MONTH)"
    );
    $stmt->execute();
    $stmt->bind_result(
        $id,
        $userid,
        $FirstName,
        $LastName,
        $City,
        $Zip,
        $DateOfBirth,
        $EmailAddress,
        $MemberSince,
        $active
    );
    $row = array();
    while ($stmt->fetch()) {
        $row [] = array(
            'id'                      => $id,
            'userid'                  => $userid,
            'firstname'               => $FirstName,
            'lastname'                => $LastName,
            'city'                    => $City,
            'zip'                     => $Zip,
            'dateofbirth'             => $DateOfBirth,
            'email'                   => $EmailAddress,
            'membersince'             => $MemberSince,
            'active'                  => $active
        );
    }
    $stmt->close();
    return ($row);
}


?>

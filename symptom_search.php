<?php
	@session_start();
	$language = $_SESSION['language'];
	include_once '../../db_settings.php';

  //$conn = array('server' => "146.148.127.213", 'login' => "root", 'pass' => "rantapallo1423", 'db' => "oirearvio_db");

  $conn = mysqli_connect("146.148.127.213", "root", "rantapallo1423", "oirearvio_db");

  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	$key=$_GET['key'];
  $array = array();
  $result = mysqli_query($conn, "SELECT * FROM disease_table");
  while($row=mysqli_fetch_assoc($result)) {
  	$array[] = array("id" => $row['id'], "value" => $row['name']);
	}

  mysqli_close($con);

	echo json_encode($array);
?>
<?php
	@session_start();
	$language = $_SESSION['language'];
	include_once '../../db_settings.php';
  include_once('../oirearvio_api.php');

  $key=$_GET['key'];

  $oire = new oa($key);

  echo json_encode($oire->similars['symptoms']);


?>

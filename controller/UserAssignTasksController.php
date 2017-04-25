<?php
	function __autoload($className) {
		require_once "../model/" . $className . '.php';
	}
	
	session_start ();
	if (! isset ( $_SESSION ['user'] )) {
		header ( 'Location:../view/index.php' );
	}
	
	$sessionVars = json_decode($_SESSION['user'], true);
	$user_id = $sessionVars['id'];
	
	try {
		$assignTasks = TaskDAO::getUserAssignTasks( $user_id );
	} catch ( Exception $e ) {
		$message = $e->getMessage ();
	}
	
	//var_dump($assignTasks);
	include '../view/mytasks.php';
?>
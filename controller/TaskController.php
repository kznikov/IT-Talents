<?php

	function __autoload($className) {
		require_once "../model/" . $className . '.php';	
	}
	
	session_start();
	$userId = json_decode($_SESSION['user'],true)['id'];
	$userId = (int)($userId);
	
	 if (isset($_POST['submit'])) {
		 try {

		 	$projectId = htmlentities(trim($_POST['project']));
		 	$title = htmlentities(trim($_POST['title']));
		 	$owner = htmlentities(trim($_POST['owner']));
		 	$description = htmlentities(trim($_POST['description']));
		 	$type = htmlentities(trim($_POST['type']));
		 	$priority = htmlentities(trim($_POST['priority']));
		 	$status = htmlentities(trim($_POST['status']));
		 	$progress =  htmlentities(trim($_POST['progress']));
		 	$startDate = htmlentities(trim($_POST['start_date']));
		 	$endDate = htmlentities(trim($_POST['end_date']));
		 	
		 	if(!empty($projectId) && !empty($title) && !empty($owner)){
				
		 		$task = new Task($title, $projectId, $userId, $owner, $type, $priority, $status, $progress, $description, $startDate, $endDate, $id = null);

			 	//var_dump($task);
				$taskData = new TaskDAO();
				
				$result = $taskData->createTask($task);
				
				 if(!$result){
					throw new Exception("Failed to create new task!");
				}else{
					$message = "Task $title successfully created.";
					$class = "flash_success";
					include '../view/alltasks.php';
				} 
			}else{
				throw new Exception("Failed to create new task!");
			}
			
			
		}catch (Exception $e) {

			$message = $e->getMessage();
			//$row = $e->getLine(); 
			$class = "flash_error";
			include '../view/alltasks.php';
		}
	}else{
		$message = "Failed to create new project!";
		$class = "flash_error";
		include '../view/alltasks.php';
	}
	
	//include '../view/index.php';
?>
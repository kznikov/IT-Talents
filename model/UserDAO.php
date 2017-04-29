<?php

require_once "../model/IUserDAO.php";

	class UserDAO implements IUserDAO {
		
		
		private $db;
		
		
		const GET_AND_CHECK_USER_SQL = "SELECT * FROM users WHERE username = ? AND password = ?";

		const CHECK_IF_USER_EXIST = "SELECT username, id FROM users WHERE username = ?";
		
		const CHECK_IF_EMAIL_EXIST = "SELECT username, id FROM users WHERE email = ?";

		const REGISTER_NEW_USER_SQL = "INSERT INTO users (username, password, firstname, lastname, email, created) 
																					VALUES (?, ?, ?, ?, ?, NOW())";
		const UPDATE_TOKEN = "UPDATE users SET token = ? WHERE email LIKE ?";
		const RESET_PASSWORD = "UPDATE users SET password = ?, token=''  WHERE email LIKE ? AND token LIKE ?";
		
		const UPDATE_LOGIN = "UPDATE users SET first_login = 1 WHERE id = ?";
		
		const SAVE_IMAGE = "UPDATE `users` SET `avatar`= ? WHERE id= ?";

		const GET_IMAGE = "SELECT avatar FROM users WHERE id = ?";

		const GET_INFO_USER = "SELECT * FROM users WHERE id = ?";

		const UPDATE_INFO_USER = "UPDATE users SET username = ?, password = ?, firstname = ?, lastname = ?, email = ?, phone = ?, mobile = ?, last_upd =  NOW(), avatar = ? WHERE id = ?";

		const SELECT_ALL =  "SELECT * FROM `users` ORDER BY firstname";

		const DELETE_USER = "DELETE FROM users WHERE id=:id";
		
		const GET_ALL_USERNAMES = "SELECT username FROM users WHERE username LIKE CONCAT('%',?,'%') ORDER BY username";
		
		const SEARCH_FOR_USERS = "SELECT * FROM users WHERE MATCH (username,firstname, lastname, email) AGAINST
																 (? IN BOOLEAN MODE) HAVING email LIKE '%@%';";
		
		const GET_PROJECT_ASSOC_USERS = "SELECT * from (SELECT u.* FROM users u JOIN user_projects up
							ON u.id = up.user_id JOIN projects p ON up.project_id = p.id WHERE p.name LIKE ?) as users
												union (SELECT u.* FROM users u JOIN projects p ON p.admin_id = u.id WHERE p.name LIKE ?)";
		
		
		public function __construct() {
			$this->db = DBConnection::getDb();
		}
		
		
		public function loginUser(User $user) {
			try{
				$pstmt = $this->db->prepare(self::GET_AND_CHECK_USER_SQL);
				$pstmt->execute(array($user->username,  hash('sha256',$user->password)));
				
				$res = $pstmt->fetchAll(PDO::FETCH_ASSOC);
				
				if (count($res) === 0)
					throw new Exception("Try again!");
				
				$user = $res[0];
				
				if(!$user['first_login']){
					$firstlogin = true;
					$pstmt = $this->db->prepare(self::UPDATE_LOGIN);
					$pstmt->execute(array($user['id']));
				}else{
					$firstlogin = false;
				}
				
				return new User($user['username'], 'p', $user['firstname'], $user['lastname'], $user['email'],
											$firstlogin, $user['phone'], $user['mobile'], $user['avatar'], $user['id']);
			}catch (Exception $e){
				throw $e;
			}
		}

		public function checkUserName($username) {
			try{
				$pstmt = $this->db->prepare(self::CHECK_IF_USER_EXIST);
				$pstmt->execute(array($username));
				
				$res = $pstmt->fetchAll(PDO::FETCH_ASSOC);
	
				if (count($res) === 0) {
					return true;
				} else {
					return false;
				}
			}catch(Exception $e){
				throw new Exception("Something went wrong, please try again later!");
			}
			
		}

		public function checkEmail($email) {
			try{
				$pstmt = $this->db->prepare(self::CHECK_IF_EMAIL_EXIST);
				$pstmt->execute(array($email));
				
				$res = $pstmt->fetchAll(PDO::FETCH_ASSOC);
	
				if (count($res) === 0) {
					return true;
				} else {
					return false;
				}
			}catch(Exception $e){
				throw new Exception("Something went wrong, please try again later!");
			}
			
		}
		
		public function registerUser(User $user) {
			try{				
				$pstmt = $this->db->prepare(self::REGISTER_NEW_USER_SQL);
	
				
				if ( $pstmt->execute(array($user->username, hash('sha256',$user->password),
														$user->firstname, $user->lastname, $user->email))){
					$user->__set('id', $this->db->lastInsertId());
					return $user;
					
				}else{
					throw new Exception("Unsuccessful registration!");
				}
			}catch (PDOException $e){
				throw $e;
				//echo $e->getMessage();
			}
			
		}
		
		public function saveImage($name, $id) {
			$pstmt = $this->db->prepare(self::SAVE_IMAGE);
			$pstmt->execute(array($name, $id));
		}

		public function updateUser(User $user) {
			try{
				$pstmt = $this->db->prepare(self::UPDATE_INFO_USER);
				$pstmt->execute(array($user->username, $user->password, $user->firstname,
													$user->lastname, $user->email, $user->phone, $user->mobile, $user->avatar, $user->id ));			
			}catch(Exception $e){
				throw new Exception("Something went wrong, please try again later!");
			}
		}

		public function getImage($id) {	
			try{
				$pstmt = $this->db->prepare(self::GET_IMAGE);
				$pstmt->execute(array($id));
				
				$res = $pstmt->fetchAll(PDO::FETCH_ASSOC);
	
				return $res;
			}catch(Exception $e){
				throw new Exception("Something went wrong, please try again later!");
			}
			
		}

		public function getInfoUser($id) {	
			try{
				$pstmt = $this->db->prepare(self::GET_INFO_USER);
				$pstmt->execute(array($id));
				
				if($res = $pstmt->fetchAll(PDO::FETCH_ASSOC)){
					$user = $res[0];
				}else{
					throw new Exception("This user does not exists!");
				}
	
				return new User($user['username'], $user['password'], $user['firstname'], $user['lastname'], $user['email'],
						$user['first_login'], $user['phone'], $user['mobile'], $user['avatar'], $user['id']);
			}catch (Exception $e){
				throw new Exception("Something went wrong, please try again later!");
			}
			
		}

		public function selectUser() {
			try{
				$pstmt = $this->db->query(self::SELECT_ALL);
				$res = $pstmt->fetchAll(PDO::FETCH_ASSOC);
				return $res;
			}catch(Exception $e){
				throw new Exception("Something went wrong, please try again later!");
			}
		}

		public function deleteUser($id) {
			try{
				$pstmt = $this->db->prepare(self::DELETE_USER);
				$pstmt->execute(array(":id"=>$id));
			}catch (Exception $e){
				echo $e->getMessage();
			}
			
		}
		
		public function forgotPassword($email, $token) {
			try{
				$pstmt = $this->db->prepare(self::UPDATE_TOKEN);
				if(!self::checkEmail($email)){
					return $pstmt->execute(array($token, $email));
				}
				return false;
			}catch (Exception $e){
				echo $e->getMessage();
			}
				
		}
		
		
		public function resetPassword($email, $token, $pass) {
			try{
				$pstmt = $this->db->prepare(self::RESET_PASSWORD);
				
				 $pstmt->execute(array(hash('sha256', $pass), $email, $token));
				 return $pstmt->rowCount();
			}catch (Exception $e){
				throw $e;
			}
		}
		
		
		
		public function getProjectAssocUsers($projectName) {
			
			try{
				$pstmt = $this->db->prepare(self::GET_PROJECT_ASSOC_USERS);
				$pstmt->execute(array($projectName, $projectName));
				
				$users = $pstmt->fetchAll(PDO::FETCH_ASSOC);
				
				$assocUssers = array();
				foreach ($users as $user){
					$assocUssers [] = new User($user['username'], 'p', $user['firstname'], $user['lastname'],
															$user['email'], null, $user['phone'], $user['mobile'], $user['avatar'], null);
				}
				return $assocUssers;
				
			} catch(Exception $e){
				throw new Exception("Something went wrong, please try again later!");
			}
		}


		public function getRealUserIp(){
		    switch(true){
		      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
		      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
		      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
		      default : return $_SERVER['REMOTE_ADDR'];
		    }
		 }
		 
		 
		 public function getAllUsernames($keyword){
		 	try{
		 		$pstmt = $this->db->prepare(self::GET_ALL_USERNAMES);
		 		$pstmt->execute(array($keyword));
		 		
		 		$usernames = $pstmt->fetchAll(PDO::FETCH_ASSOC);
		 		
		 		return $usernames;
		 		
		 	} catch(Exception $e){
		 		throw new Exception("Something went wrong, please try again later!");
		 	}
		 }
		 
		 
		 
		 public function serchUsers($keyword){
		 	try{
		 		$pstmt = $this->db->prepare(self::SEARCH_FOR_USERS);
		 		$pstmt->execute(array($keyword));
		 		
		 		$users = $pstmt->fetchAll(PDO::FETCH_ASSOC);
		 		
		 		$allUsers = array();
		 		foreach ($users as $user){
		 			$allUsers [] = new User($user['username'], 'p', $user['firstname'], $user['lastname'], $user['email'],
		 					$user['first_login'], $user['phone'], $user['mobile'], $user['avatar'], $user['id']);
		 		}
		 		
		 		return $allUsers;
		 		
		 	} catch(Exception $e){
		 		throw new Exception("Something went wrong, please try again later!");
		 	}
		 }
		

}
	

?>
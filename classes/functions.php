<?php
	require_once '../classes/connect.php';

	class User {

		public static function create_user(string $first_name, string $last_name, string $password, string $email ){
			header('Content-type: application/json');
			$pdo = database::getInstance()->getConnection();
			// validation
			
			if (!isset($first_name) || !isset($last_name) || !isset($email)){
				// ToDo: return missing components
				echo 'Es fehlen Daten!';
				return;
			}
			if (isset($password)){
				// ToDo: security related checks 
				$password = password_hash(md5($password),PASSWORD_DEFAULT);
			}
			if (isset($email)){
				if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
					// ToDo: implement better userfeedback i.e. what is missing exactly
					echo "E-Mail nicht korrekt!";
					return;
				};
			}


			// check if already exists
			$sQuery = 'SELECT * FROM users where email = :email';
			$statement = $pdo->prepare($sQuery);
			$statement->bindValue(':email', $email, PDO::PARAM_STR);
			$statement->execute();
			$iRowCount = $statement->rowCount(); 
			if($iRowCount == 1){
				echo 'existiert bereits';
				return User::select_all_users();
			} elseif($iRowCount > 1) {
				// bitte an den Support wenden, existiert bereits ein oder mehrere Accounts ( falls später unique auf email entfernt wird )
				echo 'existiert bereits';
				return User::select_all_users();
			}
			// insert
			$sQuery = ' INSERT INTO public.users( first_name, last_name, email, password, created_on , lastedit)
				VALUES (:first_name,:last_name,:email,:password,CURRENT_TIMESTAMP , CURRENT_TIMESTAMP);';
			$statement = $pdo->prepare($sQuery);
			$statement->bindValue(':first_name', $first_name, PDO::PARAM_STR);
			$statement->bindValue(':last_name', $last_name, PDO::PARAM_STR);
			$statement->bindValue(':email', $email, PDO::PARAM_STR);
			$statement->bindValue(':password', $password, PDO::PARAM_STR);
			$statement->execute();
			// return all existing users? for now yes, generally only want to get the return and add it to the existing dataset 
			return User::select_all_users();
		}

		public static function select_all_users(PDO $pdo = NULL){
			header('Content-type: application/json');
			$pdo = database::getInstance()->getConnection();
			$sQuery = 'SELECT * FROM users';
			$statement = $pdo->prepare($sQuery);
			$statement->execute();
			
			$string =  "<table>\n";
			while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
				$string .= "\t<tr id='user_".$row['id']."'>\n";
				foreach ($row as $key => $col_value) {
					// $string .= $col_value;
					// $data[$key] = $col_value;
					$string .= "\t\t<td class=\"$key\" id=\"" . $key . "_" .$row['id'] . "\">$col_value</td>\n";
				}
				$string .= "\t\t<td><button id=\"".$row['id']."\" type=\"button\" class=\"update_user\">bearbeiten</button>\n</td>\n";
				$string .= "\t\t<td><button id=\"".$row['id']."\" type=\"button\" class=\"delete_user\">löschen</button>\n</td>\n";
				$string .= "\t</tr>\n";
			}
			$string .= "</table>\n";
			echo $string;
		}

		public static function delete_user(int $user_id){
			header('Content-type: application/json');
			$pdo = database::getInstance()->getConnection();
			// check for id
			if(!isset($user_id)){
				echo 'keine ID übergeben';
				return;
			}
			// check if exists
			$sQuery = 'SELECT * FROM users where id = :user_id;';
			$statement = $pdo->prepare($sQuery);
			$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$statement->execute();
			$iRowCount = $statement->rowCount(); 
			if($iRowCount == 0){
				// improve Errorhandling / Message
				echo 'kein passender User gefunden';
				return;
			}
			// delete
			$sQuery = ' DELETE FROM users WHERE id = :user_id;';
			$statement = $pdo->prepare($sQuery);
			$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$statement->execute();
			// return deleted id and remove from resultset -> already done in frontend on success
			echo 'gelöscht';
			// return;
		}

		public static function update_user(int $user_id,string $first_name, string $last_name, string $password, string $email){
			header('Content-type: application/json');
			$pdo = database::getInstance()->getConnection();
			// check for id
			if(!isset($user_id)){
				echo 'keine ID übergeben';
				return;
			}
			// check if exists
			$sQuery = 'SELECT * FROM users where id = :user_id;';
			$statement = $pdo->prepare($sQuery);
			$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$statement->execute();
			$iRowCount = $statement->rowCount(); 
			if($iRowCount == 0){
				// improve Errorhandling / Message
				echo 'kein passender User gefunden';
				return;
			}
			$aOldUserdata = $statement->fetch(PDO::FETCH_ASSOC);
			// update if changed
			$sQuery = ' UPDATE users SET ';

			if (!empty($first_name) && $first_name != $aOldUserdata['first_name']){
				$sQuery .= ' first_name = :first_name , ';
			}
			if (!empty($last_name) && $last_name != $aOldUserdata['last_name']){
				$sQuery .= ' last_name = :last_name ,  ';
			}
			if (!empty($password)){
				$password = password_hash(md5($password),PASSWORD_DEFAULT);
				$sQuery .= ' password = :password ,  ';
			}
			if (!empty($email) && $email != $aOldUserdata['email']){
				$sQuery .= ' email = :email , ';
			}
			$sQuery .= ' lastedit = CURRENT_TIMESTAMP WHERE id = :user_id';
			$statement = $pdo->prepare($sQuery);
			if (!empty($first_name) && $first_name != $aOldUserdata['first_name']){
				$statement->bindValue(':first_name', $first_name, PDO::PARAM_STR);
				echo 'aktualisiert11';
			}
			if (!empty($last_name) && $last_name != $aOldUserdata['last_name']){
				$statement->bindValue(':last_name', $last_name, PDO::PARAM_STR);
			}
			if (!empty($password)){
				$statement->bindValue(':password', $password, PDO::PARAM_STR);
			}
			if (!empty($email) && $email != $aOldUserdata['email']){
				$statement->bindValue(':email', $email, PDO::PARAM_STR);
			}
			$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$statement->execute();
			// return updated id and maybe highlight?
			return User::select_all_users();
		}
	}

	if (isset($_POST['operation']) && $_POST['operation'] == 'read'){
		User::select_all_users();
	} else if (isset($_POST['operation']) && $_POST['operation'] == 'create'){
		User::create_user($_POST['first_name'],$_POST['last_name'],$_POST['password'],$_POST['email']);
	} else if (isset($_POST['operation']) && $_POST['operation'] == 'delete'){
		User::delete_user($_POST['user_id']);
	} else if (isset($_POST['operation']) && $_POST['operation'] == 'update'){
		// should i check here for changed input? nah
		User::update_user($_POST['user_id'], $_POST['first_name'],$_POST['last_name'],$_POST['password'],$_POST['email']);
	}

?>
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
			$query = 'SELECT * FROM users where email = :email';
			$statement = $pdo->prepare($query);
			$statement->bindValue(':email', $email);
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
			$query = ' INSERT INTO public.users( first_name, last_name, email, password, created_on )
				VALUES (:first_name,:last_name,:email,:password,CURRENT_TIMESTAMP);';
			$statement = $pdo->prepare($query);
			$statement->bindValue(':first_name', $first_name);
			$statement->bindValue(':last_name', $last_name);
			$statement->bindValue(':email', $email);
			$statement->bindValue(':password', $password);
			$statement->execute();
			// return all existing users? for now yes, generally only want to get the return and add it to the existing dataset 
			return User::select_all_users();
		}

		public static function select_all_users(PDO $pdo = NULL){
			header('Content-type: application/json');
			$pdo = database::getInstance()->getConnection();
			$query = 'SELECT * FROM users';
			$statement = $pdo->prepare($query);
			$statement->execute();
			
			$string =  "<table>\n";
			while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
				$string .= "\t<tr>\n";
				foreach ($row as $key => $col_value) {
					// $string .= $col_value;
					// $data[$key] = $col_value;
			 		$string .= "\t\t<td>$col_value</td>\n";
				}
				$string .= "\t</tr>\n";
			}
			// var_dump(json_encode($data));
			// echo json_encode($data);
			$string .= "</table>\n";
			echo $string;
		}

		function delete_user(){

		}

		function update_user(){

		}
	}

	if (isset($_POST['operation']) && $_POST['operation'] == 'read'){
		User::select_all_users();
	} else if (isset($_POST['operation']) && $_POST['operation'] == 'create'){
		User::create_user($_POST['first_name'],$_POST['last_name'],$_POST['password'],$_POST['email']);
	}

?>
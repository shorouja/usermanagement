<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once __DIR__ . "/../classes/user.php";

final class userTest extends TestCase
{
	// create
	// cases: missing contents, email invalid, existing user with email, 
	public function testUserCreate() :void{
		$first_name = 'Tester';
		$last_name = 'Schwabe';
		$email = 'schwabe.daniel@yahoo.de';
		$password = 'Tester123!';

		$results = User::create_user($first_name,$last_name,$password,$email);

		$this->assertStringStartsWith('Erfolgreich angelegt.', $results);	

	}
	public function testUserCreateExisting() :void{
		$first_name = 'Tester';
		$last_name = 'Schwabe';
		$email = 'schwabe.daniel@yahoo.de';
		$password = 'Tester123!';

		$results = User::create_user($first_name,$last_name,$password,$email);
		$this->assertStringStartsWith('Zu dieser E-Mail existiert bereits ein Account.', $results);	

	}
	public function testUserCreateEmail() :void{
		$first_name = 'Tester';
		$last_name = 'Schwabe';
		$email = 'schwabe.daniel';
		$password = 'Tester123!';

		$results = User::create_user($first_name,$last_name,$password,$email);
		$this->assertStringStartsWith('E-Mail entspricht nicht dem Standard.', $results);	

	}
	public function testUserCreateMissing() :void{
		$first_name = 'Tester';
		$last_name = '';
		$email = 'schwabe.daniel@yahoo.de';
		$password = 'Tester123!';

		$results = User::create_user($first_name,$last_name,$password,$email);
		$this->assertStringStartsWith('Es fehlen Daten.', $results);	

	}
	// read
	public function testUserReadAll() :void{
		$results = User::select_all_users();
		$this->assertStringStartsWith('Erfolgreich aktualisiert.', $results);	
	}

	// update
	// cases: missing contents, email invalid, existing user with email, missing email, 
	// for testpurposes i assume user with id 1 otherwise all would result in "Zu Ihrer Anfrage konnte kein Nutzer gefunden werden."
	
	public function testUserUpdate() :void{
		$user_id = 1;
		$first_name = 'Tester2';
		$last_name = 'Schwabe2';
		$email = 'schwabe.daniel2@yahoo.de';
		$password = 'Tester1234!';

		$results = User::update_user($user_id,$first_name,$last_name,$email,$password);

		$this->assertStringStartsWith('Erfolgreich bearbeitet.', $results);	

	}

	public function testUserUpdateNotFound() :void{
		$user_id = 100000;
		$first_name = 'Tester2';
		$last_name = 'Schwabe2';
		$email = 'schwabe.daniel2@yahoo.de';
		$password = 'Tester1234!';

		$results = User::update_user($user_id,$first_name,$last_name,$email,$password);

		$this->assertStringStartsWith('Zu Ihrer Anfrage konnte kein Nutzer gefunden werden.', $results);	

	}
	public function testUserUpdateExisting() :void{
		$user_id = 1;
		$first_name = 'Tester2';
		$last_name = 'Schwabe2';
		$email = 'schwabe.daniel@yahoo.de';
		$password = 'Tester1234!';

		$results = User::update_user($user_id,$first_name,$last_name,$email,$password);

		$this->assertStringStartsWith('Zu dieser E-Mail existiert bereits ein Account.', $results);	

	}
	public function testUserUpdateEmail() :void{
		$user_id = 1;
		$first_name = 'Tester';
		$last_name = 'Schwabe';
		$email = 'schwabe.daniel';
		$password = 'Tester1234!';

		$results = User::update_user($user_id,$first_name,$last_name,$email,$password);

		$this->assertStringStartsWith('E-Mail entspricht nicht dem Standard.', $results);	
	}
	public function testUserUpdateMissingEmail() :void{
		$user_id = 1;
		$first_name = 'Tester2';
		$last_name = 'Schwabe2';
		$email = '';
		$password = 'Tester1234!';
		
		$results = User::update_user($user_id,$first_name,$last_name,$email,$password);
		
		$this->assertStringStartsWith('Es wurde keine E-Mail übergeben.', $results);	
	}
	public function testUserUpdateSuccess() :void{
		$user_id = 1;
		$first_name = 'Tester3';
		// this couldn't be done in FE because it's "required"
		$last_name = '';
		$email = 'schwabe.daniel3@yahoo.de';
		$password = '';

		$results = User::update_user($user_id,$first_name,$last_name,$email,$password);
		
		$this->assertStringStartsWith('Erfolgreich bearbeitet.', $results);	
	}

	public function testUserUpdateMissingId() :void{
		// this couldn't be done in FE because it's "required"
		// this should result in typeerror int
		$user_id = NULL;
		$values['first_name'] = 'Tester4';
		$values['last_name'] = '';
		$values['email'] = 'schwabe.daniel4@yahoo.de';
		$values['password'] = '';
		$this->expectException(Error::class);
		$results = User::update_user($user_id,$first_name,$last_name,$email,$password);
		$this->assertStringStartsWith('Es wurde keine Nutzer-ID übergeben.', $results);	

	}

	// delete
	public function testUserDelete() :void{
		
		$results = User::delete_user(1);
		$this->assertStringStartsWith('Erfolgreich gelöscht.', $results);	

	}
	public function testUserDeleteNotFound() :void{
		
		$results = User::delete_user(1);
		$this->assertStringStartsWith('Zu Ihrer Anfrage konnte kein Nutzer gefunden werden.', $results);	
		
	}
	public function testUserDeleteNoId() :void{
		$user_id = NULL; 
		$this->expectException(Error::class);
		$results = User::delete_user($user_id);
		$this->assertStringStartsWith('Es wurde keine Nutzer-ID übergeben.', $results);	
		
	}
}

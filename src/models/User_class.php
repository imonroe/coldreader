<?php

/**
 * Coldreader 
 *
 * PHP version 5
 *
 * LICENSE: There's plenty of third-party libs in use, 
 * and nothing here should be interpreted to change or 
 * contradict anything that is stipulated in the licenses 
 * for those components.  As for my code, it's Creative 
 * Commons Attribution-NonCommercial-ShareAlike 3.0 
 * United States. (http://creativecommons.org/licenses/by-nc-sa/3.0/us/).  
 * For more information, contact Ian Monroe: ian@ianmonroe.com
 *
 * @author     Ian Monroe <ian@ianmonroe.com>
 * @copyright  2016
 * @version    0.1 ALPHA UNSTABLE
 * @link       http://www.ianmonroe.com
 * @since      File included in initial release
 *
 */
class User {
	public $config;
	public $id = '';
	public $email_address = '';
	public $date_registered = '';
	public $nonce = '';
	public $first_name = '';
	public $last_name = '';
	public $role = '';
	public $primary_controller = '';
	function __construct($from_email = '') {
		// constructor method
		$this->primary_controller = 'controllers/controller_user.php';
		if ($from_email != '') {
			$this->load ( $from_email );
		}
	}
	function __destruct() {
		// destructor method
	}
	function create() {
		$db = Database::get_instance ();
		$this->date_registered = date ( "Y-m-d H:i:s" );
		$this->create_nonce ();
		$sql = "INSERT INTO users(
				email_address,
				date_registered,
				nonce,
				first_name,
				last_name,
				role)
				VALUES (
				:email_address,
				:date_registered,
				:nonce,
				:first_name,
				:last_name,
				:role)";
		$stmt = $db->prepare ( $sql );
		$stmt->bindParam ( ':email_address', $this->email_address, PDO::PARAM_STR );
		$stmt->bindParam ( ':date_registered', $this->date_registered, PDO::PARAM_STR );
		$stmt->bindParam ( ':nonce', $this->nonce, PDO::PARAM_STR );
		$stmt->bindParam ( ':first_name', $this->first_name, PDO::PARAM_STR );
		$stmt->bindParam ( ':last_name', $this->last_name, PDO::PARAM_STR );
		$stmt->bindParam ( ':role', $this->role, PDO::PARAM_STR );
		if ($stmt->execute ()) {
			return true;
		} else {
			return false;
		}
	} // end create
	function load($from_email = '') {
		if ($from_email == '') {
			$from_email = $this->email_address;
		}
		if (! empty ( $from_email )) {
			$db = Database::get_instance ();
			$sql = "SELECT * FROM users WHERE email_address = :email_address";
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( ':email_address', $from_email, PDO::PARAM_STR );
			if ($stmt->execute ()) {
				$row = $stmt->fetchObject ();
				if (! empty ( $row )) {
					$this->email_address = $row->email_address;
					$this->date_registered = $row->date_registered;
					$this->id = $row->id;
					$this->nonce = $row->nonce;
					$this->first_name = $row->first_name;
					$this->last_name = $row->last_name;
					$this->role = $row->role;
				}
			} else {
				echo 'database problem with User.';
			}
		}
	}
	function update() {
		$db = Database::get_instance ();
		$sql = "UPDATE users SET 
				email_address = :email_address,
				date_registered = :date_registered,
				nonce = :nonce,
				first_name = :first_name,
				last_name = :last_name,
				role = :role
				WHERE email_address = :email_address1";
		$stmt = $db->prepare ( $sql );
		$stmt->bindParam ( ':email_address', $this->email_address, PDO::PARAM_STR );
		$stmt->bindParam ( ':date_registered', $this->date_registered, PDO::PARAM_STR );
		$stmt->bindParam ( ':nonce', $this->nonce, PDO::PARAM_STR );
		$stmt->bindParam ( ':first_name', $this->first_name, PDO::PARAM_STR );
		$stmt->bindParam ( ':last_name', $this->last_name, PDO::PARAM_STR );
		$stmt->bindParam ( ':role', $this->role, PDO::PARAM_STR );
		$stmt->bindParam ( ':email_address1', $this->email_address, PDO::PARAM_STR );
		if ($stmt->execute ()) {
			return true;
		} else {
			return false;
		}
	}
	function delete() {
		$db = Database::get_instance ();
		$sql = "DELETE FROM users WHERE email_address = :email_address";
		$stmt = $db->prepare ( $sql );
		$stmt->bindParam ( ':email_address', $this->email_address, PDO::PARAM_STR );
		if ($stmt->execute ()) {
			return true;
		} else {
			return false;
		}
	}
	function check_username($from_email) {
		$db = Database::get_instance ();
		$sql = "SELECT * FROM users WHERE email_address = :email_address";
		$stmt = $db->prepare ( $sql );
		$stmt->bindParam ( ':email_address', $from_email, PDO::PARAM_STR );
		$stmt->execute ();
		$row = $stmt->fetch ( PDO::FETCH_OBJ );
		if ($row->email_address) {
			return true; // returns true if it finds a user with a matching email
		} else {
			return false; // returns false if it finds no matching user
		}
	}
	function create_nonce() {
		$seed_timestamp = date ( "Y-m-d H:i:s" );
		$seed_salt = openssl_random_pseudo_bytes ( 128 );
		$seed = $seed_salt . $seed_timestamp;
		return sha1 ( $seed );
	}
	function set_nonce() {
		$new_nonce = $this->create_nonce ();
		// echo $new_nonce;
		$this->nonce = $new_nonce;
		$this->update ();
	}
	function verify_nonce($user_nonce) {
		if ($user_nonce == $this->nonce) {
			return true;
		} else {
			return false;
		}
	}
	function is_logged_in() {
		
		/*
		 * This nonce system is hard. We're getting an email from Google after sign-in.
		 * Let's just compare it to what's in the database. We'll also check for a session access token.
		 *
		 * well, fuck, that makes us vulnerable to cross-site POSTS and other state-changing operations.
		 * we should DEFINITELY use the NONCE system
		 */
		if (! empty ( $this->email_address )) {
			if ($this->check_username ( $this->email_address ) && isset ( $_SESSION ['access_token'] )) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
		
		/*
		 * if (isset( $_COOKIE['nonce'])) { $cookie_nonce = $_COOKIE['nonce']; }
		 * if (isset( $_COOKIE['auth'])) {$cookie_email = $_COOKIE['auth'];}
		 * if ( !isset($cookie_nonce) || !isset($cookie_email) ){
		 * return false;
		 * }
		 * $this->load($cookie_email);
		 * if ($cookie_nonce == $this->nonce){
		 * return true;
		 * }else{
		 * return false;
		 * }
		 */
	}
	function login_form() {
		if ($this->is_logged_in ()) {
			$this->load ( $_COOKIE ['auth'] );
			?>
<p><? if ($this->is_admin()){ ?>
					<a href="index.php?p=admin">Administration</a> | 	
				<?
			
}
			?><a
		href='index.php?p=user_edit.form&edit_user=<?=$this->email_address; ?>'>edit
		your info</a> |
	<button id="logout" class="btn btn-danger">Sign out</button>
</p>
<?php
		} else {
			?>
<button id="login" class="btn btn-primary">Sign in with Persona</button>
<?php
		}
	}
	function get_role() {
		// $user_role = new Role;
		// $user_role->load($this->role);
		// return $user_role->label;
		return;
	}
	function is_admin() {
		if ($this->get_role () == "Administrator") {
			return true;
		} else {
			return false;
		}
	}
	function get_sort_name() {
		return $this->last_name . ', ' . $this->first_name;
	}
} // end class user

?>
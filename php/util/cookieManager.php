<?php
	
	$cookieManager = new CookieManager();

	class CookieManager {

		private $user_cookie_name 	= "markdown_login_cookie";
		private $secret_word		= "python horse duck spinach cats trees rainbow";

		function setUserCookie($username){
			setcookie($this->user_cookie_name, $username.','.md5($username.$this->secret_word), time() + (86400 * 30), "/");
		}
		
		function checkUserCookie(){
			if(!isset($_COOKIE[$this->user_cookie_name]))
				return false;

		    list($c_username, $cookie_hash) = split(',', $_COOKIE[$this->user_cookie_name]);

		    if (md5($c_username.$this->secret_word) == $cookie_hash)
		        return true;

			return false;
		}

		function deleteUserCookie(){
			if(isset($_COOKIE[$this->user_cookie_name])){
				setcookie($this->user_cookie_name, "", time() - 36000, "/");
			}
		}

	}
?>
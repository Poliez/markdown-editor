<?php
	require_once "./util/cookieManager.php";
	require_once "./util/navigationUtil.php";

    $cookieManager->deleteUserCookie();

	session_unset(); 
	session_destroy(); 
	
    goHome();
    exit;
?>

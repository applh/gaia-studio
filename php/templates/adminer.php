<?php
function adminer_object() {
	include_once __DIR__ . "/lib/plugin.php";
	include_once __DIR__ ."/lib/login-password-less.php";
    // HACK: use current date as default password
    $password = getenv("ADMINER_PASSWORD") ?: date("Ymd");
    error_log("ADMINER_PASSWORD: $password");
	return new AdminerPlugin(array(
		// TODO: inline the result of password_hash() so that the password is not visible in source codes
		new AdminerLoginPasswordLess(password_hash($password, PASSWORD_DEFAULT)),
	));
}

// /app/php/my-data/db-users.sqlite

include __DIR__ . "/lib/adminer.php";
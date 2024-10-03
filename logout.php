<?php

// logout from session
	session_destroy();
	header("Location: login");
	exit;

?>
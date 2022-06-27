<?php
// +------------------------------------------------------------------------+
// | @author Hassan Tijani.A (SureCoder)
// | @author_email: gatukurh1@gmail.com
// +------------------------------------------------------------------------+
// | SureChatter - Social Chatting System
// | Copyright (c) 2022 SureChattter. All rights reserved.
// +------------------------------------------------------------------------+


if ( file_exists( dirname( __FILE__ ) . '/config-local.php' ) ) {

	require_once dirname( __FILE__ ) . '/config-local.php';
} else {
	// MySQL Hostname
	$sql_db_host = "localhost";
	// MySQL Database User
	$sql_db_user = "root";

	// MySQL Database Password
	$sql_db_pass = "";

	// MySQL Database Name
	$sql_db_name = "surechatter";

	// Site URL
	$site_url = "http://localhost/surechatter";
}

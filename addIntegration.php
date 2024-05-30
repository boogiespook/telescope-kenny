<?php

$pg_host = getenv('POSTGRESQL_HOST');
$pg_db = getenv('POSTGRESQL_DATABASE');
$pg_user = getenv('POSTGRESQL_USER');
$pg_passwd = getenv('POSTGRESQL_PASSWORD');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");

$qq = "INSERT into integrations (integration_name, capability_id, url, \"user\", password, token, success_criteria, hash) VALUES ('" . $_REQUEST['integration-name'] . "', '" . $_REQUEST['capability-id'] . "', '" . $_REQUEST['endpoint-url'] . "', '" . $_REQUEST['username'] . "', '" . $_REQUEST['password'] . "', '" . $_REQUEST['token'] . "', '" . $_REQUEST['success-criteria'] . "', '" . $_REQUEST['hash'] . "')";

$result = pg_query($db_connection, $qq);


header("Location: index.php");



?>

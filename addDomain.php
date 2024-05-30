<?php
$pg_host = getenv('POSTGRESQL_HOST');
$pg_db = getenv('POSTGRESQL_DATABASE');
$pg_user = getenv('POSTGRESQL_USER');
$pg_passwd = getenv('POSTGRESQL_PASSWORD');
$domain = htmlspecialchars($_REQUEST['domain'], ENT_QUOTES, 'UTF-8');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");

$qq = "INSERT into domain (description) VALUES ('" . $domain . "')";

$result = pg_query($db_connection, $qq);

header("Location: index.php");



?>

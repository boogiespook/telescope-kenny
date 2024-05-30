<?php

$pg_host = getenv('POSTGRESQL_HOST');
$pg_db = getenv('POSTGRESQL_DATABASE');
$pg_user = getenv('POSTGRESQL_USER');
$pg_passwd = getenv('POSTGRESQL_PASSWORD');
$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");

$table = htmlspecialchars($_REQUEST['table'], ENT_QUOTES, 'UTF-8');
$idColumn = htmlspecialchars($_REQUEST['idColumn'], ENT_QUOTES, 'UTF-8');
$id = htmlspecialchars($_REQUEST['id'], ENT_QUOTES, 'UTF-8');

## If it's a domain deletion, also delete all child capabilities
if($table == "domain") {

## Delete all child capabilities for that specific domain
$deleteCapabilities = "delete from capability where domain_id = '" . $id . "'";
$capabilityResult = pg_query($db_connection, $deleteCapabilities);
}


$qq = "delete from $table WHERE $idColumn = '" . $id . "'";
$result = pg_query($db_connection, $qq);

header("Location: index.php");



?>

<?php

$pg_host = getenv('POSTGRESQL_HOST');
$pg_db = getenv('POSTGRESQL_DATABASE');
$pg_user = getenv('POSTGRESQL_USER');
$pg_passwd = getenv('POSTGRESQL_PASSWORD');
$profile = htmlspecialchars($_REQUEST['profileName'], ENT_QUOTES, 'UTF-8');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");

#print_r($_GET);

## Add the profile first
$qq = "INSERT into profiles (name) VALUES ('" . $profile . "')";
$result = pg_query($db_connection, $qq);

$qq2 = "SELECT id from profiles where name = '" . $profile . "'";
$result2 = pg_query($db_connection, $qq2);
$row = pg_fetch_row($result2); 
$profileId = $row[0];

$qq = "";
foreach($_GET as $name => $options) {
if ($options == '1') {
print $name . " " . $options . "<br>";
$domainNumber = substr($name,6);
print $domainNumber . "<br>";
$qq .= "UPDATE profiles SET domains = array_append(domains,'" . $domainNumber . "') WHERE id = '" . $profileId . "';";
}

}

## Add/append the domains for the specific profile
$result = pg_query($db_connection, $qq);

header("Location: index.php");



?>

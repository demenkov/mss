<?php

$host = $argv[1];
$db = $argv[2];
$user = $argv[3];
$pass = $argv[4];

$dbh = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$query = <<<SQL
INSERT INTO job (type, email, ts)
SELECT 0, email, UNIX_TIMESTAMP() 
FROM user 
WHERE checked = 0 AND valid = 0 AND confirmed = 0 AND validts > unix_timestamp()
ORDER BY validts ASC
ON DUPLICATE KEY UPDATE id = id
SQL;
$sth = $dbh->query($query)->execute();
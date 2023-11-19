<?php

$host = $argv[1];
$db = $argv[2];
$user = $argv[3];
$pass = $argv[4];

$dbh = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$query = <<<SQL
INSERT INTO job (type, email, ts)
SELECT 1, email, UNIX_TIMESTAMP() 
FROM user 
WHERE (
    validts BETWEEN UNIX_TIMESTAMP() AND UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY) OR
    validts BETWEEN UNIX_TIMESTAMP(CURDATE() + INTERVAL 3 DAY) AND UNIX_TIMESTAMP(CURDATE() + INTERVAL 4 DAY)
) AND (confirmed = 1 OR valid = 1)
ORDER BY validts ASC
ON DUPLICATE KEY UPDATE id = id
SQL;
$sth = $dbh->query($query)->execute();
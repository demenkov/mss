<?php

$host = $argv[1];
$db = $argv[2];
$user = $argv[3];
$pass = $argv[4];

$dbh = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

while (1) {
    $job = get_next_job($dbh);
    if (is_null($job)) {
        continue;
    }
    echo sprintf('processing job %s', $job['id']), PHP_EOL;
    if ($job['type'] === 1) {
        send_email('info@service', $job['email'], sprintf('%s, your subscription is expiring soon', $job['username']));
    }
    if ($job['type'] === 0) {
        $result = check_email($job['email']);
        $stmt = $dbh->prepare('UPDATE user SET checked = :checked, valid = :valid WHERE email = :email');
        $stmt->execute(['checked' => 1, 'valid' => $result, 'email' => $job['email']]);
    }
    $dbh->prepare('DELETE FROM job WHERE id = :id')->execute(['id' => $job['id']]);
}

function get_next_job($dbh) {
    try {
        $dbh->beginTransaction();
        $id = $dbh->query(<<<SQL
SELECT id FROM job j
         WHERE run is null 
         ORDER BY type DESC, ts ASC 
         LIMIT 1 FOR UPDATE SKIP LOCKED
SQL)->fetchColumn();
        if (false === $id) {
            $dbh->commit();
            return null;
        }
        $dbh->query("UPDATE job SET run = UNIX_TIMESTAMP() WHERE id = $id")->execute();
        $dbh->commit();
    } catch (\Exception $e) {
        echo $e->getMessage(), PHP_EOL;
        $dbh->rollBack();
        return null;
    }
    $query = <<<SQL
SELECT * FROM job j inner join user u on j.email = u.email WHERE id = $id
SQL;
    return $dbh->query($query)->fetch(PDO::FETCH_ASSOC);
}
function send_email( $from, $to, $text ) {
    echo $text, PHP_EOL;
    sleep(rand(1, 10));
    return true;
}

function check_email($email) {
    echo sprintf('spent 1 for check %s', $email), PHP_EOL;
    sleep(rand(1, 60));
    return true;
}
<?php
$secrets = parse_ini_file("secrets.ini");

$dsn = "mysql:host=" . $secrets["host"] . ";dbname=" . $secrets["ucid"];

try {
    $db = new PDO($dsn, $secrets["ucid"], $secrets["password"]);
} catch (PDOException $err) {
    echo "Connection failed: " . $err->getMessage();
}

$create_accounts = "CREATE TABLE IF NOT EXISTS `is218_accounts`
    (
       `id`     INT(11) UNSIGNED NOT NULL auto_increment,
       `email`  VARCHAR(60) DEFAULT NULL,
       `fname`  VARCHAR(30) DEFAULT NULL,
       `lname`  VARCHAR(30) DEFAULT NULL,
       `dob`    DATE DEFAULT NULL,
       `passwd` VARCHAR(30) DEFAULT NULL,
       PRIMARY KEY (`id`)
    )
    engine=innodb
    DEFAULT charset=utf8;";

$create_questions = "CREATE TABLE IF NOT EXISTS `is218_questions`
    (
       `id`            INT(11) UNSIGNED NOT NULL auto_increment,
       `account_email` VARCHAR(60) DEFAULT NULL,
       `account_id`    INT(11) DEFAULT NULL,
       `created_at`    DATETIME DEFAULT NOW(),
       `title`         TEXT,
       `body`          TEXT,
       `skills`        TEXT,
       `score`         INT(11) NOT NULL DEFAULT '0',
       PRIMARY KEY (`id`)
    )
    engine =innodb
    DEFAULT charset=utf8;";

try {
    $db->exec($create_accounts);
    $db->exec($create_questions);
} catch (PDOException $err) {
    echo "Table creation failed: " . $err->getMessage();
}

<?php

$host = 'db';
$db   = 'dbname';
$user = 'mariadb_user';
$pass = 'mariadb_user_password';
$charset = 'utf8';

define('BOOKS_COUNT', 3000);
define('AUTHORS_COUNT', 100);

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$authors = [];
$stmt = $pdo->prepare('INSERT INTO `authors` (`name`) VALUES (:name)');
for($i=1; $i<=AUTHORS_COUNT; $i++){
    $name = generateRandomString();
    $stmt->execute(array('name' => $name));
    $id = $pdo->lastInsertId();
    $authors[] = [
        'id' => $id,
        'name' => $name,
    ];
}

function getRandomAuthors($authors){
    $count = rand(5, 10);
    $result = [];
    for($i=1; $i<=$count; $i++){
        $ind = rand(0, AUTHORS_COUNT - 1);
        $result[] = $authors[$ind];
    }
    return $result;
}

$stmt = $pdo->prepare('INSERT INTO `books` (`name`) VALUES (:name)');
$stmt_rel = $pdo->prepare('INSERT INTO `relations` (`book_id`, `author_id`) VALUES (:book_id, :author_id)');
for($i=1; $i<=BOOKS_COUNT; $i++){
    $name = generateRandomString();
    $stmt->execute(array('name' => $name));
    $book_id = $pdo->lastInsertId();
    $book_authors = getRandomAuthors($authors);
    foreach ($book_authors as $author){
        $author_id = $author['id'];
        $stmt_rel->execute(array('book_id' => $book_id, 'author_id' => $author_id));
    }
}
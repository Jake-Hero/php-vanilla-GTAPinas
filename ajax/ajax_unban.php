<?php
require __DIR__ . '/../class/db.php';
require __DIR__ . '/../class/function.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

if(isset($_POST['username'])) {
    $query = "DELETE FROM bans WHERE username = :username";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':username' => $_POST['username']));
        
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle the exception
        echo "Error: " . $e->getMessage();
    }
}

die;

?>
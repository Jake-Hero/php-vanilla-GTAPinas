<?php
require __DIR__ . '/../class/db.php';
require __DIR__ . '/../class/function.php';

$dbClass = new DB(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$pdo = $dbClass->getConnection();
$obj = new ucpProject($pdo);

$query = "SELECT * FROM bans ORDER BY id DESC";
try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $dataset = array(
        "echo" => 1,
        "totalrecords" => count($array),
        "totaldisplayrecords" => count($array),
        "data" => $array
    );

    echo json_encode($dataset);
} catch (PDOException $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
}
?>
<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    date_default_timezone_set('Europe/Moscow');
    
    $client = htmlspecialchars(trim($_POST['client_name']));
    $dish = htmlspecialchars(trim($_POST['dish']));
    $chef = htmlspecialchars(trim($_POST['chef']));
    $qty = (int)$_POST['quantity'];
    $date = date('d.m.Y H:i');

    try {
        $sql = "INSERT INTO orders (order_date, client_name, dish, chef, quantity) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date, $client, $dish, $chef, $qty]);
        
        header("Location: index.php?status=success");
        exit();
    } catch (PDOException $e) {
        die("Ошибка сохранения в базу: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
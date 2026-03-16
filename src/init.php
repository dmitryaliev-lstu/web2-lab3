<?php
require_once 'db.php';

echo "<h2>Инициализация БД (ЛР3)</h2>";

try {
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_date VARCHAR(50),
        client_name VARCHAR(255),
        dish VARCHAR(255),
        chef VARCHAR(255),
        quantity INT
    ) DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "✅ Таблица 'orders' готова.<br>";
} catch (PDOException $e) {
    die("Ошибка создания таблицы: " . $e->getMessage());
}

if (isset($_POST['do_import'])) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "⚠️ Данные уже есть в базе ($count записей). Импорт отменен.<br>";
    } else {
        if (file_exists('orders.csv')) {
            $file = fopen('orders.csv', 'r');
            fgetcsv($file);
            
            $sql = "INSERT INTO orders (order_date, client_name, dish, chef, quantity) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            $i = 0;
            while (($row = fgetcsv($file)) !== false) {
                $stmt->execute($row);
                $i++;
            }
            fclose($file);
            echo "🚀 Успешно перенесено из CSV в БД: $i записей.<br>";
        } else {
            echo "❌ Файл orders.csv не найден.<br>";
        }
    }
}
?>

<form method="POST" style="margin-top: 20px;">
    <button type="submit" name="do_import">Запустить импорт из CSV</button>
</form>

<br>
<a href="index.php">На главную (index.php)</a>
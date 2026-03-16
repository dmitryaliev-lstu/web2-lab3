<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ресторан - ЛР3 (База данных)</title>
    <style>
        body { font-family: Arial; margin: 20px; background-color: #f4f4f9; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .success-msg { color: green; background: #d4edda; padding: 10px; margin-bottom: 10px; border: 1px solid #c3e6cb; }
        .forms { display: flex; gap: 20px; margin-bottom: 20px; }
        .form-box { flex: 1; padding: 15px; border: 1px solid #eee; background: #fafafa; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Управление заказами (MySQL + PDO)</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="success-msg">✅ Заказ успешно записан в базу данных!</div>
    <?php endif; ?>

    <div class="forms">
        <div class="form-box">
            <h3>Новый заказ</h3>
            <form action="save.php" method="POST">
                <input type="text" name="client_name" maxlength="255" placeholder="Клиент" required style="width:90%; margin-bottom:5px;"><br>
                <input type="text" name="dish" maxlength="255" placeholder="Блюдо" required style="width:90%; margin-bottom:5px;"><br>
                <input type="text" name="chef" maxlength="255" placeholder="Повар" required style="width:90%; margin-bottom:5px;"><br>
                <input type="number" name="quantity" value="1" min="1" max="2147483647" required style="width:90%; margin-bottom:5px;"><br>
                <button type="submit" style="background: green; color: white; border: none; padding: 10px; width: 95%;">Отправить в БД</button>
            </form>
        </div>

        <div class="form-box">
            <h3>Поиск</h3>
            <form method="GET">
                <input type="text" name="search" placeholder="Поиск по всем полям..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                       style="width:90%; margin-bottom:5px;"><br>
                <button type="submit" style="padding: 10px; width: 45%;">Найти</button>
                <a href="index.php" style="font-size: 12px; margin-left: 5px;">Сброс</a>
            </form>
        </div>
    </div>

    <h3>Список заказов из MySQL</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Клиент</th>
            <th>Блюдо</th>
            <th>Повар</th>
            <th>Кол-во</th>
        </tr>
        <?php
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        if ($search !== '') {
            $sql = "SELECT * FROM orders WHERE client_name LIKE ? OR dish LIKE ? OR chef LIKE ? ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["%$search%", "%$search%", "%$search%"]);
        } else {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
        }

        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['order_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dish']) . "</td>";
            echo "<td>" . htmlspecialchars($row['chef']) . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <a href="init.php" style="color: #666; font-size: 12px;">Вернуться к странице инициализации</a>
</div>

</body>
</html>
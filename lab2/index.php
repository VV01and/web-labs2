<?php
require_once __DIR__ . '/vendor/autoload.php';

// Підключення до MongoDB
$client = new MongoDB\Client("mongodb://127.127.126.17:27017");
$db = $client->dbforlab;
$usersCollection = $db->users;
$sessionsCollection = $db->sessions;

// Отримати повідомлення від обраного клієнта
$userMessages = [];
if (isset($_POST['getMessages'])) {
    $login = $_POST['login'];
    $user = $usersCollection->findOne(['login' => $login]);
    if ($user && isset($user['messages'])) {
        $userMessages = $user['messages'];
        // Зберігаємо в localStorage через JavaScript
        echo "<script>localStorage.setItem('lastMessages', JSON.stringify({login: '$login', messages: " . json_encode($userMessages) . "}));</script>";
    } else {
        $userMessages = ["Користувача не знайдено або немає повідомлень!"];
    }
}

// Обчислення загального вхідного і вихідного трафіку
$totalTraffic = '';
if (isset($_POST['getTraffic'])) {
    $pipeline = [
        ['$group' => ['_id' => null, 'total_inbound' => ['$sum' => '$inbound_traffic'], 'total_outbound' => ['$sum' => '$outbound_traffic']]]
    ];
    $traffic = $sessionsCollection->aggregate($pipeline)->toArray()[0];
    $totalTraffic = "Вхідний трафік: " . $traffic['total_inbound'] . " MB, Вихідний трафік: " . $traffic['total_outbound'] . " MB";
}

// Список клієнтів з від'ємним балансом
$negativeBalanceUsers = [];
if (isset($_POST['getNegativeBalanceUsers'])) {
    $negativeBalanceUsers = $usersCollection->find(['balance' => ['$lt' => 0]])->toArray();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>MongoDB PHP Example</title>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Відображення останніх збережених повідомлень з localStorage
            const lastMessages = JSON.parse(localStorage.getItem('lastMessages'));
            if (lastMessages) {
                let messagesHTML = `<strong>Останні повідомлення для ${lastMessages.login}:</strong><ul>`;
                lastMessages.messages.forEach(function(message) {
                    messagesHTML += `<li>${message}</li>`;
                });
                messagesHTML += '</ul>';
                document.getElementById('lastMessages').innerHTML = messagesHTML;
            } else {
                document.getElementById('lastMessages').textContent = 'Немає збережених повідомлень.';
            }
        });
    </script>
</head>
<body>

<h1>MongoDB PHP Example</h1>

<!-- Форма для отримання повідомлень від користувача -->
<form method="POST">
    <label for="login">Введіть логін користувача:</label>
    <input type="text" id="login" name="login" required>
    <button type="submit" name="getMessages">Отримати повідомлення</button>
</form>
<?php if ($userMessages): ?>
    <ul>
        <?php foreach ($userMessages as $message): ?>
            <li><?php echo htmlspecialchars($message); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- Форма для обчислення загального трафіку -->
<form method="POST">
    <button type="submit" name="getTraffic">Обчислити загальний трафік</button>
</form>
<p><?php echo htmlspecialchars($totalTraffic); ?></p>

<!-- Форма для виведення клієнтів з від'ємним балансом -->
<form method="POST">
    <button type="submit" name="getNegativeBalanceUsers">Вивести клієнтів з від'ємним балансом</button>
</form>
<ul>
    <?php
    foreach ($negativeBalanceUsers as $user) {
        echo '<li>' . htmlspecialchars($user['login']) . " (Баланс: " . htmlspecialchars($user['balance']) . " грн)</li>";
    }
    ?>
</ul>

<!-- Виведення останніх збережених повідомлень з localStorage -->
<p id="lastMessages"></p>

</body>
</html>

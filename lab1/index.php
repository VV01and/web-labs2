<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пошук видань у бібліотеці</title>
</head>
<body>
    <h1>Пошук видань у бібліотеці</h1>

    <!-- Форма для пошуку за видавництвом -->
    <form action="db_access/search_by_publisher.php" method="GET">
        <h3>Пошук книжок зазначеного видавництва:</h3>
        <label for="publisher">Оберіть видавництво:</label>
        <select name="publisher" id="publisher">
            <?php
            require 'db_access/db_connect.php';
            $stmt = $pdo->query("SELECT DISTINCT PUBLISHER FROM literature");
            $publishers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($publishers as $publisher) {
                echo "<option value=\"{$publisher['PUBLISHER']}\">{$publisher['PUBLISHER']}</option>";
            }
            ?>
        </select>
        <button type="submit">Результати пошуку</button>
    </form>

    <br>

    <!-- Форма для пошуку за автором -->
    <form action="db_access/search_by_author.php" method="GET">
        <h3>Пошук книжок зазначеного автора</h3>
        <label for="author">Оберіть автора:</label>
        <select name="author" id="author">
            <?php
            require 'db_access/db_connect.php';
            $stmt = $pdo->query("SELECT id, NAME FROM author");
            $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($authors as $author) {
                echo "<option value=\"{$author['NAME']}\">{$author['NAME']}</option>";
            }
            ?>
        </select>
        <button type="submit">Результати пошуку</button>
    </form>

    <br>

    <!-- Форма для пошуку за роком видання -->
    <form action="db_access/search_by_year.php" method="GET">
        <h3>Пошук книжок, журналів і газет виданих в зазначеному періоді часу</h3>
        <label for="start_year">Початковий рік:</label>
        <input type="text" name="start_year" id="start_year" value="2000">
        <label for="end_year">Кінцевий рік:</label>
        <input type="text" name="end_year" id="end_year" value="2024">
        <button type="submit">Результати пошуку</button>
    </form>

    <br>

</body>
</html>

<?php
require 'db_connect.php';
require 'utils.php';

if (isset($_GET['publisher'])) {
    $publisher = $_GET['publisher'];

    $stmt = $pdo->prepare("
        SELECT l.NAME, l.ISBN, l.PUBLISHER, l.YEAR, l.QUANTITY, a.NAME AS AUTHOR
        FROM literature l
        JOIN book_authrs ba ON l.id = ba.FID_BOOK
        JOIN author a ON ba.FID_AUTH = a.id
        WHERE l.PUBLISHER = :publisher AND l.LITERATE = 'Book'
    ");
    $stmt->execute(['publisher' => $publisher]);

    $books = $stmt->fetchAll();

    if ($books) {
        print_table($books);
    } else {
        echo "<p>Немає книг від цього видавництва.</p>";
    }
}
?>

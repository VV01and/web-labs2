<?php
require 'db_connect.php';

if (isset($_GET['author'])) {
    $author = $_GET['author'];

    $stmt = $pdo->prepare("
        SELECT l.NAME, l.ISBN, l.PUBLISHER, l.YEAR, l.QUANTITY, a.NAME AS AUTHOR
        FROM literature l
        JOIN book_authrs ba ON l.id = ba.FID_BOOK
        JOIN author a ON ba.FID_AUTH = a.id
        WHERE a.NAME = :author AND l.LITERATE = 'Book'
    ");
    $stmt->execute(['author' => $author]);

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<books>';
    foreach ($books as $book) {
        echo '<book>';
        echo '<name>' . htmlspecialchars($book['NAME']) . '</name>';
        echo '<isbn>' . htmlspecialchars($book['ISBN']) . '</isbn>';
        echo '<publisher>' . htmlspecialchars($book['PUBLISHER']) . '</publisher>';
        echo '<year>' . htmlspecialchars($book['YEAR']) . '</year>';
        echo '<count>' . htmlspecialchars($book['QUANTITY']) . '</count>';
        echo '<author>' . htmlspecialchars($book['AUTHOR']) . '</author>';
        echo '</book>';
    }
    echo '</books>';
}
?>
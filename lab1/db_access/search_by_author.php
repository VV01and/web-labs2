<?php
require 'db_connect.php';
require 'utils.php';

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

    $books = $stmt->fetchAll();

    if ($books) {
        print_table($books);
    } else {
        echo "<p>Немає книг цього автора.</p>";
    }
}
?>

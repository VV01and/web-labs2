<?php
require 'db_connect.php';
require 'utils.php';

if (isset($_GET['start_year']) && isset($_GET['end_year'])) {
    $start_year = $_GET['start_year'];
    $end_year = $_GET['end_year'];

    $stmt = $pdo->prepare("
        SELECT l.NAME, l.ISBN, l.PUBLISHER, l.YEAR, l.QUANTITY, a.NAME AS AUTHOR
        FROM literature l
        JOIN book_authrs ba ON l.id = ba.FID_BOOK
        JOIN author a ON ba.FID_AUTH = a.id
        WHERE l.YEAR BETWEEN :start_year AND :end_year AND l.LITERATE IN ('Book', 'Journal', 'Newspaper')
    ");
    $stmt->execute(['start_year' => $start_year, 'end_year' => $end_year]);

    $books = $stmt->fetchAll();

    if ($books) {
        print_table($books);
    } else {
        echo "<p>Немає книг, виданих у цьому періоді.</p>";
    }
}
?>

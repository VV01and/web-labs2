<?php
function print_table($books) : void {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>
            <th>Name</th>
            <th>ISBN</th>
            <th>Publisher</th>
            <th>Year</th>
            <th>Count</th>
            <th>Author</th>
        </tr>";
    foreach ($books as $book) {
        echo "<tr>
                <td>{$book['NAME']}</td>
                <td>{$book['ISBN']}</td>
                <td>{$book['PUBLISHER']}</td>
                <td>{$book['YEAR']}</td>
                <td>{$book['QUANTITY']}</td>
                <td>{$book['AUTHOR']}</td>
            </tr>";
    }
    echo "</table>";
}
?>
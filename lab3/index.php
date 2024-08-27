<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пошук видань у бібліотеці</title>
    <script>
        function searchByPublisher() {
            const publisher = document.getElementById('publisher').value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'db_access/search_by_publisher.php?publisher=' + encodeURIComponent(publisher), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('results').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function searchByAuthor() {
            const author = document.getElementById('author').value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'db_access/search_by_author.php?author=' + encodeURIComponent(author), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const xmlDoc = xhr.responseXML;
                    let table = "<table border='1' cellpadding='5' cellspacing='0'>";
                    table += "<tr><th>Name</th><th>ISBN</th><th>Publisher</th><th>Year</th><th>Count</th><th>Author</th></tr>";

                    const books = xmlDoc.getElementsByTagName("book");
                    for (var i = 0; i < books.length; i++) {
                        let name = books[i].getElementsByTagName("name")[0].textContent;
                        let isbn = books[i].getElementsByTagName("isbn")[0].textContent;
                        let publisher = books[i].getElementsByTagName("publisher")[0].textContent;
                        let year = books[i].getElementsByTagName("year")[0].textContent;
                        let count = books[i].getElementsByTagName("count")[0].textContent;
                        let author = books[i].getElementsByTagName("author")[0].textContent;

                        table += "<tr><td>" + name + "</td><td>" + isbn + "</td><td>" + publisher + "</td><td>" + year + "</td><td>" + count + "</td><td>" + author + "</td></tr>";
                    }
                    table += "</table>";
                    document.getElementById('results').innerHTML = table;
                }
            };
            xhr.send();
        }

        async function searchByYear() {
            const startYear = document.getElementById('start_year').value;
            const endYear = document.getElementById('end_year').value;

            const baseUrl = 'lab3/db_access/search_by_year.php';
            const url = new URL(baseUrl, window.location.origin);

            url.searchParams.append('start_year', startYear);
            url.searchParams.append('end_year', endYear);

            try {
                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }

                const books = await response.json();

                let table = "<table border='1' cellpadding='5' cellspacing='0'>";
                table += "<tr><th>Name</th><th>ISBN</th><th>Publisher</th><th>Year</th><th>Count</th><th>Author</th></tr>";

                books.forEach(book => {
                    book.ISBN = book.ISBN || ''; // Handle missing ISBN
                    table += `<tr><td>${book.NAME}</td><td>${book.ISBN}</td><td>${book.PUBLISHER}</td><td>${book.YEAR}</td><td>${book.QUANTITY}</td><td>${book.AUTHOR}</td></tr>`;
                });

                table += "</table>";
                document.getElementById('results').innerHTML = table;
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        }
    </script>
</head>
<body>
    <h1>Пошук видань у бібліотеці</h1>

    <!-- Форма для пошуку за видавництвом -->
    <form onsubmit="searchByPublisher(); return false;">
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
    <form onsubmit="searchByAuthor(); return false;">
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
    <form onsubmit="searchByYear(); return false;">
        <h3>Пошук книжок, журналів і газет виданих в зазначеному періоді часу</h3>
        <label for="start_year">Початковий рік:</label>
        <input type="text" name="start_year" id="start_year" value="2000">
        <label for="end_year">Кінцевий рік:</label>
        <input type="text" name="end_year" id="end_year" value="2024">
        <button type="submit">Результати пошуку</button>
    </form>

    <br>

    <!-- Блок для відображення результатів пошуку -->
    <h2>Результати пошуку:</h2>
    <div id="results"></div>
</body>
</html>

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
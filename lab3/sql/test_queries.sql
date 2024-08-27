-- select by publisher, exemplary parameters values
SELECT l.NAME, l.ISBN, l.PUBLISHER, l.YEAR, l.QUANTITY, a.NAME AS AUTHOR
FROM literature l
JOIN book_authrs ba ON l.id = ba.FID_BOOK
JOIN author a ON ba.FID_AUTH = a.id
WHERE l.PUBLISHER = 'ХНУРЕ' AND l.LITERATE = 'Book';

-- select by publication year, exemplary parameters values
SELECT l.NAME, l.ISBN, l.PUBLISHER, l.YEAR, l.QUANTITY, a.NAME AS AUTHOR
FROM literature l
JOIN book_authrs ba ON l.id = ba.FID_BOOK
JOIN author a ON ba.FID_AUTH = a.id
WHERE l.YEAR BETWEEN '2024' AND '2024' AND l.LITERATE IN ('Book', 'Journal', 'Newspaper');

-- select by author, exemplary parameters values
SELECT l.NAME, l.ISBN, l.PUBLISHER, l.YEAR, l.QUANTITY, a.NAME AS AUTHOR
FROM literature l
JOIN book_authrs ba ON l.id = ba.FID_BOOK
JOIN author a ON ba.FID_AUTH = a.id
WHERE a.NAME = 'Іваницький Руслан' AND l.LITERATE = 'Book';
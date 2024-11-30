<?php
// Dane do połączenia z bazą danych
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'moja_strona');

// Nawiązanie połączenia z bazą danych
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Sprawdzenie połączenia
if (!$link) {
    die('<b>Przerwane połączenie: </b>' . mysqli_connect_error());
}

// Ustawienia administratora
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASS', '1234');  
?>

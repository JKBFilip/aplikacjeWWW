<?php
$nr_indeksu = '169237';
$nrGrupy = '4';
echo 'Jakub Filipiak ' . $nr_indeksu . ' grupa ' . $nrGrupy . ' <br /><br />';

// a) Metoda include() i require_once()
echo 'a) Metoda include() i require_once() <br />';
include 'plik_zalaczany.php'; // Upewnij się, że plik istnieje

// b) Warunki if, else, elseif, switch
echo '<br />b) Warunki if, else, elseif, switch <br />';
$liczba = 5;

if ($liczba < 0) {
    echo 'Liczba jest ujemna.<br />';
} elseif ($liczba == 0) {
    echo 'Liczba jest zerem.<br />';
} else {
    echo 'Liczba jest dodatnia.<br />';
}

switch ($liczba) {
    case 1:
        echo 'Liczba to 1.<br />';
        break;
    case 5:
        echo 'Liczba to 5.<br />';
        break;
    default:
        echo 'Liczba nie jest ani 1, ani 5.<br />';
        break;
}

// c) Pętla while() i for()
echo '<br />c) Pętla while() i for() <br />';
$licznik = 0;

echo 'Pętla while:<br />';
while ($licznik < 5) {
    echo 'Licznik: ' . $licznik . '<br />';
    $licznik++;
}

echo '<br />Pętla for:<br />';
for ($i = 0; $i < 5; $i++) {
    echo 'Indeks: ' . $i . '<br />';
}

// d) Typy zmiennych $_GET, $_POST, $_SESSION
echo '<br />d) Typy zmiennych $_GET, $_POST, $_SESSION <br />';

// Przykładowe użycie $_GET
if (isset($_GET['name'])) {
    echo 'Imię: ' . htmlspecialchars($_GET['name']) . '<br />';
} else {
    echo 'Nie podano imienia w $_GET.<br />';
}

// Przykładowe użycie $_POST (tylko po wysłaniu formularza)
// echo 'Imię: ' . htmlspecialchars($_POST['name']) . '<br />'; // Odkomentuj po dodaniu formularza

// Przykładowe użycie $_SESSION (należy najpierw uruchomić sesję)
session_start();
if (isset($_SESSION['user'])) {
    echo 'Użytkownik: ' . htmlspecialchars($_SESSION['user']) . '<br />';
} else {
    echo 'Brak zalogowanego użytkownika.<br />';
}
?>

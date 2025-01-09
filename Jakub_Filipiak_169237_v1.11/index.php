<?php
// Dołączenie plików konfiguracyjnych i funkcji wyświetlających strony
include('cfg.php');
include('php/showpage.php');
?>

<!-- Załączenie pliku CSS oraz ustawienia meta -->
<link rel="stylesheet" type="text/css" href="style/style_css.css">
<head>
    <!-- Ustawienia meta -->
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Jakub Filipiak" />
    
    <!-- Dołączenie plików JavaScript -->
    <script src="java_script/kolorujtlo.js" type="text/javascript"></script>
    <script src="java_script/timedate.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Tytuł strony -->
    <title>Hodowla żółwia wodnego</title>
</head>
<body onload="startclock()">
<!-- Funkcja startclock uruchamia zegar po załadowaniu strony -->

<!-- Wyświetlanie zegarka i daty -->
<div id="zegarek"></div>
<div id="data"></div>

<!-- Przyciski do zmiany koloru tła -->
<form method="POST" name="background">
    <input type="button" value="Żółty" onclick="changeBackground('#FFFF00')">
    <input type="button" value="Czarny" onclick="changeBackground('#000000')">
    <input type="button" value="Biały" onclick="changeBackground('#FFFFFF')">
    <input type="button" value="Zielony" onclick="changeBackground('#00FF00')">
    <input type="button" value="Niebieski" onclick="changeBackground('#0000FF')">
    <input type="button" value="Pomarańczowy" onclick="changeBackground('#FF8000')">
    <input type="button" value="Szary" onclick="changeBackground('#C0C0C0')">
    <input type="button" value="Czerwony" onclick="changeBackground('#FF0000')">
</form>

<!-- Animacja: kliknięcie powoduje powiększenie -->
<div id="animacjaTestowal" class="test-block">Kliknij, a się powiększę</div>
<script>
    // Po kliknięciu animacja powiększa element
    $("#animacjaTestowal").on("click", function(){
        $(this).animate({
            width: "500px",
            opacity: 0.4,
            fontSize: "3em",
            borderWidth: "10px"
        }, 1500); // Animacja trwa 1,5 sekundy
    });
</script>

<!-- Animacja: najazd kursorem powoduje powiększenie -->
<div id="animacjaTestowa2" class="test-block">
    Najedź kursorem, a się powiększę
</div>
<script>
    // Zdarzenia najazdu i zjazdu kursora zmieniają rozmiar elementu
    $("#animacjaTestowa2").on({
        "mouseover" : function() {
            $(this).animate({
                width: 300
            }, 800); // Powiększenie trwa 0,8 sekundy
        },
        "mouseout" : function() {
            $(this).animate({
                width: 200
            }, 800); // Powrót do pierwotnego rozmiaru trwa 0,8 sekundy
        }
    });
</script>

<!-- Animacja: wielokrotne kliknięcia powodują powiększanie -->
<div id="animacjaTestowa3" class="test-block">
    Klikaj, abym urósł
</div>
<script>
    // Każde kliknięcie zwiększa rozmiar elementu
    $("#animacjaTestowa3").on("click", function() {
        if (!$(this).is(":animated")) { // Sprawdzenie, czy element już się nie animuje
            $(this).animate({
                width: "+=" + 50,    // Zwiększenie szerokości o 50px
                height: "+=" + 10,   // Zwiększenie wysokości o 10px
                opacity: "+=" + 0.1  // Zwiększenie przezroczystości
            }, 3000); // Animacja trwa 3 sekundy
        }
    });
</script>

<!-- Nagłówek z nawigacją -->
<header>
    <h1 class="header">Jak hodować żółwia wodnego</h1>
    <nav>
        <!-- Menu nawigacyjne -->
        <ul>
            <li><a href="index.php">MENU </a></li>
            <li><a href="index.php?idp=gatunek">Wybór gatunku </a></li>  
            <li><a href="index.php?idp=akwarium">Akwarium </a></li>
            <li><a href="index.php?idp=temperatura">Temperatura i oświetlenie </a></li>
            <li><a href="index.php?idp=zywienie">Żywienie </a></li>
            <li><a href="index.php?idp=zdrowie">Zdrowie i higiena </a></li>
            <li><a href="index.php?idp=kontakt">KONTAKT </a></li>
            <li><a href="index.php?idp=filmy">Filmy </a></li>
        </ul>
    </nav>
</header>

<!-- Stopka -->
<footer>    
<?php
// Wyświetlenie informacji o autorze
$nr_indeksu = '169237';
$nrGrupy = 'ISI4';
echo 'Autor: Jakub Filipiak '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
?>
</footer>

<!-- Główna treść strony -->
<main>
<?php
// Wyłączenie ostrzeżeń i błędów
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Wybór odpowiedniego pliku HTML na podstawie parametru GET
if ($_GET['idp'] == '') $strona = 'html/glowna.html';
if ($_GET['idp'] == 'akwarium') $strona = 'html/akwarium.html';
if ($_GET['idp'] == 'gatunek') $strona = 'html/gatunek.html';
if ($_GET['idp'] == 'kontakt') $strona = 'html/kontakt.html';
if ($_GET['idp'] == 'temperatura') $strona = 'html/temperatura.html';
if ($_GET['idp'] == 'zdrowie') $strona = 'html/zdrowie.html';
if ($_GET['idp'] == 'zywienie') $strona = 'html/zywienie.html';
if ($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';

// Sprawdzenie, czy plik istnieje i jego dołączenie
if (file_exists($strona)) {
    include($strona);
} else {
    echo 'Strona nie została znaleziona.';
}
?>
</main>
</body>

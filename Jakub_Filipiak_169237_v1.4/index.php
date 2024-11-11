<link rel="stylesheet" type="text/css" href="style/style_css.css">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="pl" />
<meta name="Author" content="Jakub Filipiak" />
<script src="java_script/kolorujtlo.js" type="text/javascript"></script>
<script src="java_script/timedate.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<title>Hodowla żółwia wodnego</title>
</head>
<body onload="startclock()">

<div id="zegarek"></div>
<div id="data"></div>
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
	
	<div id="animacjaTestowal" class="test-block">Kliknij, a się powiększę</div>

<script>
    $("#animacjaTestowal").on("click", function(){
        $(this).animate({
            width: "500px",
            opacity: 0.4,
            fontSize: "3em",
            borderWidth: "10px"
        }, 1500);
    });
</script>
<div id="animacjaTestowa2" class="test-block">
    Najedź kursorem, a się powiększę
</div>

<script>
    $("#animacjaTestowa2").on({
        "mouseover" : function() {
            $(this).animate({
                width: 300
            }, 800);
        },
        "mouseout" : function() {
            $(this).animate({
                width: 200
            }, 800);
        }
    });
</script>
<div id="animacjaTestowa3" class="test-block">
    Klikaj, abym urósł
</div>
<script>
$( "#animacjaTestowa3" ).on( "click", function() {
    if ( !$( this ).is( ":animated" ) ) {
        $( this ).animate({
            width: "+=" + 50,
            height: "+=" + 10,
            opacity: "+=" + 0.1,
            duration: 3000 // inny sposób deklaracji czasu trwania animacji
        });
    }
});
</script>
	<header>
		
	<h1 class="header">Jak hodować żółwia wodnego</h1>
		<nav>
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

<footer>	
<?php
 $nr_indeksu = '169237';
 $nrGrupy = 'ISI4';
 echo 'Autor: Jakub Filipiak '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
?>
</footer>
<main>
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

if ($_GET['idp'] == '') $strona = 'html/glowna.html';
if ($_GET['idp'] == 'akwarium') $strona = 'html/akwarium.html';
if ($_GET['idp'] == 'gatunek') $strona = 'html/gatunek.html';
if ($_GET['idp'] == 'kontakt') $strona = 'html/kontakt.html';
if ($_GET['idp'] == 'temperatura') $strona = 'html/temperatura.html';
if ($_GET['idp'] == 'zdrowie') $strona = 'html/zdrowie.html';
if ($_GET['idp'] == 'zywienie') $strona = 'html/zywienie.html';
if ($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';

if (file_exists($strona)) {
    include($strona);
} else {
    echo 'Strona nie została znaleziona.';
}
?>
</main>
</body>
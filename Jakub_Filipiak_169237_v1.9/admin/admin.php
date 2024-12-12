<?php
// --- Uruchomienie sesji i wczytanie konfiguracji ---
session_start();
require_once '../cfg.php'; 

// --- Połączenie z bazą danych ---
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$link) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

/**
 * Wyświetla formularz logowania.
 */
function FormularzLogowania() {
    echo '<form method="POST" action="admin.php">';
    echo '<label for="login">Login:</label>';
    echo '<input type="text" id="login" name="login" required>';
    echo '<label for="pass">Hasło:</label>';
    echo '<input type="password" id="pass" name="pass" required>';
    echo '<button type="submit" name="submit">Zaloguj</button>';
    echo '</form>';
}

// --- Dodanie funkcji zarządzania kategoriami ---

/**
 * Funkcja dodawania nowej kategorii.
 */
function DodajKategorie($link, $nazwa, $matka = 0) {
    $nazwa = mysqli_real_escape_string($link, $nazwa);
    $matka = intval($matka);
    $query = "INSERT INTO zarzadzanie (matka, nazwa) VALUES ($matka, '$nazwa')";
    return mysqli_query($link, $query);
}

/**
 * Funkcja edytowania istniejącej kategorii.
 */
function EdytujKategorie($link, $id, $nazwa, $matka = 0) {
    $id = intval($id);
    $nazwa = mysqli_real_escape_string($link, $nazwa);
    $matka = intval($matka);
    $query = "UPDATE zarzadzanie SET nazwa = '$nazwa', matka = $matka WHERE ID = $id";
    return mysqli_query($link, $query);
}

/**
 * Funkcja usuwania kategorii.
 */
function UsunKategorie($link, $id) {
    $id = intval($id);
    $query = "DELETE FROM zarzadzanie WHERE ID = $id OR matka = $id"; // Usuwanie kategorii i jej podkategorii
    return mysqli_query($link, $query);
}

/**
 * Funkcja wyświetlania kategorii w formie drzewa.
 */
function PokazKategorie($link, $matka = 0, $poziom = 0) {
    $matka = intval($matka);
    $query = "SELECT * FROM zarzadzanie WHERE matka = $matka ORDER BY nazwa ASC";
    $result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo str_repeat("-", $poziom) . htmlspecialchars($row['nazwa']) . " <a href='admin.php?action=edit_category&id=" . $row['ID'] . "'>Edytuj</a> | <a href='admin.php?action=delete_category&id=" . $row['ID'] . "' onclick=\"return confirm('Czy na pewno chcesz usunąć tę kategorię?')\">Usuń</a><br>";
        PokazKategorie($link, $row['ID'], $poziom + 1); // Rekurencja dla podkategorii
    }
}

// --- Sprawdzanie sesji logowania ---
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // --- Wyświetlanie panelu administracyjnego ---
    echo '<a href="admin.php?action=logout" style="display:inline-block;padding:10px 15px;background-color:#ff4444;color:white;text-decoration:none;border-radius:5px;">Wyloguj się</a><br><br>';

    // --- Obsługa akcji w panelu ---
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'list':
                echo ListaPodstron($link);
                break;

            case 'add':
                echo DodajNowaPodstrone();
                break;

            case 'edit':
                if (isset($_GET['id'])) {
                    echo EdytujPodstrone($link, intval($_GET['id']));
                } else {
                    echo '<p>Brak ID podstrony do edycji.</p>';
                }
                break;

            case 'delete':
                if (isset($_GET['id'])) {
                    UsunPodstrone($link, intval($_GET['id']));
                } else {
                    echo '<p>Brak ID podstrony do usunięcia.</p>';
                }
                break;

            case 'logout':
                session_destroy();
                header('Location: admin.php');
                exit();

            case 'add_category':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nazwa = $_POST['nazwa'] ?? '';
                    $matka = $_POST['matka'] ?? 0;
                    if (DodajKategorie($link, $nazwa, $matka)) {
                        echo "Kategoria dodana!";
                    } else {
                        echo "Błąd dodawania kategorii.";
                    }
                }
                echo '<form method="POST" action="admin.php?action=add_category">';
                echo '<label for="nazwa">Nazwa kategorii:</label><input type="text" name="nazwa" required><br>';
                echo '<label for="matka">Matka (ID):</label><input type="number" name="matka" value="0"><br>';
                echo '<button type="submit">Dodaj</button></form>';
                echo '<br><a href="admin.php?action=list_categories" style="display:inline-block;padding:10px 15px;background-color:#007BFF;color:white;text-decoration:none;border-radius:5px;">Wróć</a>';
                break;

            case 'edit_category':
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']);
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $nazwa = $_POST['nazwa'] ?? '';
                        $matka = $_POST['matka'] ?? 0;
                        if (EdytujKategorie($link, $id, $nazwa, $matka)) {
                            echo "Kategoria zaktualizowana!";
                        } else {
                            echo "Błąd edycji kategorii.";
                        }
                    }
                    $query = "SELECT * FROM zarzadzanie WHERE ID = $id";
                    $result = mysqli_query($link, $query);
                    $kategoria = mysqli_fetch_assoc($result);
                    echo '<form method="POST" action="admin.php?action=edit_category&id=' . $id . '">';
                    echo '<label for="nazwa">Nazwa kategorii:</label><input type="text" name="nazwa" value="' . htmlspecialchars($kategoria['nazwa']) . '" required><br>';
                    echo '<label for="matka">Matka (ID):</label><input type="number" name="matka" value="' . intval($kategoria['matka']) . '"><br>';
                    echo '<button type="submit">Zapisz</button></form>';
                    echo '<br><a href="admin.php?action=list_categories" style="display:inline-block;padding:10px 15px;background-color:#007BFF;color:white;text-decoration:none;border-radius:5px;">Wróć</a>';
                } else {
                    echo "Nie podano ID kategorii do edycji.";
                }
                break;

            case 'delete_category':
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']);
                    if (UsunKategorie($link, $id)) {
                        echo "Kategoria usunięta!";
                    } else {
                        echo "Błąd usuwania kategorii.";
                    }
                } else {
                    echo "Nie podano ID kategorii do usunięcia.";
                }
                echo '<br><a href="admin.php?action=list_categories" style="display:inline-block;padding:10px 15px;background-color:#007BFF;color:white;text-decoration:none;border-radius:5px;">Wróć</a>';
                break;

            case 'list_categories':
                echo '<h1>Drzewo kategorii:</h1>';
                PokazKategorie($link);
                echo '<br><a href="admin.php?action=add_category" style="display:inline-block;padding:10px 15px;background-color:#4CAF50;color:white;text-decoration:none;border-radius:5px;">Dodaj Kategorię</a>';
                echo '<br><a href="admin.php" style="display:inline-block;padding:10px 15px;background-color:#007BFF;color:white;text-decoration:none;border-radius:5px;">Wróć do Panelu</a>';
                break;

            default:
                echo "Nieznana akcja.";
                break;
        }
    } else {
        echo '<h1>Panel Administracyjny</h1>';
        echo '<div style="margin-bottom:20px;"><a href="admin.php?action=list_categories" style="display:inline-block;padding:10px 15px;background-color:#007BFF;color:white;text-decoration:none;border-radius:5px;">Zarządzaj Kategoriami</a></div>';
        echo ListaPodstron($link);
    }
} else {
    // --- Obsługa logowania ---
    if (isset($_POST['submit'])) {
        $login = htmlspecialchars(trim($_POST['login']));
        $pass = htmlspecialchars(trim($_POST['pass']));

        if ($login === ADMIN_LOGIN && $pass === ADMIN_PASS) {
            $_SESSION['logged_in'] = true;
            header('Location: admin.php');
            exit();
        } else {
            echo '<p>Nieprawidłowy login lub hasło.</p>';
        }
    }
    FormularzLogowania();
}

/**
 * Wyświetla listę podstron.
 */
function ListaPodstron($link) {
    $query = "SELECT ID, page_title FROM page_list ORDER BY ID DESC";
    $result = mysqli_query($link, $query);
    $output = '<div class="subpage-list"><h1>Lista Podstron</h1>';
    $output .= '<a href="admin.php?action=add">Dodaj Nową Podstronę</a><br><br>';
    $output .= '<table border="1"><tr><th>ID</th><th>Tytuł Podstrony</th><th>Akcje</th></tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr><td>' . intval($row['ID']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['page_title']) . '</td>';
        $output .= '<td><a href="admin.php?action=edit&id=' . intval($row['ID']) . '">Edytuj</a> | ';
        $output .= '<a href="admin.php?action=delete&id=' . intval($row['ID']) . '" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\')">Usuń</a></td></tr>';
    }
    $output .= '</table></div>';
    return $output;
}

/**
 * Formularz dodawania nowej podstrony.
 */
function DodajNowaPodstrone() {
    $output = '<div class="add-subpage-form"><h1>Dodaj Nową Podstronę</h1>';
    $output .= '<form method="post" action="admin.php?action=process_add">';
    $output .= '<label for="page_title">Tytuł Podstrony:</label><br>';
    $output .= '<input type="text" name="page_title" id="page_title" required><br><br>';
    $output .= '<label for="page_content">Treść Podstrony:</label><br>';
    $output .= '<textarea name="page_content" id="page_content" rows="10" cols="50" required></textarea><br><br>';
    $output .= '<label for="status">Aktywna:</label>';
    $output .= '<input type="checkbox" name="status" id="status" value="1"><br><br>';
    $output .= '<input type="submit" value="Dodaj Podstronę"></form></div>';
    return $output;
}

/**
 * Obsługa dodawania nowej podstrony.
 */
if (isset($_GET['action']) && $_GET['action'] === 'process_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($link, $_POST['page_title']);
    $content = mysqli_real_escape_string($link, $_POST['page_content']);
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}

/**
 * Formularz edycji podstrony.
 */
function EdytujPodstrone($link, $id) {
    $query = "SELECT page_title, page_content, status FROM page_list WHERE ID = $id";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
    $output = '<div class="edit-subpage-form"><h1>Edytuj Podstronę</h1>';
    $output .= '<form method="post" action="admin.php?action=process_edit&id=' . intval($id) . '">';
    $output .= '<label for="page_title">Tytuł Podstrony:</label><br>';
    $output .= '<input type="text" name="page_title" value="' . htmlspecialchars($row['page_title']) . '" required><br><br>';
    $output .= '<label for="page_content">Treść Podstrony:</label><br>';
    $output .= '<textarea name="page_content" rows="10" cols="50" required>' . htmlspecialchars($row['page_content']) . '</textarea><br><br>';
    $output .= '<label for="status">Aktywna:</label>';
    $output .= '<input type="checkbox" name="status" value="1"' . ($row['status'] ? ' checked' : '') . '><br><br>';
    $output .= '<input type="submit" value="Zapisz zmiany"></form></div>';
    return $output;
}

/**
 * Obsługa edycji podstrony.
 */
if (isset($_GET['action']) && $_GET['action'] === 'process_edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_GET['id']);
    $title = mysqli_real_escape_string($link, $_POST['page_title']);
    $content = mysqli_real_escape_string($link, $_POST['page_content']);
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "UPDATE page_list SET page_title = '$title', page_content = '$content', status = $status WHERE ID = $id";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}

/**
 * Usuwanie podstrony.
 */
function UsunPodstrone($link, $id) {
    $query = "DELETE FROM page_list WHERE ID = $id";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}
?>

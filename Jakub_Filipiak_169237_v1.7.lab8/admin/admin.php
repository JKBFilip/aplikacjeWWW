<?php
session_start();
require_once '../cfg.php'; 

// Połączenie z bazą danych
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$link) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

// Funkcja wyświetlająca formularz logowania
function FormularzLogowania() {
    echo '<form method="POST" action="admin.php">';
    echo '<label for="login">Login:</label>';
    echo '<input type="text" id="login" name="login" required>';
    echo '<label for="pass">Hasło:</label>';
    echo '<input type="password" id="pass" name="pass" required>';
    echo '<button type="submit" name="submit">Zaloguj</button>';
    echo '</form>';
}

// Sprawdzanie sesji logowania
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Obsługa akcji w panelu administracyjnym
	echo '<a href="admin.php?action=logout" style="display:inline-block;padding:10px 15px;background-color:#ff4444;color:white;text-decoration:none;border-radius:5px;">Wyloguj się</a><br><br>';

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
                    echo EdytujPodstrone($link, $_GET['id']);
                } else {
                    echo '<p>Brak ID podstrony do edycji.</p>';
                }
                break;

            case 'delete':
                if (isset($_GET['id'])) {
                    UsunPodstrone($link, $_GET['id']);
                } else {
                    echo '<p>Brak ID podstrony do usunięcia.</p>';
                }
                break;

            case 'logout':
                session_destroy();
                header('Location: admin.php');
                exit();

            default:
                echo '<p>Nieznana akcja.</p>';
                break;
        }
    } else {
        echo ListaPodstron($link);
    }
} else {
    if (isset($_POST['submit'])) {
        $login = $_POST['login'];
        $pass = $_POST['pass'];

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


// Funkcja wyświetlająca listę podstron
function ListaPodstron($link) {
    $query = "SELECT ID, page_title FROM page_list ORDER BY ID DESC";
    $result = mysqli_query($link, $query);
    $output = '<div class="subpage-list"><h1>Lista Podstron</h1>';
    $output .= '<a href="admin.php?action=add">Dodaj Nową Podstronę</a><br><br>';
    $output .= '<table border="1"><tr><th>ID</th><th>Tytuł Podstrony</th><th>Akcje</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr><td>' . $row['ID'] . '</td>';
        $output .= '<td>' . htmlspecialchars($row['page_title']) . '</td>';
        $output .= '<td><a href="admin.php?action=edit&id=' . $row['ID'] . '">Edytuj</a> | ';
        $output .= '<a href="admin.php?action=delete&id=' . $row['ID'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\')">Usuń</a></td></tr>';
    }
    $output .= '</table></div>';
    return $output;
}

// Funkcja dodawania nowej podstrony
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

// Obsługa dodawania podstrony
if (isset($_GET['action']) && $_GET['action'] === 'process_add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($link, $_POST['page_title']);
    $content = mysqli_real_escape_string($link, $_POST['page_content']);
    $status = isset($_POST['status']) ? 1 : 0;

    $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}

// Funkcja edytowania podstrony
function EdytujPodstrone($link, $id) {
    $query = "SELECT page_title, page_content, status FROM page_list WHERE ID = " . intval($id);
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

// Obsługa edycji podstrony
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

// Funkcja usuwania podstrony
function UsunPodstrone($link, $id) {
    $query = "DELETE FROM page_list WHERE ID = " . intval($id);
    mysqli_query($link, $query);
    header('Location: admin.php?action=list');
    exit();
}
?>

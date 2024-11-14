<?php

session_start();


require_once 'cfg.php';


function FormularzLogowania() {
    echo '<form method="POST" action="admin.php">';
    echo '<label for="login">Login:</label>';
    echo '<input type="text" id="login" name="login" required>';
    echo '<label for="pass">Hasło:</label>';
    echo '<input type="password" id="pass" name="pass" required>';
    echo '<button type="submit" name="submit">Zaloguj</button>';
    echo '</form>';
}


if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    
    echo 'Witaj w panelu administracyjnym!<br>';
    echo '<a href="admin.php?action=logout">Wyloguj się</a>';

  
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        
        session_destroy();
        header('Location: admin.php');
        exit();
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

function ListaPodstron() {
   
    $output = '<div class="subpage-list">';
    $output .= '<h1>Lista Podstron</h1>';
    $output .= '<table border="1">';
    $output .= '<tr><th>ID</th><th>Tytuł Podstrony</th><th>Akcje</th></tr>';
    
    
    $query = "SELECT id, tytul FROM page_list ORDER BY data DESC";
    $result = mysql_query($query);
    
   
    while ($row = mysql_fetch_array($result)) {
        $output .= '<tr>';
        $output .= '<td>' . $row['id'] . '</td>';
        $output .= '<td>' . htmlspecialchars($row['tytul']) . '</td>';
        $output .= '<td>';
        $output .= '<a href="edit.php?id=' . $row['id'] . '">Edytuj</a> | ';
        $output .= '<a href="delete.php?id=' . $row['id'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć tę podstronę?\')">Usuń</a>';
        $output .= '</td>';
        $output .= '</tr>';
    }
    
    $output .= '</table>';
    $output .= '</div>';
    
    return $output;
}

function EdytujPodstrone($id) {
   
    
    $query = "SELECT tytul, tresc, aktywna FROM page_list WHERE id = " . intval($id);
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);

   
    $currentTitle = htmlspecialchars($row['tytul']);
    $currentContent = htmlspecialchars($row['tresc']);
    $isActive = $row['aktywna'] ? 'checked' : '';

    
    $output = '<div class="edit-subpage-form">';
    $output .= '<h1>Edytuj Podstronę</h1>';
    $output .= '<form method="post" action="update_subpage.php">';


    $output .= '<input type="hidden" name="id" value="' . intval($id) . '">';

   
    $output .= '<label for="tytul">Tytuł Podstrony:</label><br>';
    $output .= '<input type="text" name="tytul" id="tytul" value="' . $currentTitle . '" required><br><br>';

  
    $output .= '<label for="tresc">Treść Podstrony:</label><br>';
    $output .= '<textarea name="tresc" id="tresc" rows="10" cols="50" required>' . $currentContent . '</textarea><br><br>';

   
    $output .= '<label for="aktywna">Aktywna:</label>';
    $output .= '<input type="checkbox" name="aktywna" id="aktywna" value="1" ' . $isActive . '><br><br>';

  
    $output .= '<input type="submit" name="submit" value="Zapisz zmiany">';
    $output .= '</form>';
    $output .= '</div>';

    return $output;
}

function DodajNowaPodstrone() {
    
    $output = '<div class="add-subpage-form">';
    $output .= '<h1>Dodaj Nową Podstronę</h1>';
    $output .= '<form method="post" action="add_subpage.php">';

  
    $output .= '<label for="tytul">Tytuł Podstrony:</label><br>';
    $output .= '<input type="text" name="tytul" id="tytul" required><br><br>';

  
    $output .= '<label for="tresc">Treść Podstrony:</label><br>';
    $output .= '<textarea name="tresc" id="tresc" rows="10" cols="50" required></textarea><br><br>';

   
    $output .= '<label for="aktywna">Aktywna:</label>';
    $output .= '<input type="checkbox" name="aktywna" id="aktywna" value="1"><br><br>';

    
    $output .= '<input type="submit" name="submit" value="Dodaj Podstronę">';
    $output .= '</form>';
    $output .= '</div>';

    return $output;
}

function UsunPodstrone($id) {
    
    $id = intval($id);
    
   
    $query = "DELETE FROM page_list WHERE id = $id";
    
   
    mysql_query($query);
    
   
    header("Location: admin.php");
    exit;
?>
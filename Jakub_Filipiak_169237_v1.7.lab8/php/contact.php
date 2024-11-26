<?php
class Contact {
    // Metoda wyświetlająca formularz kontaktowy
    public function PokazKontakt() {
        echo "<h1>Kontakt</h1>";
        echo "<form method='post' action=''>
                <label for='email'>Twój e-mail:</label><br>
                <input type='email' id='email' name='email' required><br><br>
                
                <label for='subject'>Temat:</label><br>
                <input type='text' id='subject' name='subject' required><br><br>
                
                <label for='message'>Wiadomość:</label><br>
                <textarea id='message' name='message' rows='5' required></textarea><br><br>
                
                <button type='submit' name='sendMessage'>Wyślij wiadomość</button>
              </form>";

        // Obsługa formularza
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sendMessage'])) {
            $this->WyslijMailKontakt();
        }
    }

   public function WyslijMailKontakt() {
    // Walidacja danych z formularza
    if (empty($_POST['subject']) || empty($_POST['message']) || empty($_POST['email'])) {
        echo "Wszystkie pola są wymagane!";
        $this->PokazKontakt(); // Ponowne wyświetlenie formularza
        return;
    }

    // Dane maila
    $mail = [
        'subject' => $_POST['subject'],
        'body' => $_POST['message'],
        'sender' => $_POST['email'], // E-mail podany przez użytkownika
        'recipient' => "jakubfilipiak02@op.pl" // Adres odbiorcy
    ];

    // Walidacja adresu e-mail nadawcy
    if (!filter_var($mail['sender'], FILTER_VALIDATE_EMAIL)) {
        echo "Podano nieprawidłowy adres e-mail!";
        $this->PokazKontakt(); // Ponowne wyświetlenie formularza
        return;
    }

    // Ustawienia nagłówków
    $header = "From: no-reply@yourdomain.com\r\n"; // Statyczny poprawny e-mail
    $header .= "Reply-To: " . $mail['sender'] . "\r\n";
    $header .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Wysyłanie maila
    if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
        echo "Wiadomość została wysłana pomyślnie!";
    } else {
        echo "Wystąpił problem z wysłaniem wiadomości.";
    }
}
}

// Tworzenie instancji klasy Contact
$kontakt = new Contact();

// Wyświetlenie formularza kontaktowego
$kontakt->PokazKontakt();
?>

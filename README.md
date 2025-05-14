# Biletowo

**Biletowo** to strona internetowa napisana w PHP, która umożliwia tworzenie wydarzeń (np. koncertów) oraz rezerwację miejsc poprzez zakup biletów. Po dokonaniu rezerwacji użytkownik otrzymuje bilet w formacie PDF na adres e-mail. Projekt został stworzony do celów edukacyjnych.

## ✨ Funkcje

- Tworzenie wydarzeń z przypisanymi miejscami
- Rezerwacja biletów (bez obsługi płatności)
- Wysyłanie biletu PDF na e-mail
- Panel administratora (niedokończony)

## 🛠️ Wymagania

- PHP 8.2.12 lub serwer apache (np. XAMPP)
- Composer
- MySQL (np. poprzez XAMPP)
- Serwer SMTP (lokalny np. Mercury Mail w XAMPP)

## 🚀 Instalacja i uruchomienie

1. **Sklonuj repozytorium:**
   ```bash
   git clone https://github.com/PieselKlif/biletowo.git
   cd biletowo
    ```

2. **Zainstaluj zależności przy pomocy Composera:**

   ```bash
   composer install
   ```

3. **Utwórz bazę danych:**

   * Otwórz phpMyAdmin lub terminal MySQL
   * Stwórz nową bazę danych, np. `biletowo`
   * Zaimportuj plik `biletowo.sql` (powinien znajdować się w repozytorium)

4. **Skonfiguruj połączenie z bazą danych:**

   * Edytuj plik `config/database-conf.php` i ustaw swoje dane dostępowe do MySQL:

     ```php
     public static $host = 'localhost';
     public static $db = 'biletowo';
     public static $user = 'root';
     public static $password = '';
     ```

5. **Uruchom projekt:**

   ```bash
   php -S localhost:3000
   ```

   Otwórz przeglądarkę i przejdź pod adres [http://localhost:3000](http://localhost:3000)

   Można też użyć serwera apache np. XAMPP przenosząc pliki do folderu `htdocs`.
   
> [!IMPORTANT]
> W przypadku serwera apache pliki projektu muszą się znajdować w głównym folderze.

## 📧 Konfiguracja wysyłania maili

Aby wysyłka e-maili działała lokalnie, należy:

1. Zainstalować **XAMPP** i uruchomić moduł **Mercury Mail**.
2. Skonfigurować konto użytkownika w Mercury (login i hasło).
3. Zalogować klienta (np. thunderbird) do użytkownika w Mercury.
4. W systemie Windows dodać wyjątek zapory dla Mercury, jeśli e-maile nie dochodzą.

## 🔐 Dane logowania

Panel administratora jest dostępny pod `/admin` z tymi danymi logowania

* **Login:** `admin`
* **Hasło:** `admin123`

## 📁 Struktura projektu

Projekt nie korzysta z frameworka ani pliku `.env`. Wszystkie pliki znajdują się w katalogu głównym.

## 👨‍💻 Autor

Projekt stworzony przez **PieselKlif**.

## 📌 Informacje dodatkowe

* Projekt służy wyłącznie do użytku lokalnego.
* Nie obsługuje płatności online.
* Nie przeznaczony do produkcyjnego wdrożenia bez dodatkowych zabezpieczeń i konfiguracji.

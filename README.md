# SnapForm

SnapForm is a local-network form builder built with Laravel 12, PHP 8.2+, and MySQL. Form owners can create and edit forms, share links with devices on the same network, collect email-verified responses, archive forms, and export results to CSV.

## Requirements

- PHP 8.2 or later with the extensions required by Laravel
- Composer
- Node.js and npm
- XAMPP MySQL

## Local setup

1. Start MySQL from the XAMPP Control Panel.
2. Create a MySQL database named `snapform`.
3. Install PHP and JavaScript dependencies:

   ```powershell
   composer install
   npm install
   ```

4. Create the local environment file and application key:

   ```powershell
   if (!(Test-Path .env)) { Copy-Item .env.example .env }
   php artisan key:generate
   ```

   Keep any existing `.env` file and update its values instead of overwriting it.

5. Configure `.env` for the MySQL instance you are actually running. XAMPP commonly uses `root` with an empty password by default, but use your own credentials if they differ:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=snapform
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   If another MySQL server is already using port 3306, make sure XAMPP is connected to the intended server and set `DB_PORT` to that instance’s port.
6. Configure SMTP in `.env` if respondents must receive verification emails in real inboxes. Change `MAIL_MAILER` to `smtp` and enter the SMTP settings provided by your email provider. Keep credentials in `.env`, which must not be committed. With the default `log` mailer, verification links are written to Laravel’s log instead of being delivered to an inbox.
7. Apply database migrations:

   ```powershell
   php artisan migrate
   ```

8. Build frontend assets:

   ```powershell
   npm run build
   ```

9. Start the app so other devices on the local network can reach it:

   ```powershell
   php artisan serve --host=0.0.0.0 --port=8000
   ```

10. On the host computer, open `http://127.0.0.1:8000`. On another device connected to the same Wi-Fi, open `http://<host-computer-local-IP>:8000` (for example, `http://192.168.1.25:8000`).

The host computer must stay on and connected to the network. Allow port 8000 through the host computer’s firewall for private networks if needed. Do not configure router port forwarding or expose Laravel’s development server to the public internet.

When using SnapForm from another device, set `APP_URL` in `.env` to the host computer’s local IP and port so generated email verification links use an address respondents can reach. Update it if the host IP changes.

## Features

- Owner sign-up, sign-in, and password reset
- Form questions: short text, long text, dropdown, single choice, and multiple choice
- Question edits preserve submitted question text and options in answer snapshots
- Each response is pending until the respondent verifies their email using a single-use link
- A verified email address may submit multiple responses
- Forms can be archived without deleting their response history
- Owners can review verified responses and download CSV exports

## Development and checks

Run the test suite with:

```powershell
php artisan test
```

The default environment sends mail to Laravel’s log driver. Configure SMTP in `.env` for actual email delivery.
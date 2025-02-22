### 📌 Final Updated Project Files

#### ✅ **1. `.env` (Environment Configuration)**
```ini
# Database Credentials
DB_HOST="localhost"
DB_NAME="your_database"
DB_USER="your_db_user"
DB_PASS="your_db_password"

# Gmail OAuth 2.0 Credentials
GMAIL_CLIENT_ID="your-client-id.apps.googleusercontent.com"
GMAIL_CLIENT_SECRET="your-client-secret"
GMAIL_REFRESH_TOKEN="your-refresh-token"
GMAIL_EMAIL="your-email@gmail.com"
```

---

#### ✅ **2. `init.php` (Initialize Configuration and Authentication)**
```php
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Config;
use Delight\Auth\Auth;

// Load environment variables
Config::load();

// Database Connection
try {
    $db = new PDO(
        'mysql:host=' . Config::get('DB_HOST') . ';dbname=' . Config::get('DB_NAME') . ';charset=utf8mb4', 
        Config::get('DB_USER'), 
        Config::get('DB_PASS')
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize Authentication
$auth = new Auth($db);
```

---

#### ✅ **3. `Config.php` (Load Environment Variables)**
```php
namespace App\Core;

use Dotenv\Dotenv;

class Config {
    public static function load() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Adjust path as needed
        $dotenv->load();
    }

    public static function get($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}
```

---

#### ✅ **4. `Mail.php` (Send Emails via Gmail XOAUTH2)**
```php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;

class Mail {
    public static function sendEmail($to, $subject, $body) {
        $mail = new PHPMailer(true);

        try {
            // Load environment variables
            Config::load();
            $clientId = Config::get('GMAIL_CLIENT_ID');
            $clientSecret = Config::get('GMAIL_CLIENT_SECRET');
            $refreshToken = Config::get('GMAIL_REFRESH_TOKEN');
            $email = Config::get('GMAIL_EMAIL');

            // Configure PHPMailer
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // OAuth 2.0 Authentication
            $provider = new Google([
                'clientId'     => $clientId,
                'clientSecret' => $clientSecret,
            ]);

            $mail->AuthType = 'XOAUTH2';
            $mail->setOAuth(new \PHPMailer\PHPMailer\OAuth([
                'provider' => $provider,
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
                'refreshToken' => $refreshToken,
                'userName' => $email
            ]));

            // Email Details
            $mail->setFrom($email, 'Your App Name');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Send Email
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
```

---

#### ✅ **5. `public/register.php` (User Registration)**
```php
require '../init.php';

try {
    $userId = $auth->register(
        $_POST['email'],
        $_POST['password'],
        $_POST['username'],
        function ($selector, $token) {
            $verificationUrl = "http://yourdomain.com/verify.php?selector=$selector&token=$token";
            Mail::sendEmail($_POST['email'], "Verify Your Account", "Click here: $verificationUrl");
        }
    );
    echo "User registered successfully. Please check your email for verification.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

---

#### ✅ **6. `public/login.php` (User Login)**
```php
require '../init.php';

try {
    $auth->login($_POST['email'], $_POST['password']);
    echo "Login successful.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

---

#### ✅ **7. `public/logout.php` (User Logout)**
```php
require '../init.php';
$auth->logOut();
echo "Logged out successfully.";
```

---

#### ✅ **8. `public/verify.php` (Email Verification)**
```php
require '../init.php';

try {
    $auth->confirmEmail($_GET['selector'], $_GET['token']);
    echo "Email verified successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

---

### 🚀 **Final Testing Steps**
1️⃣ **Register a new user** – Ensure email verification is sent via Gmail XOAUTH2.
2️⃣ **Test login/logout functionality** – Confirm smooth authentication flow.
3️⃣ **Ensure database connection works correctly** – Debug any errors.

---

### 🔥 **Next Steps**
- Would you like to **log errors into a database** instead of `error_log()`?
- Should we implement a **resend verification email feature**?
- Would you like to add a **password reset via email**?

Let me know how you’d like to proceed! 😊

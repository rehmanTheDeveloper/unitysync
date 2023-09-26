<?php

date_default_timezone_set("Asia/Karachi");
$created_date = date("Y-m-d") . " " . date("h:i:sa");
$created_by = (isset($_SESSION['id'])) ? $_SESSION['id'] : "";
$current_month = date('F, Y');
define("LICENSE_PATH", "license/".$created_by."/license.json");

error_reporting(E_ALL);
ini_set('display_errors', 'On');

class DB
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $pdo;

    public function __construct($host, $db, $user, $pass)
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->connect();
    }

    private function connect()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}

function conn($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME)
{
    $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if (!$conn) {
        die("Database Isn't connected");
    }
    return $conn;
}

function isLicenseExpired($registrationDate, $validityMonths)
{
    // Convert the registration date string to a DateTime object
    $registrationDateTime = new DateTime($registrationDate);

    // Calculate the expiration date by adding the validity months to the registration date
    $expirationDate = clone $registrationDateTime;
    $expirationDate->add(new DateInterval('P' . $validityMonths . 'M'));

    // Get the current date as a DateTime object
    $currentDateTime = new DateTime();

    // Compare the current date with the expiration date
    return $currentDateTime > $expirationDate;
}

function licenseExpirationDate($registrationDate, $validity)
{
    // Convert the registration date string to a timestamp
    $registrationTimestamp = strtotime($registrationDate);

    // Get the number of validity months from the $validity variable
    $validityMonths = $validity;

    // Calculate the expiration date by adding the validity months to the registration date
    $expirationTimestamp = strtotime("+$validityMonths months", $registrationTimestamp);

    // Convert the expiration timestamp back to a date string
    $expirationDate = date('Y-m-d h:ia', $expirationTimestamp);
    return $expirationDate;
}

function encryptor($action, $string) {
    $output = FALSE;
    $method = "AES-256-CBC";
    $secret_key = '';
    $secret_iv = '';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv),0 ,16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $method, $key, 0, $iv);
        $output = base64_encode($output);
    } elseif ('decrypt') {
        $output = openssl_decrypt(base64_decode($string), $method, $key, 0, $iv);
    }
    return $output;
}
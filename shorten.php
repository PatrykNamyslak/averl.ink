<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/path/to/db-connection.php';

$dbh = CreateDatabaseConnection();




// Function to check if a URL exists in the blacklist table
function isBlacklisted($url, $dbh) {
    $query = $dbh->prepare('SELECT COUNT(*) FROM blacklist WHERE url = ?');
    $query->execute([$url]);
    $count = $query->fetchColumn();
    return $count > 0;
}

// Function to generate a truly random short code
function generateRandomShortCode() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 6;
    $randomCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomCode;
}

// Check if the user has submitted a URL to shorten
if (isset($_POST['url_to_shorten'])) {
    $originalURL = $_POST['url_to_shorten'];

    // Check if the URL is blacklisted
    if (isBlacklisted($originalURL, $dbh)) {
        echo "Sorry, the provided URL is blacklisted.";
    } else {
        // Check if the URL is valid
        if (!filter_var($originalURL, FILTER_VALIDATE_URL)) {
            echo "Sorry, the provided URL is not valid.";
            exit;
        }

        // Check if the URL contains any blacklisted words
        $query = $dbh->prepare('SELECT * FROM blacklistedwords');
        $query->execute();
        $blacklistedWords = $query->fetchAll();

        foreach ($blacklistedWords as $blacklistedWord) {
            if (stristr($originalURL, $blacklistedWord['word'])) {
                echo "Sorry, the provided URL contains a blacklisted word.";
                exit;
            }
        }

        // Generate a random short code for the URL
        $shortCode = generateRandomShortCode();

        // Check if the short code already exists
        $query = $dbh->prepare('SELECT COUNT(*) FROM shortened_urls WHERE short_code = ?');
        $query->execute([$shortCode]);
        $count = $query->fetchColumn();
        if ($count > 0) {
            $shortCode = generateRandomShortCode();
        }

        // Create the directory for the shortened URLs if it does not exist
        $directory = $_SERVER['DOCUMENT_ROOT'] . "/path/to/shortened-links/" . $shortCode;
// used to create a directory
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Store the shortened URL and related data in the database
        $query = $dbh->prepare('INSERT INTO shortened_urls (original_url, short_code, created_at) VALUES (?, ?, NOW())');
        $query->execute([$originalURL, $shortCode]);

        // Create the index.php file
        $file = $directory . '/' . 'index.php';
    
        file_put_contents($file, '<script>
            window.location.href = "' . htmlspecialchars($originalURL) . '";
        </script>');

        // Display the shortened URL to the user
        $shortenedURL = "https://your_website.com" . $shortCode;
        echo "Your shortened URL: <input type='text' value='$shortenedURL' readonly>";
    }
}

// Close the database connection
$dbh = null;
?>
               ©2023 Patryk Namyslak
               
               
               Author: Patryk Namyslak
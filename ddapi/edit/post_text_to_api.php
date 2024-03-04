
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["text_data"])) {
    // Get the text data from the form
    $textData = $_POST["text_data"];

    // Your API endpoint where you want to post the text data
    $apiEndpoint = "http://localhost:8000/ddapi";

    // API key
    $apiKey = 'your_api_key1';

    // Initialize cURL session
    $curl = curl_init($apiEndpoint);

    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array("text_data" => $textData)));

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        echo "Error: " . curl_error($curl);
    } else {
        echo "Data successfully posted to API!";
    }

    // Close cURL session
    curl_close($curl);
} else {
    echo "Invalid request!";
}
?>

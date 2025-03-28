<?php
if (isset($_GET['whatsapp_number'])) {
    $whatsapp_number = $_GET['whatsapp_number'];

    $api_url = "https://whatsapp-number-validator3.p.rapidapi.com/wsp/check?number=" . urlencode($whatsapp_number);
    $api_key = "4e6ec01c1dmsh6da95efda041dd6p1c0d4bjsn37da538dd5ad"; // Replace with your actual API key

    $headers = [
        "X-RapidAPI-Host: whatsapp-number-validator3.p.rapidapi.com",
        "X-RapidAPI-Key: $api_key"
    ];

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response
    $result = json_decode($response, true);

    if (isset($result['valid']) && $result['valid'] === true) {
        echo "valid";
    } else {
        echo "invalid";
    }
}
?>
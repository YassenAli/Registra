<?php
class WhatsAppValidator {
    private $apiKey = '4135c8f314msh463be3366482269p1a7ab5jsn41201ed42f04';
    private $apiHost = 'whatsapp-number-validator3.p.rapidapi.com';

    public function validateNumber($phone) {
        $url = 'https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken';
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'phone_number' => $phone
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-rapidapi-host: " . $this->apiHost,
                "x-rapidapi-key: " . $this->apiKey
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return ['valid' => false, 'message' => 'API connection failed'];
        }

        $data = json_decode($response, true);
        
        if (isset($data['status'])) {
            return [
                'valid' => $data['status'] === 'valid',
                'message' => $data['message'] ?? ($data['status'] === 'valid'
                            ? 'Valid WhatsApp number'
                            : 'Invalid WhatsApp number')
            ];
        }
        
        return ['valid' => false, 'message' => 'Validation failed'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'validate_whatsapp') {
    header('Content-Type: application/json');
    
    if (!isset($_POST['number'])) {
        echo json_encode(['valid' => false, 'message' => 'Number required']);
        exit;
    }

    $validator = new WhatsAppValidator();
    $result = $validator->validateNumber($_POST['number']);
    echo json_encode($result);
    exit;
}

// if (isset($_GET['whatsapp_number'])) {
//     $whatsapp_number = $_GET['whatsapp_number'];

//     $api_url = "https://whatsapp-number-validator3.p.rapidapi.com/wsp/check?number=" . urlencode($whatsapp_number);
//     $api_key = "4e6ec01c1dmsh6da95efda041dd6p1c0d4bjsn37da538dd5ad"; // Replace with your actual API key

//     $headers = [
//         "X-RapidAPI-Host: whatsapp-number-validator3.p.rapidapi.com",
//         "X-RapidAPI-Key: $api_key"
//     ];

//     // Initialize cURL session
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $api_url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//     $response = curl_exec($ch);
//     curl_close($ch);

//     // Decode JSON response
//     $result = json_decode($response, true);

//     if (isset($result['valid']) && $result['valid'] === true) {
//         echo "valid";
//     } else {
//         echo "invalid";
//     }
// }
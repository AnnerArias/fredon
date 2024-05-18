<?php
/**
 * @author     Cristian Cisternas.
 * @copyright  2021 Brouter SpA (https://www.brouter.cl)
 * @date       August 2021
 * @license    GNU LGPL
 * @version    1.0.1
 */

// Set error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to make API calls
function call_ws_api($data, $method, $type, $endpoint) {
    // API credentials
    $TbkApiKeyId = '597055555532';
    $TbkApiKeySecret = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';

    // Determine URL based on type
    $url = ($type == 'live') ? "https://webpay3g.transbank.cl" : "https://webpay3gint.transbank.cl";

    // Initialize cURL
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Tbk-Api-Key-Id: ' . $TbkApiKeyId,
            'Tbk-Api-Key-Secret: ' . $TbkApiKeySecret,
            'Content-Type: application/json'
        ),
    ));

    // Execute cURL request
    $response = curl_exec($curl);

    // Close cURL session
    curl_close($curl);

    // Return JSON decoded response
    return json_decode($response);
}

// Function to handle initial transaction
function init_transaction() {
    $buy_order = rand();
    $session_id = rand();
    $amount = 15000;
    $return_url = $baseurl . "?action=getResult";

    $data = json_encode(array(
        "buy_order" => $buy_order,
        "session_id" => $session_id,
        "amount" => $amount,
        "return_url" => $return_url
    ));
    $method = 'POST';
    $type = 'sandbox';
    $endpoint = '/rswebpaytransaction/api/webpay/v1.0/transactions';

    return call_ws_api($data, $method, $type, $endpoint);
}

// Function to handle getting transaction result
function get_transaction_result($token) {
    $data = '';
    $method = 'PUT';
    $type = 'sandbox';
    $endpoint = '/rswebpaytransaction/api/webpay/v1.0/transactions/' . $token;

    return call_ws_api($data, $method, $type, $endpoint);
}

// Function to handle getting transaction status
function get_transaction_status($token) {
    $data = '';
    $method = 'GET';
    $type = 'sandbox';
    $endpoint = '/rswebpaytransaction/api/webpay/v1.0/transactions/' . $token;

    return call_ws_api($data, $method, $type, $endpoint);
}

// Function to handle refunding a transaction
function refund_transaction($token) {
    $amount = 15000;
    $data = json_encode(array(
        "amount" => $amount
    ));
    $method = 'POST';
    $type = 'sandbox';
    $endpoint = '/rswebpaytransaction/api/webpay/v1.0/transactions/' . $token . '/refunds';

    return call_ws_api($data, $method, $type, $endpoint);
}

// Main logic
$action = isset($_GET["action"]) ? $_GET["action"] : 'init';
$message = null;
$baseurl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

switch ($action) {
    case "init":
        $response = init_transaction();
        $message = print_r($response, true);
        $url_tbk = $response->url;
        $token = $response->token;
        $submit = 'Continuar!';
        break;

    case "getResult":
        if (!isset($_POST["token_ws"])) break;
        $token = filter_input(INPUT_POST, 'token_ws');
        $response = get_transaction_result($token);
        $message = print_r($response, true);
        $url_tbk = $baseurl . "?action=getStatus";
        $submit = 'Ver Status!';
        break;

    case "getStatus":
        if (!isset($_POST["token_ws"])) break;
        $token = filter_input(INPUT_POST, 'token_ws');
        $response = get_transaction_status($token);
        $message = print_r($response, true);
        $url_tbk = $baseurl . "?action=refund";
        $submit = 'Refund!';
        break;

    case "refund":
        if (!isset($_POST["token_ws"])) break;
        $token = filter_input(INPUT_POST, 'token_ws');
        $response = refund_transaction($token);
        $message = print_r($response, true);
        $url_tbk = $baseurl;
        $submit = 'Crear nueva!';
        break;
}
?>
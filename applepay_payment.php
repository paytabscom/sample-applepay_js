<?php

require_once 'env.php';
require_once('./paytabs_core.php');

//

$payment = file_get_contents('php://input');

$payment_token = json_decode($payment, true);


$pt_holder = new PaytabsApplePayHolder();
$pt_holder
    ->set01PaymentCode('applepay')
    ->set02Transaction(PaytabsEnum::TRAN_TYPE_SALE)
    ->set03Cart('applepay_01', $ap_currency, $ap_amount, 'ApplePay Sample')
    ->set04CustomerDetails('Test ApplePay', 'wajih@mail.com', '0555555555', 'plugins applepay', 'Dubai', 'Dubai', 'AE', null, $_SERVER['REMOTE_ADDR'])
    ->set07URLs(null, null)
    ->set50ApplePay($payment_token)
    ->set99PluginInfo('PHP Pure', '1.0.0');

$pt_body = $pt_holder->pt_build();

PaytabsHelper::log(json_encode($pt_body), 1);

$endpoint = $env['endpoint'];
$profile_id = $env['profile_id'];
$server_key = $env['server_key'];
$pt_api = PaytabsApi::getInstance($endpoint, $profile_id, $server_key);

$result = $pt_api->create_pay_page($pt_body);

PaytabsHelper::log(json_encode($result), 1);

echo json_encode([
    "success" => $result->success,
    "result" => $result,
]);

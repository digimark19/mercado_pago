<?php
    require_once 'vendor/autoload.php';

   // MercadoPago\SDK::setAccessToken('TEST-6196813918475187-062612-3841e0096245caf473519eb7ff6674f0-252204241');
    MercadoPago\SDK::setAccessToken('TEST-7756684308165475-071912-f10fa977f33f99dffbc360b0926c9438-1163486988');

    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = (float)$_POST['transactionAmount'];
    $payment->token = $_POST['token'];
    $payment->description = $_POST['description'];
    $payment->installments = (int)$_POST['installments'];
    $payment->payment_method_id = $_POST['paymentMethodId'];
    $payment->issuer_id = (int)$_POST['issuer'];

    $payer = new MercadoPago\Payer();
    $payer->email = $_POST['cardholderEmail'];
    $payer->identification = array(
        "number" => $_POST['identificationNumber']
    );
    $payer->first_name = $_POST['cardholderName'];
    $payment->payer = $payer;

    $payment->save();

    $response = array(
        'status' => $payment->status,
        'status_detail' => $payment->status_detail,
        'id' => $payment->id
    );
    echo json_encode($response);
    echo json_encode($_POST);

?>
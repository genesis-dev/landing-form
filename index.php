<?php
header('Content-Type: application/json');
require __DIR__."/LandingForm.php";
$data = array();
try {
    $form = new LandingForm();
    //$origin = $form->getSiteConfig()['origin'];
    //header("Access-Control-Allow-Origin: http://genesis.kz");
    $data['success'] = true;
    if ($form->load() && $form->validate()) {
        $form->send();
        $form->save();
    } else {
        $data->errors = $form->errors;
    }
} catch (Exception $e) {
    $data['success'] = false;
    if (!isset($data['errors']))
        $data['errors'] = [];
    $data['errors'][] = $e->getMessage();
}

echo $_GET['callback'] . '(' . json_encode($data) . ')';


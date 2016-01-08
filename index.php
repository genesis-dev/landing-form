<?php
header('Content-Type: application/json');
require __DIR__."/LandingForm.php";
$data = $_GET;//array();
try {
    $form = new LandingForm();
    //$origin = $form->getSiteConfig()['origin'];
    //header("Access-Control-Allow-Origin: http://genesis.kz");
    $data['success'] = true;
    if ($form->load() && $form->validate()) {
        $form->send();
        $form->save();
    }
} catch (Exception $e) {
    $data['success'] = false;
    $data['errors'] = [$e->getMessage()];
}

echo $_GET['callback'] . '(' . json_encode($data) . ')';


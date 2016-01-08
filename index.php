<?php
header('Content-Type: application/json');
require __DIR__."/LandingForm.php";
$data = array("success"=>false);
try {
    $form = new LandingForm();
    //$origin = $form->getSiteConfig()['origin'];
    //header("Access-Control-Allow-Origin: http://genesis.kz");
    if ($form->load() && $form->validate()) {
        $data['valid'] = true;
        if($form->send())
            $data['success'] = true;
        $form->save();
    } else {
        $data->errors = $form->errors;
    }
} catch (Exception $e) {
    if (!isset($data['errors']))
        $data['errors'] = [];
    $data['errors'][] = $e->getMessage();
}

echo $_GET['callback'] . '(' . json_encode($data) . ')';


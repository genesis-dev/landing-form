<?php
header('Content-Type: application/javascript');
define('DEBUG', true);
require __DIR__."/LandingForm.php";
$data = array("success"=>false);
try {
    $form = new LandingForm();
    $form->load();
    if ($form->validate()) {
        if($form->send())
            $data['success'] = true;
        $form->save();
        $telegram = $form->sendTelegram();
        if (DEBUG) {
            $data['telegram'] = $telegram;

        }
    }
    $data["errors"] = array_merge($data["errors"], $form->errors);
} catch (Exception $e) {
    if (!isset($data['errors']))
        $data['errors'] = [];
    $data['errors'][] = $e->getMessage();
}

echo $_GET['callback'] . '(' . json_encode($data) . ')';

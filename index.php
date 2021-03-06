<?php
header('Content-Type: application/javascript');
define('DEBUG', true);
require __DIR__."/LandingForm.php";
$data = ["success"=>false, "errors" => []];
try {
    $form = new LandingForm();
    $form->load();
    if ($form->validate()) {
        $email = $form->send();
        $database = $form->save();
        $telegram = $form->sendTelegram();
        $data['success'] = true;
        if (DEBUG) {
            $data['telegram'] = $telegram;
            $data['email'] = $email;
            $data['database'] = $database;
        }
    }
    $data["errors"] = array_merge($data["errors"], $form->getErrors());
} catch (Exception $e) {
    $data['errors'][] = $e->getMessage();
}

echo $_GET['callback'] . '(' . json_encode($data) . ')';

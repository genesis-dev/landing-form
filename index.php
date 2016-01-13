<?php
header('Content-Type: application/javascript');
require __DIR__."/LandingForm.php";
$data = array("success"=>false);
try {
    $form = new LandingForm();
    $form->load();
    //$data['updates']=$form->sendTelegram();
    if ($form->validate()) {
        if($form->send())
            $data['success'] = true;
        $form->save();
    } else {
        $data["errors"] = $form->errors;
    }
} catch (Exception $e) {
    if (!isset($data['errors']))
        $data['errors'] = [];
    $data['errors'][] = $e->getMessage();
}

echo $_GET['callback'] . '(' . json_encode($data) . ')';

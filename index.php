<?php
header('Content-Type: application/json');
require __DIR__."/LandingForm.php";
$data = array();
try {
    $form = new LandingForm();
    $data['success'] = true;
    if ($form->load() && $form->validate()) {
        $form->send();
        $form->save();
    }
} catch (Exception $e) {
    $data['success'] = false;
    $data['errors'] = [$e->getMessage()];
}

echo json_encode($data);


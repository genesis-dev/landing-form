<?php
require __DIR__.'/lib/PHPMailerAutoload.php';
require __DIR__.'/lib/medoo.php';

class LandingForm {
    private $formName = "landing-form";
    private $fields;
    private $database;
    private $config;
    private $siteConfig;
    private $siteID;

    /**
     * @throws Exception
     */
    public function __construct() {
        $this->config = (include __DIR__ . "/config/main.php");
        var_dump($_POST);
        var_dump($this->config);
        $this->database = new Medoo($this->config['db']);
        if (!isset($_POST['site-id']) || !isset($this->config['sites'][$_POST['site-id']]))
            throw new Exception('Unknown resource.');
        $this->siteID = $_POST['site-id'];
        $this->siteConfig = array_merge_recursive($this->config['defaults'], $this->config['sites'][$this->siteID]);
        $this->fiedls = [];
    }

    /**
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */
    public function send() {
        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = $this->siteConfig['mailer']['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->siteConfig['mailer']['username'];
        $mail->Password = $this->siteConfig['mailer']['password'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->From = $this->siteConfig['mailer']['from'];
        $mail->FromName = $this->siteConfig['mailer']['fromName'];
        foreach ($this->siteConfig['mailer']['to'] as $email) {
            $mail->addAddress($email);
        }
        $mail->Subject = $this->siteConfig['mailer']['subject'];
        ob_start();
        include __DIR__."/views/mailBody.php";
        $mail->Body = ob_get_contents();
        $mail->isHTML(true);
        ob_clean();
        include __DIR__."/views/mailAltBody.php";
        $mail->AltBody = ob_get_contents();
        ob_end_flush();
        return $mail->send();
    }

    /**
     * @return number
     */
    public function save() {
        return $this->database->insert($this->config->table, ["data" => $this->fiedls]);
    }

    /**
     * @return bool
     */
    public function validate() {
        foreach($this->fiedls as $key=>$field) {
            if (is_callable($this->siteConfig['validators']['key']) && !$this->siteConfig['validators']['key']($field['value'], $this->fields, $this->siteConfig['validators']))
                return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function load() {
        if (isset($_POST[$this->formName]) && is_array($_POST[$this->formName])) {
            foreach ($_POST[$this->formName] as $key=>$val) {
                if (!is_array($val))
                    $this->fields[$key] = [
                        "name" => isset($_POST[$this->formName]['field-names'][$key]) ? $_POST[$this->formName]['field-names'][$key]: $key,
                        "value" => $val,
                    ];
            }
            return true;
        }
        return false;
    }
}

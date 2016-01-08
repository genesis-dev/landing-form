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
    public $errors;

    /**
     * @throws Exception
     */
    public function __construct() {
        $this->config = (include __DIR__ . "/config/main.php");
        $this->database = new Medoo($this->config['db']);
        if (!isset($_GET['site-id']) || !isset($this->config['sites'][$_GET['site-id']]))
            throw new Exception('Unknown resource.');
        $this->siteID = $_GET['site-id'];
        $this->siteConfig = array_merge($this->config['defaults'], $this->config['sites'][$this->siteID]);
        $this->fiedls = [];
        $this->errors = [];
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
        /*ob_start();
        include __DIR__."/views/mailBody.php";
        $mail->Body = ob_get_contents();
        $mail->isHTML(true);
        ob_clean();
        include __DIR__."/views/mailAltBody.php";
        $mail->AltBody = ob_get_contents();
        ob_end_flush();*/
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
        $this->errors = [];
        foreach($this->fiedls as $key=>$field) {
            if (is_callable($this->siteConfig['validators'][$key]) && !$this->siteConfig['validators'][$key]($field['value'], $this->fields, $this->siteConfig['validators']))
                $this->errors[] = "Неверно заполнено поле '$field[name]'";
        }
        if (!empty($this->errors))
            return false;
        return true;
    }


    public function load() {
        if (isset($_GET[$this->formName]) && is_array($_GET[$this->formName])) {
            foreach ($_GET[$this->formName] as $key=>$val) {
                if (!is_array($val))
                    $this->fields[$key] = [
                        "name" => isset($_GET[$this->formName]['field-names'][$key]) ? $_GET[$this->formName]['field-names'][$key]: $key,
                        "value" => $val,
                    ];
            }
            //return true;
        }
        //return false;
    }

    /**
     * @return array
     */
    public function getSiteConfig()
    {
        return $this->siteConfig;
    }

    /**
     * @return mixed
     */
    public function getSiteID()
    {
        return $this->siteID;
    }
}

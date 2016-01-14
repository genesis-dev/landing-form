<?php
require __DIR__.'/lib/PHPMailerAutoload.php';
require __DIR__.'/lib/medoo.php';

class LandingForm {
    private $formName = "landingForm";
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
        $this->errors = [];
        if (!isset($_GET['siteID']) || !isset($this->config['sites'][$_GET['siteID']]))
            throw new Exception('Unknown resource.');
        try {
            $this->database = new Medoo($this->config['db']);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            $this->errors["database"] = $this->database;
        }
        $this->siteID = (string)$_GET['siteID'];
        $this->siteConfig = array_replace_recursive($this->config['defaults'], $this->config['sites'][$this->siteID]);
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
        ob_end_clean();
        if ($mail->send())
            return true;
        else
            $this['errors'][] = $mail->ErrorInfo;
        return false;
    }

    /**
     * @return mixed
     */
    public function sendTelegram() {
        if (isset($this->config["telegram"]["api_key"]) && isset($this->siteConfig["telegram"]["channel_name"])) {
            $api_key = $this->config["telegram"]["api_key"];
            ob_start();
            include __DIR__ . "/views/telegram.php";
            $text = urlencode(ob_get_contents());
            ob_end_clean();
            $channel = $this->siteConfig["telegram"]["channel_name"];
            $url = "https://api.telegram.org/bot$api_key/sendMessage?text=$text&chat_id=@$channel&parse_mode=Markdown";
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($handle);
            curl_close($handle);
            if ($response !== false)
                return json_decode($response);
        }

        return false;
    }

    /**
     * @return number
     */
    public function save() {
        //return $this->database->insert($this->config["table"], ["data" => $this->fields]);
    }

    /**
     * @return bool
     */
    public function validate() {
        $this->errors = [];
        foreach($this->fields as $key=>$field) {
            if (isset($this->siteConfig['validators'][$key])) {
                $validator = $this->siteConfig['validators'][$key];
                if (is_callable($validator) && !$validator($field['value'], null, null))
                    $this->errors[] = $key;
            }
        }
        if (!empty($this->errors))
            return false;
        return true;
    }

    /**
     * @return bool
     */
    public function load() {
        if (isset($_GET[$this->formName]) && is_array($_GET[$this->formName])) {
            foreach ($_GET[$this->formName] as $key=>$val) {
                if (!is_array($val))
                    $this->fields[$key] = [
                        "name" => isset($_GET[$this->formName]['fieldNames'][$key]) ? $_GET[$this->formName]['fieldNames'][$key]: $key,
                        "value" => $val,
                    ];
            }
        }

        if (!empty($this->fields))
            return true;
        return false;
    }

    /**
     * @return array
     */
    public function getSiteConfig()
    {
        return $this->siteConfig;
    }

    /**
     * @return string
     */
    public function getSiteID()
    {
        return $this->siteID;
    }
}

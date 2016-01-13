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
        $this->database = new Medoo($this->config['db']);
        if (!isset($_GET['siteID']) || !isset($this->config['sites'][$_GET['siteID']]))
            throw new Exception('Unknown resource.');
        $this->siteID = (string)$_GET['siteID'];
        $this->siteConfig = array_replace_recursive($this->config['defaults'], $this->config['sites'][$this->siteID]);
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
        $mail->Body = "Проверка";
        ob_start();
        include __DIR__."/views/mailBody.php";
        $mail->Body = ob_get_contents();
        $mail->isHTML(true);
        ob_clean();
        include __DIR__."/views/mailAltBody.php";
        $mail->AltBody = ob_get_contents();
        ob_end_clean();
        return $mail->send();
    }

    public function sendTelegram() {
        $api_key = $this->config["telegram"]["api_key"];
        $url = "https://api.telegram.org/bot$api_key/getUpdates";
        return "sf";
    }

    private function exec_curl_request($handle) {
        $response = curl_exec($handle);

        if ($response === false) {
            $errno = curl_errno($handle);
            $error = curl_error($handle);
            error_log("Curl returned error $errno: $error\n");
            curl_close($handle);
            return false;
        }

        $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
        curl_close($handle);

        if ($http_code >= 500) {
            // do not wat to DDOS server if something goes wrong
            sleep(10);
            return false;
        } else if ($http_code != 200) {
            $response = json_decode($response, true);
            error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
            if ($http_code == 401) {
                throw new Exception('Invalid access token provided');
            }
            return false;
        } else {
            $response = json_decode($response, true);
            if (isset($response['description'])) {
                error_log("Request was successfull: {$response['description']}\n");
            }
            $response = $response['result'];
        }

        return $response;
    }

    /**
     * @return number
     */
    public function save() {
        return $this->database->insert($this->config["table"], ["data" => $this->fields]);
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

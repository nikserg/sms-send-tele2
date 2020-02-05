<?php

namespace nikserg\SmsSendTele2;

/**
 * Class Tele2SmsSender
 *
 * @package Antar\SmsSendTele2
 */
class Tele2SmsSender
{
    /**
     *  API URL
     */
    const SERVICE_URL = "https://newbsms.tele2.ru/api/";

    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $shortCode;
    /**
     * @var string
     */
    private $requestUrl;
    /**
     * @var
     */
    private $error;

    /**
     * Tele2SmsSender constructor.
     *
     * @param        $login
     * @param        $password
     * @param string $shortCode
     */
    public function __construct($login, $password, $shortCode = "Sms Sender")
    {
        $this->login = trim($login);
        $this->password = trim($password);
        $this->shortCode = trim($shortCode);

        $this->requestUrl = self::SERVICE_URL . "?" .
            "login={$this->login}&" .
            "password={$this->password}&" .
            "shortcode={$this->shortCode}&";
    }

    /**
     * @param $phone
     * @param $message
     * @return bool|string
     * @throws \Exception
     */
    public function send($phone, $message)
    {
        $this->requestUrl .= "operation=send&msisdn=" . trim($phone) . "&text=" . urlencode(trim($message));

        return $this->request();
    }

    /**
     * @return mixed
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    protected function request()
    {
        if (!$curl = curl_init()) {
            throw new \Exception(500, 'Unable to initialize CURL');
        }

        curl_setopt($curl, CURLOPT_URL, $this->requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36');
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response && preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/',
                mb_strtoupper($response))) {
            return $response;
        }

        $this->error = $response;

        return false;
    }
}

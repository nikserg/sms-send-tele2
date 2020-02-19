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
    const SERVICE_URL = "http://kannel.itcomgk.ru/smsworker.php"; //http://kannel.itcomgk.ru/smsworker.php?source=crm&to=79XXXXXXXXX&text=ТЕКСТДЛЯОТПРАКИ

    /**
     * @var
     */
    private $error;

    /**
     * @var string
     */
    private $source;

    /**
     * Tele2SmsSender constructor.
     *
     * @param string $source
     */
    public function __construct($source = 'crm')
    {
        $this->source = $source;
    }

    /**
     * @param $phone
     * @param $message
     * @return bool|string
     * @throws \Exception
     */
    public function send($phone, $message)
    {
        $requestUrl = self::SERVICE_URL. "?source=".$this->source."&to=" . trim($phone) . "&text=" . urlencode(trim($message));

        if (!$curl = curl_init()) {
            throw new \Exception(500, 'Unable to initialize CURL');
        }

        curl_setopt($curl, CURLOPT_URL, $requestUrl);
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

    /**
     * @return mixed
     */
    public function error()
    {
        return $this->error;
    }

}

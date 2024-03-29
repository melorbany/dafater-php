<?php

define("CLIENT_ID", "mybusiness-z6n9ob");
define("CLIENT_SECRET", "GDv55ZBnnt");
define("AUTHENTICATION", "https://identity.dafater.biz/connect/token");

/**
 * Class Dafater
 */
Class Dafater
{

    /**
     * @var
     */
    protected $access_token;

    /**
     * @var
     */
    protected $token_type;


    /**
     * @return |null
     */
    public function authenticate()
    {

        $obj = json_decode($this->post(AUTHENTICATION, [
            "client_id" => CLIENT_ID,
            "client_secret" => CLIENT_SECRET,
            "grant_type" => "client_credentials",
        ]));

        $this->access_token = isset($obj) && isset($obj->access_token) ? $obj->access_token : null;

        $this->token_type = isset($obj) && isset($obj->token_type) ? $obj->token_type : null;

        return $this->access_token;
    }


    /**
     * @param $document_type
     * @param array $filters
     * @return mixed
     */
    public function getDocument($document_type ,array $params = [])
    {

        $url = "https://api.dafater.biz/document/$document_type";

        $url = str_replace(' ','%20',$url);

        $obj = json_decode($this->get($url,$params, true));
        return $obj;
    }


    /**
     * @param $document_type
     * @param $fields
     * @return mixed
     */
    public function createDocument($document_type, $fields)
    {

        $fields["doctype"] = $document_type;
        $obj = json_decode($this->post("https://api.dafater.biz/document",
            $fields, true, true));

        return $obj;
    }


    /**
     * @param $document_type
     * @param $fields
     * @return mixed
     */
    public function updateDocument($document_type, $fields)
    {

        $fields["doctype"] = $document_type;
        $obj = json_decode($this->put("https://api.dafater.biz/document",
            $fields, true, true));

        return $obj;
    }

    /**
     * @param $url
     * @param $fields
     * @param bool $auth
     * @param bool $json
     * @return mixed
     */
    public function post($url, $fields, $auth = false, $json = false)
    {

        $header = [
            'Accept: application/json',
        ];

        if ($json) {

            $fields_string = json_encode($fields);
            $header[] = 'Content-Type: application/json';
            $header[] = 'Content-Length: ' . strlen($fields_string);

        } else {
            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
        }

        if ($auth && $this->access_token && $this->token_type) {

            $header[] = 'Accept: application/json';
            $header[] = 'Authorization:' . $this->token_type . ' ' . $this->access_token;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 1);

        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }


        $result = curl_exec($ch);

        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }


    /**
     * @param $url
     * @param bool $auth
     * @return bool|string
     */
    public function get($url,$params =[] , $auth = false)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($auth && $this->access_token && $this->token_type) {
            $header = [
                'Accept: application/json',
                'Authorization:' . $this->token_type . ' ' . $this->access_token
            ];

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


    /**
     * @param $url
     * @param $fields
     * @param bool $auth
     * @param bool $json
     * @return mixed
     */
    public function put($url, $fields, $auth = false, $json = false)
    {

        $header = [
            'Accept: application/json',
        ];

        if ($json) {

            $fields_string = json_encode($fields);
            $header[] = 'Content-Type: application/json';
            $header[] = 'Content-Length: ' . strlen($fields_string);

        } else {
            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
        }

        if ($auth && $this->access_token && $this->token_type) {

            $header[] = 'Accept: application/json';
            $header[] = 'Authorization:' . $this->token_type . ' ' . $this->access_token;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 1);

        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }


        $result = curl_exec($ch);

        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

}

?>

<?php

define("CLIENT_ID", "apitest");
define("CLIENT_SECRET", "12345678");
define("AUTHENTICATION", "https://dafateridentity-test.azurewebsites.net/connect/token");


$dafater = new Dafater();
$token = $dafater->authenticate();

$dafater->createCustomer();

$token = $dafater->getDocument("Customer");

echo  $token;

Class Dafater{

	/**
	 * @var
	 */
	protected  $access_token;

	/**
	 * @var
	 */
	protected  $token_type;


	/**
	 * @return |null
	 */
	public function authenticate(){

		$obj = json_decode($this->runPost(AUTHENTICATION, [
			"client_id"=> CLIENT_ID,
			"client_secret"=>  CLIENT_SECRET,
			"grant_type"=>  "client_credentials",
		]));

		$this->access_token = isset($obj) && isset($obj->access_token)? $obj->access_token : null;

		$this->token_type = isset($obj) && isset($obj->token_type)? $obj->token_type : null;

		return $this->access_token;
	}


	public function getDocument($type){

		$obj = json_decode($this->runGet("https://api.dafater.biz/document/$type", true));

		var_dump($obj);
	}


    public function createCustomer(){

        $obj =$this->runPost("https://api.dafater.biz/document", [
        	"doctype" => "Customer",
            "name"=> "Test Account",
			"customer_name" => "Test Account",
            "customer_type"=>  "Individual",
        ],true,true);

        var_dump($obj);
    }

    /**
     * @param $url
     * @param $fields
     * @param bool $auth
     * @param bool $json
     * @return mixed
     */
	public function runPost($url, $fields , $auth = false , $json = false) {



        $header = [
            'Accept: application/json',
        ];

		if($json){

            $fields_string = json_encode($fields);
            $header[] = 'Content-Type: application/json';
            $header[] =  'Content-Length: ' . strlen($fields_string);

        }else{
            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
		}






        if($auth && $this->access_token && $this->token_type){

			$header[] =   'Accept: application/json';
            $header[] = 'Authorization:' .$this->token_type .' '. $this->access_token;
        }


        $ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, 1);

		if(count($header) > 0){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }


		$result = curl_exec($ch);

		if($result === false){
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
	public function runGet($url , $auth = false) {

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

		if($auth && $this->access_token && $this->token_type){
			$header = [
				'Accept: application/json',
				'Authorization:' .$this->token_type .' '. $this->access_token
			];

			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}
}

?>

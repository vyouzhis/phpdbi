<?php
if (!defined('M5CPL'))
exit;

class ajax extends BaseJson{


	public function __construct() {
		parent::__construct(__CLASS__);
	}

	/**
	 * 入口文件
	 * @see PPL/class/BaseTheme::Show()
	 * @param
	 */
	public function Show($arg = NULL) {
		global $router, $match;
		//file_put_contents(Data."/dd", serialize($this->porg));

		$url = "http://".CRMURL."/service/v4_1/rest.php";
		$username = "admin";
		$password = "qazwsx";

		$refered_by = array("1"=>"朋友1","2"=>"朋友2");

		//function to make cURL request


		//login ---------------------------------------------

		$login_parameters = array(
	         "user_auth" => array(
	              "user_name" => $username,
	              "password" => md5($password),
	              "version" => "1"
	              ),
	         "application_name" => "RestTest",
	         "name_value_list" => array(),
	              );

	              $login_result = $this->call("login", $login_parameters, $url);

	              /*
	               echo "<pre>";
	               print_r($login_result);
	               echo "</pre>";
	               */

	              //get session id
	              $session_id = $login_result->id;

	              //create account -------------------------------------
	              $refered_user = "";
	              $aid = $_COOKIE['aid'];
	              if(!empty($aid)){
	              	$refered_user = $refered_by[$aid];	              	             
	              }
	              
	              $set_entry_parameters = array(
	              //session id
			         "session" => $session_id,
		
			              //The name of the module from which to retrieve records.
			         "module_name" => "Leads",
		
			              //Record attributes
			         "name_value_list" => array(
			              //to update a record, you will nee to pass in a record id as commented below
			              //array("name" => "id", "value" => "9b170af9-3080-e22b-fbc1-4fea74def88f"),
			              array("name" => "last_name", "value" =>$this->porg['name']),
			              array("name" => "phone_mobile", "value" =>preg_replace('/-/','',$this->porg['myphone'])),
			              array("name" => "email1", "value" =>$this->porg['email']),
			              array("name" => "account_name", "value" =>$this->porg['company']),
			              array("name" => "description", "value" =>$this->porg['message']),
			              //array("name" => "qq_c", "value" =>$this->porg['qq']),
			              array("name" => "lead_source", "value" =>'网站'),
			              array("name"=>"refered_by", "value"=>$refered_user),  // 推荐人或代理人
			          ),
	              );

	              // file_put_contents(Data."/dd", serialize($set_entry_parameters));

	              $set_entry_result = $this->call("set_entry", $set_entry_parameters, $url);

	              //         echo "<pre>";
	              //         print_r($set_entry_result);
	              //         echo "</pre>";
	              echo json_encode(array("ok"));
	}

	private function call($method, $parameters, $url)
	{
		ob_start();
		$curl_request = curl_init();

		curl_setopt($curl_request, CURLOPT_URL, $url);
		curl_setopt($curl_request, CURLOPT_POST, 1);
		curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl_request, CURLOPT_HEADER, 1);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

		$jsonEncodedData = json_encode($parameters);

		$post = array(
             "method" => $method,
             "input_type" => "JSON",
             "response_type" => "JSON",
             "rest_data" => $jsonEncodedData
		);

		curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($curl_request);
		curl_close($curl_request);

		$result = explode("\r\n\r\n", $result, 2);
		$response = json_decode($result[1]);
		ob_end_flush();

		return $response;
	}


}
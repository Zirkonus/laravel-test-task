<?php

namespace App\Zoho;

use App\Connect;
use Psy\Exception\ErrorException;

class ZohoConnect
{
	protected $email = 'nzzo0510@gmail.com';
	protected $app;
	protected $code;
	protected $token;
	protected $count = 0;

	public function __construct($app)
	{
		$this->app = $app;
		$this->getDate();

		if (!$this->token) {
			$this->getToken();
		}
	}

	public function getUsers()
	{
		$url    = "https://crm.zoho.com/crm/private/json/Contacts/getRecords";
		$param  = 'newFormat=1&authtoken=' . $this->token . '&scope=crmapi&sortColumnString=Account Name&sortOrderString=desc';

		return $this->getJson($this->zohoCurl($url, $param));
	}

	public function insertUser($user)
	{
		$startLine	='<Contacts><row no="1">';
		$firstName	= "<FL val=\"First Name\">$user->first_name</FL>";
		$lastName	= "<FL val=\"Last Name\">$user->last_name</FL>";
		$email		= "<FL val=\"Email\">$user->email</FL>";
		$phone		= "<FL val=\"Phone\">$user->phone</FL>";
		$endLine	="</row></Contacts>";

		$xmlLink    = $startLine . $firstName . $lastName . $email . $phone . $endLine;
		$url        = 'https://crm.zoho.com/crm/private/xml/Contacts/insertRecords';
		$param      = 'authtoken=' . $this->token . '&scope=crmapi&newFormat=1&xmlData='.$xmlLink;

		$this->getJson($this->zohoCurl($url, $param));
	}

	protected function zohoCurl($url, $param)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);

		if (!$result) {
			throw new ErrorException('some problems with connecting');
		}

		return $result;
	}

	protected function getDate()
	{
		if (!$this->app) {
			throw new ErrorException("Wrong app name or it's empty!" );
		}

		$date = Connect::where('app', $this->app)->first();
		if (!$date) {
			throw new ErrorException("Wrong App name");
		}

		$this->code     = $date->code;
		$this->token    = $date->token;

		return true;
	}

	protected function getToken()
	{
		$url 	= "https://accounts.zoho.com/apiauthtoken/nb/create";
		$param 	= 'SCOPE=ZohoCRM/crmapi&EMAIL_ID=' . $this->email . '&PASSWORD=' . $this->code . '&DISPLAY_NAME=' . $this->app;

		$result = $this->zohoCurl($url, $param);

		if (preg_match('/FALSE/', $result)) {
			throw new ErrorException('Wrong Connection');
		}

		if (preg_match('/RESULT=TRUE/', $result)) {
			$pattern = '/AUTHTOKEN=(\w)*/';
			preg_match($pattern, $result, $matches);
			$this->token = substr($matches[0], 10);
			Connect::where('app', $this->app)->update(['token' => $this->token]);
		} else {
			throw new ErrorException('Wrong Connection');
		}
	}

	protected function getJson($result)
	{
		if (preg_match('/Error/', $result)) {
			if ($this->count == 0) {
				$this->count += 1;
				$this->getToken();
			} else {
				return 'Check you settings for getting token';
			}
		}
		if (preg_match('/\?xml/', $result)) {
			if (!preg_match('/success/', $result)) {
				return 'Error for operation';
			}

			return "Operation success!";
		}
		if (!preg_match('/result/', $result)) {
				return "Error for get data";
		}
		$res = json_decode($result, true);
		if (!$res) {
			throw new ErrorException("Wrong unswer!");
		}

		return $res;
	}
}

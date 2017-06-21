<?php

namespace App\Zoho;

use Psy\Exception\ErrorException;

class ZohoConnect
{
	protected $link = "https://accounts.zoho.com/apiauthtoken/nb/create?SCOPE=ZohoCRM/crmapi";
	protected $email;
	protected $app;
	protected $code;
	protected $token;

	public function __construct()
	{
		$this->email	= \Config::get('zoho.email');
		$this->token	= \Config::get('zoho.authtoken');
		$this->app		= \Config::get('zoho.app');
		$this->code		= \Config::get('zoho.auth_code');

		if (!$this->token) {
			$this->token = $this->getToken();
		}
	}

	public function getToken()
	{
		$url 	= "https://accounts.zoho.com/apiauthtoken/nb/create";
		$param 	= 'SCOPE=ZohoCRM/crmapi&EMAIL_ID=' . $this->email . '&PASSWORD=' . $this->code . '&DISPLAY_NAME=' . $this->app;

		dd($url.$param);
		https://accounts.zoho.com/apiauthtoken/nb/createSCOPE=ZohoCRM/crmapi&EMAIL_ID=nzzo@rambler.ru&PASSWORD=aqEuW3r019Y5&DISPLAY_NAME=zohoapi2
		$result = $this->zohoCurl($url, $param);

		if (preg_match('/FALSE/', $result)) {
			throw new ErrorException('Wrong Connection');
		}

		if (preg_match('/RESULT=TRUE/', $result)) {
			$pattern = '/AUTHTOKEN=(\w)*/';
			$res = preg_match($pattern, $result, $matches);
			$str = substr($matches[0], 10);
			\Config::set('zoho.authtoken', $str);
		} else {
			throw new ErrorException('Wrong Connection');
		}

		return $str;
	}

	public function getUsers()
	{
		$url = "https://crm.zoho.com/crm/private/json/Contacts/getRecords";
		$param = 'newFormat=1&authtoken=' . $this->token . '&scope=crmapi&sortColumnString=Account Name&sortOrderString=desc';

		return $this->zohoCurl($url, $param);
	}

	public function insertUser($user)
	{
		$start_line	='<Contacts><row no="1">';
		$first_name	= "<FL val=\"First Name\">$user->first_name</FL>";
		$last_name	= "<FL val=\"Last Name\">$user->last_name</FL>";
		$email		= "<FL val=\"Email\">$user->email</FL>";
		$phone		= "<FL val=\"Phone\">$user->phone</FL>";
		$end_line	="</row></Contacts>";

		$xml_link = $start_line.$first_name.$last_name.$email.$phone.$end_line;

		$url = 'https://crm.zoho.com/crm/private/xml/Contacts/insertRecords';
		$param = 'authtoken=' . $this->token . '&scope=crmapi&newFormat=1&xmlData='.$xml_link;

		return $this->zohoCurl($url, $param);
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
}

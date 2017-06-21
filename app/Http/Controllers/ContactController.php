<?php

namespace App\Http\Controllers;

use App\Contacts;
use App\Zoho\ZohoConnect;

use Illuminate\Http\Request;
use Psy\Exception\ErrorException;

class ContactController extends Controller
{
	public function showContacts($app)
	{
		$connectUsers = $this->getContacts($app);
		$contacts = Contacts::all();

		return view('contacts', [
			'contacts'      => $contacts,
			'connect_users' => $connectUsers,
			'app'           => $app
		]);
	}

	public function addUser()
	{
		return view('form');
	}

	public function addUserTo(Request $request, $app)
	{
		$this->validate($request, [
			'first_name'	=> 'required',
			'last_name' 	=> 'required',
			'email'			=> 'required',
			'phone'			=> 'required'
		]);

		$contact = new Contacts();

		$contact->first_name 	= $request->input('first_name');
		$contact->last_name 	= $request->input('last_name');
		$contact->phone 		= $request->input('phone');
		$contact->email 		= $request->input('email');
		$contact->save();

		$connect    = new ZohoConnect($app);
		$result     = $connect->insertUser($contact);

		if (!$result) {
			throw new ErrorException('User not added');
		}

		return redirect()->route('show_users')->with('Success added');
	}

	public function addUserById($id, $app)
	{
		$user = Contacts::find($id);

		if (!$user) {
			throw new ErrorException('Error');
		}

		$connect    = new ZohoConnect($app);
		$result     = $connect->insertUser($user);

		if (!$result) {
			throw new ErrorException('User not added');
		}

		return redirect()->route('show_users');
	}

	protected function getContacts($app)
	{
		$connect = new ZohoConnect($app);

		if (!$connect) {
			throw new ErrorException('wrong data');
		}

		$users = $connect->getUsers();

		if (!$users) {
			throw new ErrorException('Wrong data');
		}

		$usersFromZoho  = [];
		$u              = $users['response']['result']['Contacts']['row'] ;

		foreach ($u as $user) {
			$userZoho = ['First Name' => '', 'Last Name' => '', 'Email' => '', 'Phone' => ''];

			for ($i = 0; $i < count($user['FL']); $i++) {
				if ($user['FL'][$i]['val'] == 'First Name') {
					$userZoho['First Name'] = $user['FL'][$i]['content'];
				}
				if ($user['FL'][$i]['val'] == 'Last Name') {
					$userZoho['Last Name'] = $user['FL'][$i]['content'];
				}
				if ($user['FL'][$i]['val'] == 'Email') {
					$userZoho['Email'] = $user['FL'][$i]['content'];
				}
				if ($user['FL'][$i]['val'] == 'Phone') {
					$userZoho['Phone'] = $user['FL'][$i]['content'];
				}
			}
			$usersFromZoho[] = $userZoho;
		}

		return $usersFromZoho;
	}
}

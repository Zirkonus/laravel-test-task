<?php

namespace App\Http\Controllers;

use App\Contacts;
use App\Zoho\ZohoConnect;

use Illuminate\Http\Request;
use Psy\Exception\ErrorException;

class ContactController extends Controller
{
    public function showContacts()
    {
        $connect_users = $this->getContacts();
        $contacts = Contacts::all();
        return view('contacts', ['contacts' => $contacts , 'connect_users' => $connect_users]);
    }

    public function getContacts()
    {
        $connect = new ZohoConnect() ;

        if(!$connect){
            throw new ErrorException('wrong data');
        }

        $users = json_decode($connect->getUsers(), true);

        $u = $users['response']['result']['Contacts']['row'] ;

        $users_from_zoho = [];

        foreach ($u as $user){
            $user_from_zoho = ['First Name' => '', 'Last Name' => '', 'Email' => '', 'Phone' => ''];
            for($i= 0; $i<count($user['FL']); $i++){
                if($user['FL'][$i]['val'] == 'First Name'){
                    $user_from_zoho['First Name'] = $user['FL'][$i]['content'];
                };
                if($user['FL'][$i]['val'] == 'Last Name'){
                    $user_from_zoho['Last Name'] = $user['FL'][$i]['content'];
                };
                if($user['FL'][$i]['val'] == 'Email'){
                    $user_from_zoho['Email'] = $user['FL'][$i]['content'];
                };
                if($user['FL'][$i]['val'] == 'Phone'){
                    $user_from_zoho['Phone'] = $user['FL'][$i]['content'];
                };
            }
            $users_from_zoho[] = $user_from_zoho;
        }

       return $users_from_zoho;
    }

    public function addUser()
    {

        return view('form');
    }

    public function addUserTo(Request $request)
    {
        $this->validate($request, [
            'first_name'   =>  'required',
            'last_name' => 'required',
            'email'   =>  'required',
            'phone'  =>  'required',
        ]);

        $contact = new Contacts();

        $contact->first_name = $request->input('first_name');
        $contact->last_name = $request->input('last_name');
        $contact->phone = $request->input('phone');
        $contact->email = $request->input('email');

        $contact->save();

        $connect = new ZohoConnect() ;

        $result = $connect->insertUser($contact);

        if(!$result){
            throw new ErrorException('User not added');
        }

        return redirect()->route('show_users')->with('Success added');
    }

    public function addUserById($id)
    {
        $user = Contacts::find($id);

        if(!$user){
            throw new ErrorException('Error');
        }

        $connect = new ZohoConnect() ;
        $result = $connect->insertUser($user);

        if(!$result){
            throw new ErrorException('User not added');
        }

        return redirect()->route('show_users');
    }
}


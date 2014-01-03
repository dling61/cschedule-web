<?php
class Common{
	//convert UTC Timestamp to local DateTime
	public function timestampToDate($timestamp){
		$localTimezone = +8; // if the local timezone is +8
		$serverTimezone = 0; //if the server timezone is 0
		//display right time in different clients
		return date('Y-m-d H:i:s',$timestamp+($localTimezone-$serverTimezone)*3600);
	}
	
	//convert local DateTime to UTC Time
	public function dateToTimestamp($datetime){
		$localTimezone = +8; // if the local timezone is +8
		$serverTimezone = 0; //if the server timezone is 0
		return strtotime($datetime)-($localTimezone-$serverTimezone)*3600;
	}
	
	// get Gmail contacts
	
	public function getGmailContacts($user,$password){
		error_reporting(E_ALL);

		// step 1: login
		$login_url = "https://www.google.com/accounts/ClientLogin";
		$fields = array(
			'Email' => $user,
			'Passwd' => $password,
			'service' => 'cp', // <== contact list service code
			'source' => 'test-google-contact-grabber',
			'accountType' => 'GOOGLE',
		);
 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$login_url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$fields);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec($curl);

		$returns = array();
 
		foreach (explode("\n",$result) as $line)
		{
			$line = trim($line);
			if (!$line) continue;
			list($k,$v) = explode("=",$line,2);
 
			$returns[$k] = $v;
		}
 
		curl_close($curl);

		// step 2: grab the contact list
		$feed_url = "http://www.google.com/m8/feeds/contacts/$user/full?alt=json&max-results=250";
		if(isset($returns['Error'])){
			// if($returns['Error'] == 'BadAuthentication'){
				echo "{'error':'Email authentication failed, please check your account.'}";
				exit;
			// }
		}
		if(count($returns) == 0) $returns['Auth'] = 0; //if no auth ,set one
		
		$header = array(
			'Authorization: GoogleLogin auth=' . $returns['Auth'],
		);
 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $feed_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
 
		$result = curl_exec($curl);
		curl_close($curl);
 
		$data = json_decode($result);
		$contacts = array();
		$addresslists = array();
		if(isset($data->feed->entry)){
			foreach ($data->feed->entry as $entry)
			{
				$contact = new stdClass();
				if($entry->title->{'$t'} != ""){
					$contact->title = $entry->title->{'$t'};
					$contact->email = $entry->{'gd$email'}[0]->address;
					if(isset($entry->{'gd$phoneNumber'})){
						$contact->mobile = $entry->{'gd$phoneNumber'}[0]->{'$t'};
					}else $contact->mobile = '';
				}
			
				$contacts[] = $contact;
			}
		
			// unset the empty object and push into the addresslists
			if($contacts){
				foreach($contacts as $vals){
					if(!empty($vals->title) && !empty($vals->email)){
						// unset($contacts[$key]);
						array_push($addresslists,$vals);
					}
				}
			}else{
				echo "{'error':'No contacts found.'}";
				exit;
			}
		}else {
			echo "{'error':'No contacts found.'}";
			exit;
		}
		return $addresslists;
	}
	/*public function getGmailContacts($user,$password){
 
		error_reporting(E_ALL);

		// step 1: login
		$login_url = "https://www.google.com/accounts/ClientLogin";
		$fields = array(
			'Email' => $user,
			'Passwd' => $password,
			'service' => 'cp', // <== contact list service code
			'source' => 'test-google-contact-grabber',
			'accountType' => 'GOOGLE',
		);
 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$login_url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$fields);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec($curl);

		$returns = array();
 
		foreach (explode("\n",$result) as $line)
		{
			$line = trim($line);
			if (!$line) continue;
			list($k,$v) = explode("=",$line,2);
 
			$returns[$k] = $v;
		}
 
		curl_close($curl);

		// step 2: grab the contact list
		$feed_url = "http://www.google.com/m8/feeds/contacts/$user/full?alt=json&max-results=250";
		
		if(count($returns) == 0) $returns['Auth'] = 0; //if no auth ,set one
		
		$header = array(
			'Authorization: GoogleLogin auth=' . $returns['Auth'],
		);
 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $feed_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
 
		$result = curl_exec($curl);
		curl_close($curl);
 
		$data = json_decode($result);
		$contacts = array();
		
		foreach ($data->feed->entry as $entry)
		{
			$contact = new stdClass();
			$contact->title = $entry->title->{'$t'};
			$contact->email = $entry->{'gd$email'}[0]->address;
			// $contact->mobile = $entry->{'gd$phoneNumber'}[0]->{'$t'};
			$contact->mobile = '111';
	
 
			$contacts[] = $contact;
		}

		return $contacts;
	}*/
	
	public function getYahooContacts($login,$password){
		require_once('GrabYahoo.php');
		// Initializing Class
		$yahoo  = new GrabYahoo;
		$yahoo->service = "addressbook";
		/*
		Set to true if HTTP proxy is required to browse net
		- Setting it to true will require to provide Proxy Host Name and Port number
		*/
		$yahoo->isUsingProxy = false;

		// Set the Proxy Host Name, isUsingProxy is set to true
		$yahoo->proxyHost = "";

		// Set the Proxy Port Number
		$yahoo->proxyPort = "";

		// Set the location to save the Cookie Jar File
		$yahoo->cookieJarPath = "./";

		/* 
		Execute the Service 
		- Require to pass Yahoo! Account Username and Password
		*/
		$yahooList = $yahoo->execService($login, $password);

		// Printing the array generated by the Class
		$result = array();
		if($yahooList == ""){
			echo "{'error':'Email authentication failed, please check your account.'}";
			exit;
		}
		
		if(is_array($yahooList)){
			if(empty($yahooList)){
				echo "{'error':'No contacts found.'}";
				exit;
			}else{
				foreach($yahooList as $vals){
					array_push($result,$vals);
				}
			}
		}
		return $result;
	}
}

?>
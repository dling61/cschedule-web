<?php
/*
* @param  $type  identify if there is any urls behind  '?'
*/
class RESTClient extends CComponent
{
	private $_headers = array(
	'Accept: application/json',
	'Content-Type: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36'
	);
	// public $_url = "http://apitest2.servicescheduler.net/"; //server url
	
	public function getResponse($url,$method,$info = NULL,$ownerid=NULL,$type=false){
		$param = array(
			'd'=>DEVICE,
			'v'=>VERSION,
			'sc'=>SCODE
		);

		if(is_array($info)){
			$data = json_encode($info);
			if(strtoupper($method) == 'GET'){
				$url = $url."?".http_build_query($info, '', "&").'&'.http_build_query($param);
			}else{
				if($type)
					$url = $url.'&'.http_build_query($param);
				else
					$url = $url.'?'.http_build_query($param);
			}
		}else{
			$url = $url.'?'.http_build_query($param);
		}

		if($ownerid){
			$url = $url.'&ownerid='.$ownerid;
		}
		// echo $this->_url.$url;exit;
		$headers = $this->_headers;
		$handle = curl_init();
		// curl_setopt($handle, CURLOPT_URL, $this->_url.$url);
		curl_setopt($handle, CURLOPT_URL, SERVERURL.$url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	
		switch(strtoupper($method))
		{

			case 'GET':
			break;

			case 'POST':
			curl_setopt($handle, CURLOPT_POST, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
			break;

			case 'PUT':
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
			break;

			case 'DELETE':
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
			break;
		}		
		$response = curl_exec($handle);
		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$arr = array();
		$arr['response'] = 	$response;
		$arr['code'] = $code;
		return $arr;
	}
}

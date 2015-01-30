<?php
/*
* 预防数据库攻击
*/
function check_input($value)
{
    // 去除斜杠
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    // 如果不是数字则加引号
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}

/*********************************************************************
 * 函数名称:encrypt
 * 函数作用:加密解密字符串
 * 使用方法:
 * 加密     :encrypt('str','E','nowamagic');
 * 解密     :encrypt('被加密过的字符串','D','nowamagic');
 * 参数说明:
 * $string   :需要加密解密的字符串
 * $operation:判断是加密还是解密:E:加密   D:解密
 * $key      :加密的钥匙(密匙);
 *********************************************************************/
function encrypt($string, $operation, $key = '')
{
    $key = md5($key);
    $key_length = strlen($key);
    $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'D') {
        if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
            return substr($result, 8);
        } else {
            return '';
        }
    } else {
        return str_replace('=', '', base64_encode($result));
    }
}


function getServerSetting(){
    $ownerid = $_SESSION['ownerid'];
    $lastupdatetime = '0000-00-00 00:00:00';
    $arr = array(
        'ownerid'=>$ownerid,
        'lastupdatetime'=>$lastupdatetime
    );

    $rest = new RestClient();
    $s_result = $rest->getResponse('serversetting','get',$arr);
    $data = json_decode($s_result['response']);

    $alerts = array();
    foreach ($data->alerts as $alert){
        $alerts[$alert->id] = $alert->aname;
    }
    Yii::app()->cache->set('setting-alerts', $alerts, CACHETIME);


    $timezones = array();
    $timezone_abbrs = array();
    usort($data->timezones, 'compareTimeZone');

    foreach ($data->timezones as $timezone){
        $timezones[$timezone->id] = $timezone->tzname;
        $timezone_abbrs[$timezone->id] = $timezone->abbrtzname;
    }

    Yii::app()->cache->set('setting-timezones', $timezones, CACHETIME);
    Yii::app()->cache->set('setting-timezone-abbrs', $timezone_abbrs, CACHETIME);
}

function compareTimeZone($a, $b){
    return intval($a->displayorder) - intval($b->displayorder);
}

//activity repeat
function repeat($key)
{
    $arr = array(
        '0' => 'None',
        '1' => 'Every day',
        '2' => 'Every week',
        '3' => 'Every 2 weeks',
        '4' => 'Every month',
        '5' => 'Every year'
    );
    return $arr[$key];
}

function repeatText($value)
{
    $arr = array(
        '0' => 'None',
        '1' => 'Every day',
        '2' => 'Every week',
        '3' => 'Every 2 weeks',
        '4' => 'Every month',
        '5' => 'Every year'
    );
    return array_search($value, $arr);
}


function getAlerts(){
    $alerts = Yii::app()->cache->get('setting-alerts');
    if (!is_array($alerts)){
        getServerSetting();
        $alerts = Yii::app()->cache->get('setting-alerts');
    }

    return $alerts;
}

//activity alert
function alert($key)
{
    /*$arr = array(
        '0' => 'None',
        '1' => '5 minutes before',
        '2' => '15 minutes before',
        '3' => '30 minutes before',
        '4' => '1 hour before',
        '5' => '2 hour before',
        '6' => '1 day before',
        '7' => '2 day before',
        '8' => '3 day before',
        '9' => '7 day before'
    );
    return $arr[$key];*/
    $alerts = Yii::app()->cache->get('setting-alerts');
    if (!is_array($alerts)){
        getServerSetting();
        $alerts = Yii::app()->cache->get('setting-alerts');
    }

    return $alerts[$key];
}

function alertText($value)
{
    /*$arr = array(
        '0' => 'None',
        '1' => '5 minutes before',
        '2' => '15 minutes before',
        '3' => '30 minutes before',
        '4' => '1 hour before',
        '5' => '2 hour before',
        '6' => '1 day before',
        '7' => '2 day before',
        '8' => '3 day before',
        '9' => '7 day before'
    );

    return array_search($value, $arr);*/
    $alerts = Yii::app()->cache->get('setting-alerts');
    if (!is_array($alerts)){
        getServerSetting();
        $alerts = Yii::app()->cache->get('setting-alerts');
    }

    return array_search($value, $alerts);
}

function isLogin()
{
    if (empty($_SESSION['ownerid']))
        return true;
    else return false;

}

/**
 * @param string $string 原文或者密文
 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key 密钥
 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 * $a = authcode('abc', 'ENCODE', 'key');
 * $b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 * $a = authcode('abc', 'ENCODE', 'key', 3600);
 * $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600)
{

    $ckey_length = 4;
    // 随机密钥长度 取值 0-32;
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥


    $key = md5($key ? $key : 'key'); //这里可以填写默认key值
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey [$i] = ord($cryptkey [$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box [$i] + $rndkey [$i]) % 256;
        $tmp = $box [$i];
        $box [$i] = $box [$j];
        $box [$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box [$a]) % 256;
        $tmp = $box [$a];
        $box [$a] = $box [$j];
        $box [$j] = $tmp;
        $result .= chr(ord($string [$i]) ^ ($box [($box [$a] + $box [$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }

}

function dump($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function getMonthLastDay($month, $year)
{
    switch ($month) {
        case 4 :
        case 6 :
        case 9 :
        case 11 :
            $days = 30;
            break;
        case 2 :
            if ($year % 4 == 0) {
                if ($year % 100 == 0) {
                    $days = $year % 400 == 0 ? 29 : 28;
                } else {
                    $days = 29;
                }
            } else {
                $days = 28;
            }
            break;

        default :
            $days = 31;
            break;
    }
    return $days;
}

function   my_nl2br($s)
{
    return str_replace("\n", '\r\n', str_replace("\r", '<br>', str_replace("\r\n", '<br>', $s)));
}

function getAllTimezones()
{
    $timezones = array(
        '(GMT-12:00) International Date Line West' => 'Pacific/Wake',
        '(GMT-11:00) Midway Island' => 'Pacific/Apia',
        '(GMT-11:00) Samoa' => 'Pacific/Apia',
        '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
        '(GMT-09:00) Alaska' => 'America/Anchorage',
        '(GMT-08:00) Pacific Time (US &amp; Canada); Tijuana' => 'America/Los_Angeles',
        '(GMT-07:00) Arizona' => 'America/Phoenix',
        '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
        '(GMT-07:00) La Paz' => 'America/Chihuahua',
        '(GMT-07:00) Mazatlan' => 'America/Chihuahua',
        '(GMT-07:00) Mountain Time (US &amp; Canada)' => 'America/Denver',
        '(GMT-06:00) Central America' => 'America/Managua',
        '(GMT-06:00) Central Time (US &amp; Canada)' => 'America/Chicago',
        '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
        '(GMT-06:00) Mexico City' => 'America/Mexico_City',
        '(GMT-06:00) Monterrey' => 'America/Mexico_City',
        '(GMT-06:00) Saskatchewan' => 'America/Regina',
        '(GMT-05:00) Bogota' => 'America/Bogota',
        '(GMT-05:00) Eastern Time (US &amp; Canada)' => 'America/New_York',
        '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
        '(GMT-05:00) Lima' => 'America/Bogota',
        '(GMT-05:00) Quito' => 'America/Bogota',
        '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
        '(GMT-04:00) Caracas' => 'America/Caracas',
        '(GMT-04:00) La Paz' => 'America/Caracas',
        '(GMT-04:00) Santiago' => 'America/Santiago',
        '(GMT-03:30) Newfoundland' => 'America/St_Johns',
        '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
        '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Greenland' => 'America/Godthab',
        '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
        '(GMT-01:00) Azores' => 'Atlantic/Azores',
        '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
        '(GMT) Casablanca' => 'Africa/Casablanca',
        '(GMT) Edinburgh' => 'Europe/London',
        '(GMT) Greenwich Mean Time : Dublin' => 'Europe/London',
        '(GMT) Lisbon' => 'Europe/London',
        '(GMT) London' => 'Europe/London',
        '(GMT) Monrovia' => 'Africa/Casablanca',
        '(GMT+01:00) Amsterdam' => 'Europe/Berlin',
        '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
        '(GMT+01:00) Berlin' => 'Europe/Berlin',
        '(GMT+01:00) Bern' => 'Europe/Berlin',
        '(GMT+01:00) Bratislava' => 'Europe/Belgrade',
        '(GMT+01:00) Brussels' => 'Europe/Paris',
        '(GMT+01:00) Budapest' => 'Europe/Belgrade',
        '(GMT+01:00) Copenhagen' => 'Europe/Paris',
        '(GMT+01:00) Ljubljana' => 'Europe/Belgrade',
        '(GMT+01:00) Madrid' => 'Europe/Paris',
        '(GMT+01:00) Paris' => 'Europe/Paris',
        '(GMT+01:00) Prague' => 'Europe/Belgrade',
        '(GMT+01:00) Rome' => 'Europe/Berlin',
        '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
        '(GMT+01:00) Skopje' => 'Europe/Sarajevo',
        '(GMT+01:00) Stockholm' => 'Europe/Berlin',
        '(GMT+01:00) Vienna' => 'Europe/Berlin',
        '(GMT+01:00) Warsaw' => 'Europe/Sarajevo',
        '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
        '(GMT+01:00) Zagreb' => 'Europe/Sarajevo',
        '(GMT+02:00) Athens' => 'Europe/Istanbul',
        '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
        '(GMT+02:00) Cairo' => 'Africa/Cairo',
        '(GMT+02:00) Harare' => 'Africa/Johannesburg',
        '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
        '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
        '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
        '(GMT+02:00) Kyiv' => 'Europe/Helsinki',
        '(GMT+02:00) Minsk' => 'Europe/Istanbul',
        '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
        '(GMT+02:00) Riga' => 'Europe/Helsinki',
        '(GMT+02:00) Sofia' => 'Europe/Helsinki',
        '(GMT+02:00) Tallinn' => 'Europe/Helsinki',
        '(GMT+02:00) Vilnius' => 'Europe/Helsinki',
        '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
        '(GMT+03:00) Kuwait' => 'Asia/Riyadh',
        '(GMT+03:00) Moscow' => 'Europe/Moscow',
        '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
        '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
        '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
        '(GMT+03:00) Volgograd' => 'Europe/Moscow',
        '(GMT+03:30) Tehran' => 'Asia/Tehran',
        '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
        '(GMT+04:00) Baku' => 'Asia/Tbilisi',
        '(GMT+04:00) Muscat' => 'Asia/Muscat',
        '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
        '(GMT+04:00) Yerevan' => 'Asia/Tbilisi',
        '(GMT+04:30) Kabul' => 'Asia/Kabul',
        '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
        '(GMT+05:00) Islamabad' => 'Asia/Karachi',
        '(GMT+05:00) Karachi' => 'Asia/Karachi',
        '(GMT+05:00) Tashkent' => 'Asia/Karachi',
        '(GMT+05:30) Chennai' => 'Asia/Calcutta',
        '(GMT+05:30) Kolkata' => 'Asia/Calcutta',
        '(GMT+05:30) Mumbai' => 'Asia/Calcutta',
        '(GMT+05:30) New Delhi' => 'Asia/Calcutta',
        '(GMT+05:45) Kathmandu' => 'Asia/Katmandu',
        '(GMT+06:00) Almaty' => 'Asia/Novosibirsk',
        '(GMT+06:00) Astana' => 'Asia/Dhaka',
        '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
        '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
        '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
        '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
        '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
        '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
        '(GMT+07:00) Jakarta' => 'Asia/Bangkok',
        '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
        '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
        '(GMT+08:00) Chongqing' => 'Asia/Hong_Kong',
        '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
        '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
        '(GMT+08:00) Kuala Lumpur' => 'Asia/Singapore',
        '(GMT+08:00) Perth' => 'Australia/Perth',
        '(GMT+08:00) Singapore' => 'Asia/Singapore',
        '(GMT+08:00) Taipei' => 'Asia/Taipei',
        '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
        '(GMT+08:00) Urumqi' => 'Asia/Hong_Kong',
        '(GMT+09:00) Osaka' => 'Asia/Tokyo',
        '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
        '(GMT+09:00) Seoul' => 'Asia/Seoul',
        '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
        '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
        '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
        '(GMT+09:30) Darwin' => 'Australia/Darwin',
        '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
        '(GMT+10:00) Canberra' => 'Australia/Sydney',
        '(GMT+10:00) Guam' => 'Pacific/Guam',
        '(GMT+10:00) Hobart' => 'Australia/Hobart',
        '(GMT+10:00) Melbourne' => 'Australia/Sydney',
        '(GMT+10:00) Port Moresby' => 'Pacific/Guam',
        '(GMT+10:00) Sydney' => 'Australia/Sydney',
        '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
        '(GMT+11:00) Magadan' => 'Asia/Magadan',
        '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
        '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
        '(GMT+12:00) Auckland' => 'Pacific/Auckland',
        '(GMT+12:00) Fiji' => 'Pacific/Fiji',
        '(GMT+12:00) Kamchatka' => 'Pacific/Fiji',
        '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
        '(GMT+12:00) Wellington' => 'Pacific/Auckland',
        '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu',
    );
    return $timezones;
}

function getPartTimezones()
{
    /*$timezones = array(
        '-11' => "US/Samoa",
        '-10' => "US/Hawaii",
        '-9' => "US/Alaska",
        '-8' => "US/Pacific",
        '-7' => "US/Arizona &amp; US/Mountain",
        '-6' => "US/Central",
        '-5' => "US/Eastern &amp; US/East-Indiana",

        '-4' => "Canada/Atlantic",

        '-3.5' => "Canada/Newfoundland",

    );
    return $timezones;*/

    $timezones = Yii::app()->cache->get('setting-timezones');
    if (!is_array($timezones)){
        getServerSetting();
        $timezones = Yii::app()->cache->get('setting-timezones');
    }

    return $timezones;
}

//获取时区缩写
function getTimezoneAbbr($timezone)
{
    /*$abbrs = array(
        '-12' => "IDLW",
        '-11' => "NT",
        '-10' => "CAT",
        '-9' => "HDT",
        '-8' => "PST",
        '-7' => "PDT",
        '-6' => "CST",
        '-5' => "EST",
        '-4' => "EDT",
        '-3.5' => "NST",

        '-3' => "ADT",
        '-2.5' => "NDT",

        '-1' => "WAT",
        '0' => "GMT",
        '1' => "CET",
        '2' => "EET",
        '3' => "BT",
        '3.5' => "IT",
        '7.5' => "JT",
        '8' => "CCT",

        '8.5' => "MT",
        '9' => "JST",
        '9.5' => "SAT",
        '10' => "GST",
        '10.5' => "CST",
        '11' => "AESST",
        '12' => "NZT",
        '13' => "NZDT",
    );
    $tz = (string)$timezone;
    return $abbrs[$tz];*/

    $timezones = Yii::app()->cache->get('setting-timezone-abbrs');
    if (!is_array($timezones)){
        getServerSetting();
        $timezones = Yii::app()->cache->get('setting-timezone-abbrs');
    }

    $tz = (string)$timezone;
    return $timezones[$tz];
}

?>
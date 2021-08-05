<?php
header("content-Type: text/html; charset=utf-8");
function curl_get($url, $use = false, $save = false, $referer = null, $post_data = null){
global $cookie_file;
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
if($use){
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
}
if($save){
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
}
if(isset($referer))
curl_setopt($ch, CURLOPT_REFERER, $referer);
if(is_array($post_data)) {
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
}
$content = curl_exec($ch);
curl_close($ch);
return $content;
}

function get_formhash($res){
if(preg_match('/name="formhash" value="(.*?)"/i', $res, $matches))
return $matches[1];
else
exit('FormHash not found!');
}

$user = '  input  your  username';  //here input username
$pwd = '  input  your  password';  //here input password
$baseUrl = 'https://www.acgke.cc/';
$loginPageUrl = $baseUrl.'member.php?mod=logging&action=login';
$loginSubmitUrl = $baseUrl.'member.php?mod=logging&action=login&loginsubmit=yes&loginhash=LsCnN';
$signPageUrl = $baseUrl.'home.php?mod=spacecp&ac=pm&op=checknewpm&rand='.strval(time());
$cookie_file = tempnam('./temp','cookie');
$res=curl_get($loginPageUrl, false, true);
$formhash = get_formhash($res);
$login_array=array(
'username'=>$user,
'password'=>$pwd,
'referer'=>$baseUrl,
'questionid'=>0,
'answer'=>'',
'formhash'=>$formhash,
);
$res=curl_get($loginSubmitUrl ,true, true, null, $login_array);
if(strpos($res, '欢迎您回来'))
{
$res=curl_get($signPageUrl, true, true);
$resultStr = '签到完成';
}
else
{
$resultStr = '登陆失败';
}
echo $resultStr;
unlink($cookie_file);
?>
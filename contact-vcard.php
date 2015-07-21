<?php
(@include_once '../../config.core.php') or die("core configuration file not found");
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');

$path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');
$path .= 'model/clientconfig/';
$clientConfig = $modx->getService('clientconfig','ClientConfig', $path);

if ($clientConfig instanceof ClientConfig) {
    $settings = $clientConfig->getSettings();
}

$companyName 			= $modx->getOption('site_name');
$siteUrl				= $modx->getOption('site_url');

if($_GET['id']){
	$resource 			= $modx->getObject('modResource', $_GET['id']);

	$image				= $resource->getTVValue('photo');
	$street				= $resource->getTVValue('street');
	$housenumber		= $resource->getTVValue('housenumber');
	$zipcode			= $resource->getTVValue('zipcode');
	$city				= $resource->getTVValue('city');
	$fax				= $resource->getTVValue('fax');
	$phone				= $resource->getTVValue('phone');
	$email 				= $resource->getTVValue('email');
	$fullName 			= $resource->get('pagetitle');

}
else {
	$image				= $settings['vcard_logo'];
	$street 			= $settings['street'];
	$housenumber 		= $settings['housenumber'];
	$zipcode 			= $settings['zipcode'];
	$city 				= $settings['city'];
	$fax 				= $settings['fax'];
	$phone 				= $settings['phone'];
	$email 				= $settings['email_client'];	
	$fullName 			= $companyName; 
}

$getPhoto				= file_get_contents($siteUrl . $image);
$b64vcard 				= base64_encode($getPhoto);
$b64mline 				= chunk_split($b64vcard,74,"\n");
$b64final 				= preg_replace('/(.+)/', ' $1', $b64mline);
$photo 					= $b64final;

header('Content-Type: text/x-vcard');  
header('Content-Disposition: inline; filename= "vCard.vcf"');  

$vCard = "BEGIN:VCARD\r\n";
$vCard .= "VERSION:3.0\r\n";
$vCard .= "FN:" . $fullName . "\r\n";
$vCard .= "TITLE:" . $companyName . "\r\n";

if($street or $zipcode OR $city){
	$vCard .= "ADR;TYPE=work:;;" . $street . ' ' . $housenumber . ";" . $city . ";IL;" . $zipcode . ";\r\n";
}

if($fax){
	$vCard .= "TEL;FAX;WORK:" . $fax . "\r\n";
}

if($email){
	$vCard .= "EMAIL;TYPE=internet,pref:" . $email . "\r\n";
}

if($getPhoto){
	$vCard .= "PHOTO;ENCODING=b;TYPE=JPEG:";
	$vCard .= $photo . "\r\n";
}

if($phone){
	$vCard .= "TEL;TYPE=work,voice:" . $phone . "\r\n";	
}

$vCard .= "END:VCARD\r\n";

echo $vCard;

<?php
(@include_once '../../config.core.php') or die("Configuratiebestand is niet gevonden");
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');

$path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');
$path .= 'model/clientconfig/';
$clientConfig = $modx->getService('clientconfig','ClientConfig', $path);

/* If we got the class (gotta be careful of failed migrations), grab settings and go! */
if ($clientConfig instanceof ClientConfig) {
    $settings = $clientConfig->getSettings();
}

$companyName 			= $modx->getOption('site_name');
$street 				= $settings['street'];
$housenumber 			= $settings['housenumber'];
$zipcode 				= $settings['zipcode'];
$city 					= $settings['city'];
$fax 					= $settings['fax'];

$siteUrl				= $modx->getOption('site_url');

if(isset($_GET['id'])){
	//get tvs
	$resource 			= $modx->getObject('modResource', $_GET['id']);
	if($resource){
		$email 				= $resource->getTVValue('emailTVname');
		$phone				= $resource->getTVValue('phoneTVname');
		$image				= $resource->getTVValue('photoTVname');
		$fullName 			= $resource->get('pagetitle');
	}
	else {
		$modx->log(modX::LOG_LEVEL_ERROR,'Resource with ID: ' . $_GET['id'] . ' was not found.');
	}
}
else {
	//get options
	
	$street 			= $settings['street'];
	$housenumber 		= $settings['housenumber'];
	$zipcode 			= $settings['zipcode'];
	$city 				= $settings['city'];
	$phone 				= $settings['phone'];
	$fax 				= $settings['fax'];
	$email 				= $settings['email_client'];
	$image				= $settings['vcard_logo'];

	$fullName 			= $companyName; 
}


$getPhoto	= file_get_contents($siteUrl . $image);

$b64vcard = base64_encode($getPhoto);
$b64mline = chunk_split($b64vcard,74,"\n");
$b64final = preg_replace('/(.+)/', ' $1', $b64mline);

$photo = $b64final;

header('Content-Type: text/x-vcard');  
header('Content-Disposition: inline; filename= "vCard.vcf"');  

$vCard = "BEGIN:VCARD\r";
$vCard .= "VERSION:3.0\r";
$vCard .= "FN:" . $fullName . "\r";
$vCard .= "TITLE:" . $companyName . "\r";

if($street or $zipcode OR $city){
	$vCard .= "ADR;TYPE=work:;;" . $street . ' ' . $housenumber . ";" . $city . ";IL;" . $zipcode . ";\r";
}

if($fax){
	$vCard .= "TEL;FAX;WORK:" . $fax . "\r";
}

if($email){
	$vCard .= "EMAIL;TYPE=internet,pref:" . $email . "\r";
}

if($getPhoto){
	$vCard .= "PHOTO;ENCODING=b;TYPE=JPEG:\r";
	$vCard .= $photo;
}

if($phone){
	$vCard .= "TEL;TYPE=work,voice:" . $phone . "\r";	
}

$vCard .= "END:VCARD";

echo $vCard;

<?php


declare(strict_types=1);

require_once 'vendor/autoload.php';

use Smsapi\Client\SmsapiHttpClient;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;

$apiToken = 'c2WLcMsUaHv8q7jtopErLj3XWIXmcbBnXAxxKoAQ';
$service = (new SmsapiHttpClient())->smsapiPlService($apiToken);

$numery = [
	'Ala'    => '605565190',
	'Henryk' => '605565190',
	'Ela'    => '605565190',
	'Hania'  => '500784861',
	'Kinga'  => '574345393',
	'Piotr'  => '607179362',
	'Adam'   => '500598372',
	'Michal' => '609565341'
];

function czyPoprawnaPara($mikolaj, $grzeczneDziecko) {
	if ($mikolaj == $grzeczneDziecko) {
		return false;
	}

	if (in_array($mikolaj, ['Ala', 'Henryk']) && $grzeczneDziecko == 'Ela') {
		return false;
	}

	return true;
}

function losujPan($numery) {

	$imiona = array_keys($numery);
	$max  = count($imiona);
	$pary = [];
	
	foreach ($numery as $mikolaj => $numerMikolaja) {

		$dziecko = null;
		do {
			$indeks = rand(0, $max-1);
			if (czyPoprawnaPara($mikolaj, $imiona[$indeks])) {
				$dziecko = $imiona[$indeks];
				unset($imiona[$indeks]);
				$imiona = array_values($imiona);
				$max  = count($imiona);
				$pary[$mikolaj] = $dziecko;
			}

		} while ($dziecko == null);
	}

	return $pary;
}


$wynik = losujPan($numery);

echo "Mam listę, wysyałem smsy...\n";

foreach ($wynik as $mikolaj => $dziecko) {

	$numerTelefonu = $numery[$mikolaj];
	$wiadomosc = 'Czesc ' . $mikolaj . ', oto osoba wylosowana dla Ciebie: ' . $dziecko;
	echo "Wysylam wiadomosc do ". $mikolaj . "\n";
	$sms = SendSmsBag::withMessage($numerTelefonu, $wiadomosc);
	$service->smsFeature()->sendSms($sms);
}



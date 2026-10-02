<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);

function server_time() {
	try {
		if(true) {
			$auth = \TRP\IronChannel\BasicAuth::process('findhash');
		}
		elseif(false) {
			$auth = \TRP\IronChannel\BasicAuth::get();
			$auth->validate('john','$2y$10$xWRs2GTUyZ8QRiAt9fdAau5UDbA2St0r0MOXSSJuXOKt636UzYckC');
		}
		else {
			$auth = new \TRP\IronChannel\NoAuth();
		}
		\TRP\IronChannel\Server::confirm($auth);
		$timezone = \TRP\IronChannel\Get::read('timezone');
	}
	catch(\Exception $e) {
		http_response_code($e->getCode());
		echo $e->getMessage();
		exit;
	}

	sleep(2);
	echo "Timezone: ".$timezone.PHP_EOL;
	date_default_timezone_set($timezone);
	echo "Date: ".date('r');
}

function findhash(string $id) : string {
	if($id !== 'john') {
		throw new \Exception('Unknown user',403);
	}
	return '$2y$10$xWRs2GTUyZ8QRiAt9fdAau5UDbA2St0r0MOXSSJuXOKt636UzYckC';
}

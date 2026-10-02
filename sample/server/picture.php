<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);

function server_picture() {
	\TRP\IronChannel\Server::confirm(new \TRP\IronChannel\NoAuth());
	\TRP\IronChannel\RawString::save(__DIR__.'/save/'.$_GET['filename']);
	$filename = \TRP\IronChannel\Get::read('filename');
	echo $filename.' saved';
}

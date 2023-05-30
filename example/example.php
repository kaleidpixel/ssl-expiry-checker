<?php

require dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use kaleidpixel\SSLExpiryChecker;

try {
	$checker = new SSLExpiryChecker( 'https://www.google.com' );

	echo "SSL Certificate Expiry: " . $checker->getExpiryDate() . PHP_EOL;
	echo "SSL Certificate Remind: " . $checker->remainingDays() . PHP_EOL;
} catch ( Exception $e ) {
	echo $e->getMessage() . PHP_EOL;
}

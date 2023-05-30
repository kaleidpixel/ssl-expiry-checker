<?php

namespace kaleidpixel\Tests;

use PHPUnit\Framework\TestCase;
use kaleidpixel\SSLExpiryChecker;

class SSLExpiryCheckerTest extends TestCase {
	private $checker;

	protected function setUp(): void {
		$this->checker = new SSLExpiryChecker( 'https://www.google.com' );
	}

	public function testGetCertificateExpiry() {
		$this->assertMatchesRegularExpression(
			'/^\d{4}-\d{2}-\d{2}$/',
			$this->checker->getExpiryDate()
		);
	}

	public function testGetCertificateRemainingDays() {
		$this->assertMatchesRegularExpression(
			'/^\d{1,4}$/',
			$this->checker->remainingDays()
		);
	}
}

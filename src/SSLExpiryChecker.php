<?php
/**
 * PHP 7.4 or later
 *
 * @package    KALEIDPIXEL
 * @author     KAZUKI Otsuhata
 * @copyright  2023 (C) Kaleid Pixel
 * @license    MIT License
 * @version    0.0.1
 **/

namespace kaleidpixel;

class SSLExpiryChecker {
	private $url;
	private $expiry;

	public function __construct( $url ) {
		$this->url    = $url;
		$this->expiry = $this->__getExpiryDate();
	}

	/**
	 * @param string $date YYYY-mm-dd
	 *
	 * @return false|int
	 * @throws \Exception
	 */
	private function __getIntervalDays( $date ) {
		$now        = new \DateTime();
		$targetDate = new \DateTime( $date );
		$interval   = $now->diff( $targetDate );

		return $interval->days + 1;
	}

	/**
	 * @return false|string
	 * @throws \Exception
	 */
	private function __getExpiryDate() {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $this->url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_VERBOSE, true );
		curl_setopt( $ch, CURLOPT_CERTINFO, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt( $ch, CURLOPT_FORBID_REUSE, true );
		curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );
		curl_setopt( $ch, CURLINFO_HEADER_OUT, false );

		$response = curl_exec( $ch );

		if ( $response === false ) {
			throw new \Exception( "Curl error: " . curl_error( $ch ) );
		}

		$certInfo = curl_getinfo( $ch, CURLINFO_CERTINFO );

		if ( $certInfo === false || !isset( $certInfo[0]['Expire date'] ) ) {
			throw new \Exception( "Failed to retrieve certificate information" );
		}

		$validTo = date( 'Y-m-d', strtotime( $certInfo[0]['Expire date'] ) );

		curl_close( $ch );

		return $validTo;
	}

	/**
	 * @return false|string
	 */
	public function getExpiryDate() {
		return $this->expiry;
	}

	/**
	 * @return false|int
	 * @throws \Exception
	 */
	public function remainingDays() {
		return $this->__getIntervalDays( $this->expiry );
	}
}

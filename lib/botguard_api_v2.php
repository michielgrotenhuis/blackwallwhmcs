<?php
/*
 * botguard_api_v2.php
 * This file is an implementation of the BotGuard Partner API version 2
 *
 * Copyright (c) 2022 Dennis Prochko <wolfsoft@mail.ru>
*/

class BotGuard {

	private $api_key;

	/**
	 * Constructor. Initialize the instance of the BotGuard class.
	 *
	 * @param string $api_key BotGuard API Key to access all functions
	 */
	public function __construct(string $api_key) {
		$this->api_key = $api_key;
	}

	/**
	 * Get a list of all registered domains in your BotGuard account.
	 *
	 * @returns An array of JSON objects
	 */
	public function getDomains() {
		$result = $this->doRequest(
			'GET',
			"https://apiv2.botguard.net/website"
		);
		if (!$result) {
			throw new Exception('There are no domains registered.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Add domain into your BotGuard account.
	 *
	 * @param string $domain Domain name
	 */
	public function addDomain(string $domain, int $user = null) {
		$data = ['domain' => trim($domain)];
		if ($user) {
			$data['user'] = $user;
		}
		$result = $this->doRequest(
			'POST',
			'https://apiv2.botguard.net/website',
			$data
		);
		if (!$result) {
			throw new Exception('Domain registration error. Please go back and try again.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Get an information about domain in your BotGuard account.
	 *
	 * @param string $domain Domain name
	 *
	 * @returns JSON object
	 */
	public function getDomain(string $domain) {
		$d = urlencode(trim($domain));
		$result = $this->doRequest(
			'GET',
			"https://apiv2.botguard.net/website/$d"
		);
		if (!$result) {
			throw new Exception('Domain is not registered.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Delete domain from your BotGuard account.
	 *
	 * @param string $domain Domain name
	 */
	public function deleteDomain(string $domain) {
		$d = urlencode(trim($domain));
		$result = $this->doRequest(
			'DELETE',
			"https://apiv2.botguard.net/website/$d"
		);
		if (!$result) {
			throw new Exception('Domain deregistration error. Please go back and try again.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}
		
		return $result;
	}

	/**
	 * Suspend domain in your BotGuard account.
	 *
	 * @param string $domain Domain name
	 */
	public function suspendDomain(string $domain) {
		$d = urlencode(trim($domain));
		$result = $this->doRequest(
			'PUT',
			"https://apiv2.botguard.net/website/$d", [
				'status' => 'paused'
			]
		);
		if (!$result) {
			throw new Exception('Domain suspending error. Please go back and try again.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Unsuspend (bring online) domain in your BotGuard account.
	 *
	 * @param string $domain Domain name
	 */
	public function unsuspendDomain(string $domain) {
		$d = urlencode(trim($domain));
		$result = $this->doRequest(
			'PUT',
			"https://apiv2.botguard.net/website/$d", [
				'status' => 'online'
			]
		);
		if (!$result) {
			throw new Exception('Domain unsuspending error. Please go back and try again.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Get all subaccounts from your BotGuard account.
	 *
	 * @param string $domain Domain name
	 */
	public function getSubaccounts(array $filter = []) {
		$result = $this->doRequest(
			'GET',
			"https://apiv2.botguard.net/user",
			$filter
		);
		if (!$result) {
			throw new Exception('Subaccount getting error. Please go back and try again.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Adds a subaccount into your BotGuard account.
	 *
	 * @param string $domain Domain name
	 */
	public function addSubaccount(array $user) {
		$result = $this->doRequest(
			'POST',
			"https://apiv2.botguard.net/user",
			$user
		);
		if (!$result) {
			throw new Exception('Subaccount adding error. Please go back and try again.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Adds a subaccount into your BotGuard account.
	 *
	 * @param string $domain Domain name
	 */
	public function deleteSubaccount(int $user_id) {
		$result = $this->doRequest(
			'DELETE',
			"https://apiv2.botguard.net/user/$user_id"
		);
		if (!$result) {
			throw new Exception('Subaccount adding error. Please go back and try again.');
		}
		if (property_exists($result, 'status') && $result->status == 'error') {
			throw new Exception($result->message);
		}

		return $result;
	}

	/**
	 * Get a link to the Statistics page for iframe embedding.
	 *
	 * @param string $domain Domain name
	 *
	 * @returns An URL to the page
	 */
	public function getStatisticsPageLink(string $domain, string $api_key, string $lang) {
		$d = urlencode(trim($domain));
		return "https://apiv2.botguard.net/$lang/website/$d/statistics?api-key=$api_key";
	}

	/**
	 * Get a link to the Events page for iframe embedding.
	 *
	 * @param string $domain Domain name
	 *
	 * @returns An URL to the page
	 */
	public function getEventsPageLink(string $domain, string $api_key, string $lang) {
		$d = urlencode(trim($domain));
		return "https://apiv2.botguard.net/$lang/website/$d/events?api-key=$api_key";
	}

	/**
	 * Get a link to the website settings page for iframe embedding.
	 *
	 * @param string $domain Domain name
	 *
	 * @returns An URL to the page
	 */
	public function getSettingsPageLink(string $domain, string $api_key, string $lang) {
		$d = urlencode(trim($domain));
		return "https://apiv2.botguard.net/$lang/website/$d/edit?api-key=$api_key";
	}


	/**
	 * Make a generic https request to the BotGuard API endpoint.
	 *
	 * @param string $method HTTP method (GET, POST, ...)
	 * @param string $url URL to make request to
	 * @param string $params An array of "name=value" parameters to pass into the request (optional)
	 *
	 * @returns JSON object
	 */
	private function doRequest(string $method, string $url, array $params = null) {
		$http = [
			'protocol_version' => '1.1',
			'header' => "Accept: application/json\r\nAuthorization: Bearer {$this->api_key}\r\n",
			'method' => $method,
			'ignore_errors' => true
		];
		if ($params !== null) {
			$http['header'] .= "Content-type: application/x-www-form-urlencoded\r\n";
			$http['content'] = http_build_query($params);
		}
		$context = stream_context_create([
			'http' => $http,
			'ssl' => [
				'peer_name' => 'apiv2.botguard.net',
				'verify_depth' => 3,
				'SNI_enabled' => true
			]
		]);
		return @json_decode(@file_get_contents($url, false, $context));
	}

}
<?php
/*
 * botguard_api_v2.php
 * This file is an implementation of the BotGuard Partner API version 2
 * Updated for PHP 8.3 compatibility and modern standards
 *
 * Copyright (c) 2022 Dennis Prochko <wolfsoft@mail.ru>
 */

declare(strict_types=1);

class BotGuard {
    private string $api_key;
    private string $base_url = 'https://apiv2.botguard.net';
    private int $timeout = 30;
    private array $default_headers;

    /**
     * Constructor. Initialize the instance of the BotGuard class.
     *
     * @param string $api_key BotGuard API Key to access all functions
     * @param int $timeout Request timeout in seconds (default: 30)
     */
    public function __construct(string $api_key, int $timeout = 30) {
        if (empty($api_key)) {
            throw new InvalidArgumentException('API key cannot be empty');
        }
        
        $this->api_key = $api_key;
        $this->timeout = $timeout;
        $this->default_headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->api_key,
            'User-Agent: WHMCS-BotGuard-Module/2.0'
        ];
    }

    /**
     * Get a list of all registered domains in your BotGuard account.
     *
     * @return array An array of domain objects
     * @throws Exception When API request fails
     */
    public function getDomains(): array {
        $result = $this->doRequest('GET', '/website');
        
        if (!$result) {
            throw new Exception('No domains found or API request failed.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Unknown API error');
        }

        return is_array($result) ? $result : [$result];
    }

    /**
     * Add domain into your BotGuard account.
     *
     * @param string $domain Domain name
     * @param int|null $user User ID for subaccount assignment
     * @return object Domain creation result
     * @throws Exception When domain registration fails
     */
    public function addDomain(string $domain, ?int $user = null): object {
        $domain = trim($domain);
        if (empty($domain)) {
            throw new InvalidArgumentException('Domain name cannot be empty');
        }

        $data = ['domain' => $domain];
        if ($user !== null) {
            $data['user'] = $user;
        }

        $result = $this->doRequest('POST', '/website', $data);
        
        if (!$result) {
            throw new Exception('Domain registration error. Please try again.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Domain registration failed');
        }

        return $result;
    }

    /**
     * Get information about a domain in your BotGuard account.
     *
     * @param string $domain Domain name
     * @return object Domain information
     * @throws Exception When domain is not found or API request fails
     */
    public function getDomain(string $domain): object {
        $domain = trim($domain);
        if (empty($domain)) {
            throw new InvalidArgumentException('Domain name cannot be empty');
        }

        $encoded_domain = urlencode($domain);
        $result = $this->doRequest('GET', "/website/{$encoded_domain}");
        
        if (!$result) {
            throw new Exception('Domain is not registered or not found.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Failed to retrieve domain information');
        }

        return $result;
    }

    /**
     * Delete domain from your BotGuard account.
     *
     * @param string $domain Domain name
     * @return object Deletion result
     * @throws Exception When domain deletion fails
     */
    public function deleteDomain(string $domain): object {
        $domain = trim($domain);
        if (empty($domain)) {
            throw new InvalidArgumentException('Domain name cannot be empty');
        }

        $encoded_domain = urlencode($domain);
        $result = $this->doRequest('DELETE', "/website/{$encoded_domain}");
        
        if (!$result) {
            throw new Exception('Domain deletion error. Please try again.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Domain deletion failed');
        }

        return $result;
    }

    /**
     * Suspend domain in your BotGuard account.
     *
     * @param string $domain Domain name
     * @return object Suspension result
     * @throws Exception When domain suspension fails
     */
    public function suspendDomain(string $domain): object {
        $domain = trim($domain);
        if (empty($domain)) {
            throw new InvalidArgumentException('Domain name cannot be empty');
        }

        $encoded_domain = urlencode($domain);
        $result = $this->doRequest('PUT', "/website/{$encoded_domain}", [
            'status' => 'paused'
        ]);
        
        if (!$result) {
            throw new Exception('Domain suspension error. Please try again.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Domain suspension failed');
        }

        return $result;
    }

    /**
     * Unsuspend (bring online) domain in your BotGuard account.
     *
     * @param string $domain Domain name
     * @return object Unsuspension result
     * @throws Exception When domain unsuspension fails
     */
    public function unsuspendDomain(string $domain): object {
        $domain = trim($domain);
        if (empty($domain)) {
            throw new InvalidArgumentException('Domain name cannot be empty');
        }

        $encoded_domain = urlencode($domain);
        $result = $this->doRequest('PUT', "/website/{$encoded_domain}", [
            'status' => 'online'
        ]);
        
        if (!$result) {
            throw new Exception('Domain unsuspension error. Please try again.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Domain unsuspension failed');
        }

        return $result;
    }

    /**
     * Get all subaccounts from your BotGuard account.
     *
     * @param array $filter Filter parameters
     * @return array Array of subaccount objects
     * @throws Exception When subaccount retrieval fails
     */
    public function getSubaccounts(array $filter = []): array {
        $result = $this->doRequest('GET', '/user', $filter);
        
        if (!$result) {
            throw new Exception('Subaccount retrieval error. Please try again.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Failed to retrieve subaccounts');
        }

        return is_array($result) ? $result : [$result];
    }

    /**
     * Adds a subaccount to your BotGuard account.
     *
     * @param array $user User data
     * @return object Created subaccount object
     * @throws Exception When subaccount creation fails
     */
    public function addSubaccount(array $user): object {
        if (empty($user['email'])) {
            throw new InvalidArgumentException('Email is required for subaccount creation');
        }

        $result = $this->doRequest('POST', '/user', $user);
        
        if (!$result) {
            throw new Exception('Subaccount creation error. Please try again.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Subaccount creation failed');
        }

        return $result;
    }

    /**
     * Delete a subaccount from your BotGuard account.
     *
     * @param int $user_id User ID to delete
     * @return object Deletion result
     * @throws Exception When subaccount deletion fails
     */
    public function deleteSubaccount(int $user_id): object {
        if ($user_id <= 0) {
            throw new InvalidArgumentException('Valid user ID is required');
        }

        $result = $this->doRequest('DELETE', "/user/{$user_id}");
        
        if (!$result) {
            throw new Exception('Subaccount deletion error. Please try again.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Subaccount deletion failed');
        }

        return $result;
    }

    /**
     * Get a link to the Statistics page for iframe embedding.
     *
     * @param string $domain Domain name
     * @param string $api_key API key for authentication
     * @param string $lang Language code (en, ru)
     * @return string URL to the statistics page
     */
    public function getStatisticsPageLink(string $domain, string $api_key, string $lang = 'en'): string {
        $domain = trim($domain);
        $lang = in_array($lang, ['en', 'ru']) ? $lang : 'en';
        $encoded_domain = urlencode($domain);
        $encoded_api_key = urlencode($api_key);
        
        return "{$this->base_url}/{$lang}/website/{$encoded_domain}/statistics?api-key={$encoded_api_key}";
    }

    /**
     * Get a link to the Events page for iframe embedding.
     *
     * @param string $domain Domain name
     * @param string $api_key API key for authentication
     * @param string $lang Language code (en, ru)
     * @return string URL to the events page
     */
    public function getEventsPageLink(string $domain, string $api_key, string $lang = 'en'): string {
        $domain = trim($domain);
        $lang = in_array($lang, ['en', 'ru']) ? $lang : 'en';
        $encoded_domain = urlencode($domain);
        $encoded_api_key = urlencode($api_key);
        
        return "{$this->base_url}/{$lang}/website/{$encoded_domain}/events?api-key={$encoded_api_key}";
    }

    /**
     * Get a link to the website settings page for iframe embedding.
     *
     * @param string $domain Domain name
     * @param string $api_key API key for authentication
     * @param string $lang Language code (en, ru)
     * @return string URL to the settings page
     */
    public function getSettingsPageLink(string $domain, string $api_key, string $lang = 'en'): string {
        $domain = trim($domain);
        $lang = in_array($lang, ['en', 'ru']) ? $lang : 'en';
        $encoded_domain = urlencode($domain);
        $encoded_api_key = urlencode($api_key);
        
        return "{$this->base_url}/{$lang}/website/{$encoded_domain}/edit?api-key={$encoded_api_key}";
    }

    /**
     * Validate API key by making a test request.
     *
     * @return bool True if API key is valid
     * @throws Exception When validation fails
     */
    public function validateApiKey(): bool {
        try {
            $this->doRequest('GET', '/user');
            return true;
        } catch (Exception $e) {
            throw new Exception('API key validation failed: ' . $e->getMessage());
        }
    }

    /**
     * Get API usage statistics.
     *
     * @return object Usage statistics
     * @throws Exception When request fails
     */
    public function getUsageStats(): object {
        $result = $this->doRequest('GET', '/stats/usage');
        
        if (!$result) {
            throw new Exception('Failed to retrieve usage statistics.');
        }
        
        if (is_object($result) && property_exists($result, 'status') && $result->status === 'error') {
            throw new Exception($result->message ?? 'Usage stats retrieval failed');
        }

        return $result;
    }

    /**
     * Make a generic HTTPS request to the BotGuard API endpoint.
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint API endpoint path
     * @param array|null $params Parameters to send with the request
     * @return mixed JSON decoded response
     * @throws Exception When HTTP request fails
     */
    private function doRequest(string $method, string $endpoint, ?array $params = null): mixed {
        $url = $this->base_url . $endpoint;
        $method = strtoupper($method);
        
        // Initialize cURL
        $ch = curl_init();
        if (!$ch) {
            throw new Exception('Failed to initialize cURL');
        }

        try {
            // Basic cURL options
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT => 'WHMCS-BotGuard-Module/2.0',
                CURLOPT_HTTPHEADER => $this->default_headers,
                CURLOPT_CUSTOMREQUEST => $method,
            ]);

            // Handle request data for POST/PUT requests
            if ($params !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
                if ($method === 'GET') {
                    $url .= '?' . http_build_query($params);
                    curl_setopt($ch, CURLOPT_URL, $url);
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                    $headers = $this->default_headers;
                    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
            } elseif ($params !== null && $method === 'GET') {
                $url .= '?' . http_build_query($params);
                curl_setopt($ch, CURLOPT_URL, $url);
            }

            // Execute request
            $response = curl_exec($ch);
            
            if ($response === false) {
                $error = curl_error($ch);
                throw new Exception("cURL error: {$error}");
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            // Handle HTTP errors
            if ($http_code >= 400) {
                $error_message = "HTTP {$http_code} error";
                
                // Try to get error details from response
                $decoded_response = json_decode($response);
                if ($decoded_response && isset($decoded_response->message)) {
                    $error_message .= ": {$decoded_response->message}";
                }
                
                throw new Exception($error_message);
            }

            // Decode JSON response
            $decoded_response = json_decode($response);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response: ' . json_last_error_msg());
            }

            return $decoded_response;

        } finally {
            curl_close($ch);
        }
    }

    /**
     * Set custom timeout for API requests.
     *
     * @param int $timeout Timeout in seconds
     * @return void
     */
    public function setTimeout(int $timeout): void {
        if ($timeout <= 0) {
            throw new InvalidArgumentException('Timeout must be greater than 0');
        }
        
        $this->timeout = $timeout;
    }

    /**
     * Get current timeout setting.
     *
     * @return int Current timeout in seconds
     */
    public function getTimeout(): int {
        return $this->timeout;
    }

    /**
     * Set custom base URL for API requests.
     *
     * @param string $base_url Base URL for API
     * @return void
     */
    public function setBaseUrl(string $base_url): void {
        if (empty($base_url)) {
            throw new InvalidArgumentException('Base URL cannot be empty');
        }
        
        $this->base_url = rtrim($base_url, '/');
    }

    /**
     * Get current base URL.
     *
     * @return string Current base URL
     */
    public function getBaseUrl(): string {
        return $this->base_url;
    }
}

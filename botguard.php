<?php
/*
 * botguard.php
 * This file is part of BotGuard WHMCS Module
 *
 * Copyright (c) 2020 Dennis Prochko <wolfsoft@mail.ru>
 * Updated for WHMCS 8.13 compatibility
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . '/lib/botguard_api_v2.php';

/**
 * Define module related meta data.
 * 
 * @return array Module metadata
 */
function botguard_MetaData() {
    return [
        'DisplayName' => 'BotGuard Bot Management',
        'APIVersion' => '1.1',
        'RequiresServer' => false,
        'DefaultNonSSLPort' => '80',
        'DefaultSSLPort' => '443',
        'ServiceSingleSignOnLabel' => 'Login to BotGuard Panel',
        'ListAccountsUniqueIdentifierField' => 'domain',
        'ListAccountsUniqueIdentifierDisplayName' => 'Domain Name',
        'ListAccountsProductField' => 'configoption1',
    ];
}

/**
 * Define product configuration options.
 * 
 * @return array Configuration options
 */
function botguard_ConfigOptions() {
    return [
        'api_key' => [
            'FriendlyName' => 'API Key',
            'Type' => 'text',
            'Size' => '255',
            'Default' => '',
            'Description' => 'The API key assigned to your account at BotGuard',
            'Loader' => '',
        ],
        'primary_server' => [
            'FriendlyName' => 'Primary Server',
            'Type' => 'text',
            'Size' => '255',
            'Default' => '',
            'Description' => 'The address of the primary BotGuard Node',
            'Loader' => '',
        ],
        'secondary_server' => [
            'FriendlyName' => 'Secondary Server',
            'Type' => 'text',
            'Size' => '255',
            'Default' => '',
            'Description' => 'The address of the secondary BotGuard Node',
            'Loader' => '',
        ]
    ];
}

/**
 * Provision a new instance of a service.
 * 
 * @param array $params Module parameters
 * @return string "success" or error message
 */
function botguard_CreateAccount(array $params) {
    try {
        // Validate required configuration options
        if (empty($params['configoption1'])) {
            throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
        }
        $api_key = $params['configoption1'];

        if (empty($params['configoption2'])) {
            throw new Exception('Service was not set up by service provider, "Primary Server" parameter is not defined.');
        }
        $primary_server = $params['configoption2'];

        if (empty($params['configoption3'])) {
            throw new Exception('Service was not set up by service provider, "Secondary Server" parameter is not defined.');
        }
        $secondary_server = $params['configoption3'];

        // Get domain from parameters
        $domain = '';
        if (!empty($params['domain'])) {
            $domain = $params['domain'];
        } elseif (!empty($params['customfields']) && is_array($params['customfields'])) {
            $domain = reset($params['customfields']);
        } else {
            throw new Exception('Domain name was not defined. Please go back and try again.');
        }

        // Validate domain format
        if (!preg_match('/^(?:[a-z0-9](?:[a-z0-9\-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9\-]{0,61}[a-z0-9]$/i', $domain)) {
            throw new Exception('Domain name is incorrect. Please go back and try again.');
        }

        $botguard = new BotGuard($api_key);
        $bg_subaccount = null;

        // Create subaccount if client model is available
        if (isset($params['model']['client'])) {
            $client = $params['model']['client'];
            $bg_subaccount = $botguard->addSubaccount([
                'email' => $client->email,
                'first_name' => $client->firstname,
                'last_name' => $client->lastname,
                'company' => $client->companyname ?? '',
                'country' => $client->country ?? '',
                'address' => trim(($client->address1 ?? '') . ' ' . ($client->postcode ?? '') . ' ' . ($client->city ?? '')),
                'lang' => !empty($client->language) ? strtolower($client->language) : 'en'
            ]);
        }

        // Add domain to BotGuard
        $result = $botguard->addDomain($domain, $bg_subaccount ? $bg_subaccount->id : null);

        // Store additional information using service properties (WHMCS 8.13 standard)
        if (isset($params['model']) && method_exists($params['model'], 'serviceProperties')) {
            $serviceProperties = [];
            
            if ($bg_subaccount && isset($bg_subaccount->id)) {
                $serviceProperties['BotGuard Subaccount ID'] = $bg_subaccount->id;
            }
            
            if (isset($result->id)) {
                $serviceProperties['BotGuard Domain ID'] = $result->id;
            }
            
            if (!empty($serviceProperties)) {
                $params['model']->serviceProperties->save($serviceProperties);
            }
        }

    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Suspend an instance of a product/service.
 * 
 * @param array $params Module parameters
 * @return string "success" or error message
 */
function botguard_SuspendAccount(array $params) {
    try {
        if (empty($params['configoption1'])) {
            throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
        }
        $api_key = $params['configoption1'];

        $domain = '';
        if (!empty($params['domain'])) {
            $domain = $params['domain'];
        } elseif (!empty($params['customfields']) && is_array($params['customfields'])) {
            $domain = reset($params['customfields']);
        } else {
            throw new Exception('Domain name was not defined. Please go back and try again.');
        }

        $botguard = new BotGuard($api_key);
        $botguard->suspendDomain($domain);

    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Un-suspend instance of a product/service.
 * 
 * @param array $params Module parameters
 * @return string "success" or error message
 */
function botguard_UnsuspendAccount(array $params) {
    try {
        if (empty($params['configoption1'])) {
            throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
        }
        $api_key = $params['configoption1'];

        $domain = '';
        if (!empty($params['domain'])) {
            $domain = $params['domain'];
        } elseif (!empty($params['customfields']) && is_array($params['customfields'])) {
            $domain = reset($params['customfields']);
        } else {
            throw new Exception('Domain name was not defined. Please go back and try again.');
        }

        $botguard = new BotGuard($api_key);
        $botguard->unsuspendDomain($domain);

    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Terminate instance of a product/service.
 * 
 * @param array $params Module parameters
 * @return string "success" or error message
 */
function botguard_TerminateAccount(array $params) {
    try {
        if (empty($params['configoption1'])) {
            throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
        }
        $api_key = $params['configoption1'];

        $domain = '';
        if (!empty($params['domain'])) {
            $domain = $params['domain'];
        } elseif (!empty($params['customfields']) && is_array($params['customfields'])) {
            $domain = reset($params['customfields']);
        } else {
            throw new Exception('Domain name was not defined. Please go back and try again.');
        }

        $botguard = new BotGuard($api_key);
        $botguard->deleteDomain($domain);

        // Clean up service properties
        if (isset($params['model']) && method_exists($params['model'], 'serviceProperties')) {
            $params['model']->serviceProperties->save([
                'BotGuard Subaccount ID' => '',
                'BotGuard Domain ID' => ''
            ]);
        }

    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Test connection to the service.
 * 
 * @param array $params Module parameters
 * @return array Connection test results
 */
function botguard_TestConnection(array $params) {
    try {
        if (empty($params['configoption1'])) {
            throw new Exception('API Key is required for connection test.');
        }

        $botguard = new BotGuard($params['configoption1']);
        $domains = $botguard->getDomains();
        
        $success = true;
        $errorMsg = '';
    } catch (Exception $e) {
        $success = false;
        $errorMsg = $e->getMessage();
        
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );
    }

    return [
        'success' => $success,
        'error' => $errorMsg,
    ];
}

/**
 * Client area output logic handling.
 * 
 * @param array $params Module parameters
 * @return array Template data
 */
function botguard_ClientArea(array $params) {
    $lang = 'en';
    
    try {
        if (empty($params['configoption1'])) {
            throw new Exception('BotGuard module is not configured, API key is missing.');
        }

        $botguard = new BotGuard($params['configoption1']);
        $api_key = null;

        // Determine language
        if (isset($params['model']['client'])) {
            $client = $params['model']['client'];
            
            // Get subaccount API key
            $subaccounts = $botguard->getSubaccounts([
                'email' => $client->email
            ]);
            
            if (count($subaccounts) == 1) {
                $api_key = $subaccounts[0]->api_key;
            }

            // Set language based on client preference
            switch ($client->language ?? '') {
                case 'azerbaijani':
                case 'ukrainian':
                case 'russian':
                    $lang = 'ru';
                    break;
                default:
                    $lang = 'en';
            }
        }

        // Get domain
        $domain = '';
        if (!empty($params['domain'])) {
            $domain = $params['domain'];
        } elseif (!empty($params['customfields']) && is_array($params['customfields'])) {
            $domain = reset($params['customfields']);
        }

        // Generate management URLs
        $managementUrls = [];
        if ($api_key && $domain) {
            $managementUrls = [
                'show_stats_url' => $botguard->getStatisticsPageLink($domain, $api_key, $lang),
                'show_events_url' => $botguard->getEventsPageLink($domain, $api_key, $lang),
                'manage_rules_url' => $botguard->getSettingsPageLink($domain, $api_key, $lang)
            ];
        }

        return [
            'tabOverviewModuleOutputTemplate' => "templates/overview.{$lang}.tpl",
            'templateVariables' => array_merge([
                'primary_server' => $params['configoption2'] ?? '',
                'secondary_server' => $params['configoption3'] ?? '',
                'botguard_domain' => $domain,
                'api_available' => !empty($api_key),
                'service_status' => $params['status'] ?? 'Unknown'
            ], $managementUrls),
        ];

    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );
        
        return [
            'tabOverviewModuleOutputTemplate' => "templates/error.{$lang}.tpl",
            'templateVariables' => [
                'error_details' => $e->getMessage(),
            ]
        ];
    }
}

/**
 * Admin custom button array.
 * 
 * @return array Custom buttons for admin area
 */
function botguard_AdminCustomButtonArray() {
    return [
        "Test Connection" => "testConnection",
        "Sync Status" => "syncStatus",
    ];
}

/**
 * Test connection admin function.
 * 
 * @param array $params Module parameters
 * @return string Result message
 */
function botguard_testConnection(array $params) {
    try {
        if (empty($params['configoption1'])) {
            return 'API Key is required.';
        }

        $botguard = new BotGuard($params['configoption1']);
        $domains = $botguard->getDomains();
        
        return 'Connection successful. Found ' . count($domains) . ' domains.';
        
    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );
        
        return 'Connection failed: ' . $e->getMessage();
    }
}

/**
 * Sync status admin function.
 * 
 * @param array $params Module parameters
 * @return string Result message
 */
function botguard_syncStatus(array $params) {
    try {
        if (empty($params['configoption1'])) {
            return 'API Key is required.';
        }

        $domain = '';
        if (!empty($params['domain'])) {
            $domain = $params['domain'];
        } elseif (!empty($params['customfields']) && is_array($params['customfields'])) {
            $domain = reset($params['customfields']);
        } else {
            return 'Domain name not found.';
        }

        $botguard = new BotGuard($params['configoption1']);
        $domainInfo = $botguard->getDomain($domain);
        
        return 'Domain status synced successfully. Status: ' . ($domainInfo->status ?? 'Unknown');
        
    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );
        
        return 'Sync failed: ' . $e->getMessage();
    }
}

/**
 * Single Sign-On function.
 * 
 * @param array $params Module parameters
 * @return array SSO redirect data
 */
function botguard_ServiceSingleSignOn(array $params) {
    try {
        if (empty($params['configoption1'])) {
            throw new Exception('BotGuard module is not configured, API key is missing.');
        }

        $botguard = new BotGuard($params['configoption1']);
        $api_key = null;

        if (isset($params['model']['client'])) {
            $subaccounts = $botguard->getSubaccounts([
                'email' => $params['model']['client']->email
            ]);
            
            if (count($subaccounts) == 1) {
                $api_key = $subaccounts[0]->api_key;
            }
        }

        if (!$api_key) {
            throw new Exception('No API key available for this client.');
        }

        $domain = '';
        if (!empty($params['domain'])) {
            $domain = $params['domain'];
        } elseif (!empty($params['customfields']) && is_array($params['customfields'])) {
            $domain = reset($params['customfields']);
        }

        if (!$domain) {
            throw new Exception('Domain not found for SSO.');
        }

        $lang = 'en';
        if (isset($params['model']['client'])) {
            switch ($params['model']['client']->language ?? '') {
                case 'azerbaijani':
                case 'ukrainian': 
                case 'russian':
                    $lang = 'ru';
                    break;
            }
        }

        return [
            'success' => true,
            'redirectTo' => $botguard->getSettingsPageLink($domain, $api_key, $lang),
        ];

    } catch (Exception $e) {
        logModuleCall(
            'botguard',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return [
            'success' => false,
            'errorMsg' => $e->getMessage(),
        ];
    }
}

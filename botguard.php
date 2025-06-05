<?php
/*
 * botguard.php
 * This file is part of BotGuard WHMCS Module
 *
 * Copyright (c) 2020 Dennis Prochko <wolfsoft@mail.ru>
*/

if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

require_once __DIR__ . '/lib/botguard_api_v2.php';

/**
 * Define module related meta data.
 */
function botguard_MetaData() {
	return [
		'DisplayName' => 'BotGuard Bot Management',
		'APIVersion' => '1.1',
		'RequiresServer' => false
	];
}

/**
 * Define product configuration options.
 */
function botguard_ConfigOptions() {
	return [
		'api_key' => [
			'FriendlyName' => 'API Key',
			'Type' => 'text',
			'Size' => '255',
			'Default' => '',
			'Description' => 'The API key assigned to your account at BotGuard',
		],
		'primary_server' => [
			'FriendlyName' => 'Primary Server',
			'Type' => 'text',
			'Size' => '255',
			'Default' => '',
			'Description' => 'The address of the primary BotGuard Node',
		],
		'secondary_server' => [
			'FriendlyName' => 'Secondary Server',
			'Type' => 'text',
			'Size' => '255',
			'Default' => '',
			'Description' => 'The address of the secondary BotGuard Node',
		]
	];
}

/**
 * Provision a new instance of a service.
 */
function botguard_CreateAccount(array $params) {
	try {
		if (!isset($params['configoption1']) || empty($params['configoption1'])) {
			throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
		} else {
			$api_key = $params['configoption1'];
		}

		if (!isset($params['configoption2']) || empty($params['configoption2'])) {
			throw new Exception('Service was not set up by service provider, "Primary Server" parameter is not defined.');
		} else {
			$primary_server = $params['configoption2'];
		}

		if (!isset($params['configoption3']) || empty($params['configoption3'])) {
			throw new Exception('Service was not set up by service provider, "Secondary Server" parameter is not defined.');
		} else {
			$secondary_server = $params['configoption3'];
		}

		if (isset($params['domain']) && !empty($params['domain'])) {
			$domain = $params['domain'];
		} else {
			if (!isset($params['customfields']) || empty($params['customfields'])) {
				throw new Exception('Domain name was not defined. Please go back and try again.');
			} else {
				$domain = reset($params['customfields']);
			}
		}

		if (preg_match('/^(?:[a-z0-9](?:[a-z0-9\-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9\-]{0,61}[a-z0-9]$/', $domain) !== 1) {
			throw new Exception('Domain name is incorrect. Please go back and try again.');
		}

		$botguard = new BotGuard($api_key);
		$bg_subaccount = NULL;

		if (isset($params['model']['client'])) {
			$bg_subaccount = $botguard->addSubaccount([
				'email' => $params['model']['client']->email,
				'first_name' => $params['model']['client']->firstname,
				'last_name' => $params['model']['client']->lastname,
				'company' => $params['model']['client']->companyname,
				'country' => $params['model']['client']->country,
				'address' => $params['model']['client']->address1 . ' ' . $params['model']['client']->postcode . ' ' . $params['model']['client']->city,
				'lang' => empty($params['model']['client']->language) ? 'en' : strtolower($params['model']['client']->language)
			]);
		}

		$botguard->addDomain($domain, $bg_subaccount->id);

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
 */
function botguard_SuspendAccount(array $params) {
	try {
		if (!isset($params['configoption1']) || empty($params['configoption1'])) {
			throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
		} else {
			$api_key = $params['configoption1'];
		}

		if (isset($params['domain']) && !empty($params['domain'])) {
			$domain = $params['domain'];
		} else {
			if (!isset($params['customfields']) || empty($params['customfields'])) {
				throw new Exception('Domain name was not defined. Please go back and try again.');
			} else {
				$domain = reset($params['customfields']);
			}
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
 */
function botguard_UnsuspendAccount(array $params) {
	try {
		if (!isset($params['configoption1']) || empty($params['configoption1'])) {
			throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
		} else {
			$api_key = $params['configoption1'];
		}

		if (isset($params['domain']) && !empty($params['domain'])) {
			$domain = $params['domain'];
		} else {
			if (!isset($params['customfields']) || empty($params['customfields'])) {
				throw new Exception('Domain name was not defined. Please go back and try again.');
			} else {
				$domain = reset($params['customfields']);
			}
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
 */
function botguard_TerminateAccount(array $params)
{
	try {
		if (!isset($params['configoption1']) || empty($params['configoption1'])) {
			throw new Exception('Service was not set up by service provider, "API Key" parameter is not defined.');
		} else {
			$api_key = $params['configoption1'];
		}

		if (isset($params['domain']) && !empty($params['domain'])) {
			$domain = $params['domain'];
		} else {
			if (!isset($params['customfields']) || empty($params['customfields'])) {
				throw new Exception('Domain name was not defined. Please go back and try again.');
			} else {
				$domain = reset($params['customfields']);
			}
		}

		$botguard = new BotGuard($api_key);
		$botguard->deleteDomain($domain);

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
 * Client area output logic handling.
 */
function botguard_ClientArea(array $params) {
	$lang = 'en';
	try {
		if (!isset($params['configoption1'])) {
			throw new Exception('BotGuard module is not configured, API key is missing.');
		}

		$botguard = new BotGuard($params['configoption1']);
		$api_key = NULL;

		if (isset($params['model']['client'])) {
			$s = $botguard->getSubaccounts([
				'email' => $params['model']['client']->email
			]);
			if (count($s) == 1) {
				$api_key = $s[0]->api_key;
			}

			switch ($params['model']['client']->language) {
				case 'azerbaijani':
				case 'ukranian':
				case 'russian':
					$lang = 'ru';
					break;
				default:
					$lang = 'en';
			}
		}

		if (isset($params['domain']) && !empty($params['domain'])) {
			$d = $params['domain'];
		} else {
			$d = reset($params['customfields']);
		}

		return [
			'templatefile' => "templates/overview.{$lang}.tpl",
			'vars' => [
				'primary_server' => $params['configoption2'],
				'secondary_server' => $params['configoption3'],
				'botguard_domain' => $d,
				'show_stats_url' => $api_key ? $botguard->getStatisticsPageLink($d, $api_key, $lang) : NULL,
				'show_events_url' => $api_key ? $botguard->getEventsPageLink($d, $api_key, $lang) : NULL,
				'manage_rules_url' => $api_key ? $botguard->getSettingsPageLink($d, $api_key, $lang) : NULL
			],
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
			'templatefile' => "templates/error.{$lang}.tpl",
			'vars' => [
				'error_details' => $e->getMessage(),
			]
		];
	}
}

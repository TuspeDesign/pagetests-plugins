<?php

/**
 * Plugin Name: Silky Speed Statistics - Website performance and optimization test tool
 * Plugin URI: https://silkyspeed.com/
 * Text Domain: silkyspeed
 * Domain Path: /languages
 * Description:
 * Version: 0.0.1
 * Requires at least: 6
 * Requires PHP: 7.4
 * Code Name: SSS
 * Author: Timo Anttila
 * Author URI: https://tuspe.com/en
 * License: GPLv2 or later
 *
 * Copyright 2023 Tuspe Design Oy
 */

defined("ABSPATH") || exit;

// Plugin version and folders
const SS_VERSION = '0.0.1';
const SS_FILE = __FILE__;
const SS_DIR = __DIR__ . '/';
const SS_TD = 'silkyspeed';
const SS_API = 'https://silkyspeed.com/api/';

// Class Autoloading
spl_autoload_register(function ($class) {
  $class_file = SS_DIR . 'api/' . $class . '.php';
  if (file_exists($class_file)) {
    require_once $class_file;
  }
});

// Plugin Activation Hook
register_activation_hook(__FILE__, 'silkyspeed_activate');
function silkyspeed_activate()
{
  // Send POST request to external server on activation
  $response = wp_remote_post(SS_API . 'activation-endpoint', array(
    'body' => array(
      'plugin_version' => SS_VERSION,
      // Add any other data you want to send
    ),
  ));

  // Handle the response if needed
  if (is_wp_error($response)) {
    // Handle error
    error_log('Activation request failed: ' . $response->get_error_message());
  } else {
    // Handle success
    $body = wp_remote_retrieve_body($response);
    error_log('Activation request successful. Response: ' . $body);
  }

  // Perform activation tasks, if any
}

// Plugin Deactivation Hook
register_deactivation_hook(__FILE__, 'silkyspeed_deactivate');
function silkyspeed_deactivate()
{
  // Send POST request to external server on deactivation
  $response = wp_remote_post(SS_API . '/deactivation-endpoint', array(
    'body' => array(
      'plugin_version' => SS_VERSION,
      // Add any other data you want to send
    ),
  ));

  // Handle the response if needed
  if (is_wp_error($response)) {
    // Handle error
    error_log('Deactivation request failed: ' . $response->get_error_message());
  } else {
    // Handle success
    $body = wp_remote_retrieve_body($response);
    error_log('Deactivation request successful. Response: ' . $body);
  }

  // Perform deactivation tasks, if any
}

// Initiate the plugin
SilkySpeed::init();

// Create custom plugin settings menu
add_action('admin_menu', 'silkyspeed_menu');
function silkyspeed_menu()
{
  add_menu_page(__('Silky Speed Statistics', 'silkyspeed'), __('Silky Speed Management', 'silkyspeed'), 'manage_options', 'silkyspeed', 'silkyspeed_page');
}

function silkyspeed_page()
{
  // Fetch results from external server and display on the admin page
  $response = wp_remote_get(SS_API . 'results-endpoint');

  // Handle the response if needed
  if (is_wp_error($response)) {
    // Handle error
    echo 'Error fetching results: ' . $response->get_error_message();
  } else {
    // Handle success
    $body = wp_remote_retrieve_body($response);
    echo 'Results from external server: ' . $body;
  }

  // Output your menu page content here
}

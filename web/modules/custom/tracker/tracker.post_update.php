<?php

/**
 * Implements hook_update_N().
 *
 * Enable the Web Service modules.
 */
function tracker_post_update_enable_web_service_modules(&$sandbox) {
  $modules = [
    'serialization',
    'rest',
    'basic_auth',
    'jsonapi',
    'restui',
  ];

  \Drupal::service('module_installer')->install($modules, TRUE);
}

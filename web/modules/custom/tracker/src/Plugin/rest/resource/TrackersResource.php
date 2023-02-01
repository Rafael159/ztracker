<?php

namespace Drupal\tracker\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides Trackers resource.
 *
 * @RestResource(
 *   id = "tracker_resource",
 *   label = @Translation("Trackers resource"),
 *   uri_paths = {
 *     "canonical" = "/api/v0/trackers"
 *   }
 * )
 */
class TrackersResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $response = ['message' => 'Hello, this is a rest service'];
    return new ResourceResponse($response);
  }
}

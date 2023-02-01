<?php

namespace Drupal\tracker\Plugin\rest\resource;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id, $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected DateFormatterInterface $dateFormatter
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    /** @var DateFormatterInterface $dateFormatter */
    $dateFormatter = $container->get('date.formatter');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      (array)$dateFormatter,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('tracker_log')
    );
  }

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    // Hold on for now.
    try {
      $trackers = [
        'user' => '',
        'ticket' => '',
        'description' => '',
        'estimated_time' => '',
        'logged_time' => '',
        'date_closed' => '',
        'complexity' => '',
        'sprint' => '',
        'ticket_type' => '',
        'pull_request' => '',
        'support_ticket' => '',
        'one_code_review' => '',
        'notes' => '',
      ];
      return new ResourceResponse($response);

    } catch (\Exception $exception) {

    }
  }
}

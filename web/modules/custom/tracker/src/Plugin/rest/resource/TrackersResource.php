<?php

namespace Drupal\tracker\Plugin\rest\resource;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\tracker\TrackerManagerService;
use Drupal\user\Entity\User;
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
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected DateFormatterInterface $dateFormatter,
    protected TrackerManagerService $trackerManager
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

    /** @var TrackerManagerService $tracker */
    $tracker = $container->get(TrackerManagerService::SERVICE_ID);

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('tracker'),
      $dateFormatter,
      $tracker,
    );
  }

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $trackers = $this->trackerManager->getTrackers();

    try {
      $result = [];
      foreach ($trackers as $track) {
        $account = $track->get('user_id');
        $user_id = $account->target_id;
        $user = User::load($user_id);

        // All the Tracker item data.
        $trackers = [
          'user' => $user ? $user->get('name')->value : 'User name',
          'track' => $track->id(),
          'ticket' => $track->get('ticket')->value,
          'description' => $track->get('description')->value,
          'estimated_time' => $this->trackerManager->convertsMinutesToHours($track->get('estimated_time')->value),
          'logged_time' => $this->trackerManager->convertsMinutesToHours($track->get('logged_time')->value),
          'date_closed' => $this->trackerManager->formatDate($track->get('date_closed')->value),
          'complexity' => $track->get('complexity')->value,
          'sprint' => $track->get('sprint')->value,
          'ticket_type' => $track->get('ticket_type')->value,
          'pull_request' => $track->get('pull_request')->value,
          'support' => $track->get('support')->value,
          'one_code_review' => $track->get('one_code_review')->value,
          'notes' => $track->get('notes')->value,
        ];
        // Append values to the end of the array.
        array_unshift($result, $trackers);
      }

      $response = new ResourceResponse(['trackers' => $result]);

      foreach ($trackers as $track) {
        $response->addCacheableDependcy($track);
      }

      return $response;
    } catch (\Exception $exception) {
      $this->logger
        ->error($exception->getMessage() . $this->getTraceAsString());

      $response = new ResourceResponse([]);
      foreach ($trackers as $track) {
        $response->addCacheableDependcy($track);
      }
      return $response;
    }
  }
}

<?php
namespace Drupal\tracker;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class TrackerManagerService.
 *
 * Use this for dealing with common action for the Trackers.
 *
 * @package Drupal\tracker
 */
class TrackerManagerService extends TrackerManagerServiceBase {

  /**
   * The service ID.
   */
  const SERVICE_ID = 'tracker.manager.service';

  /**
   * TrackerManagerService constructor.
   *
   * @param EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param Connection $database
   *   The database connection.
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    Connection $database,
  ) {
    parent::__construct($entityTypeManager, $database);
  }

  /**
   * Returns the tracker items.
   *
   * @return Entity\Tracker[]
   */
  public function getTrackers() {
    /** @var \Drupal\tracker\Entity\Tracker[] $tracker */
    return $this->trackers->loadMultiple();
  }

  /**
   * Returns an instance of the service.
   *
   * @return Drupal\tracker\TrackerManagerService
   *   This service's instance.
   */
  public function getService(): TrackerManagerService {
    return \Drupal::service(self::SERVICE_ID);
  }
}

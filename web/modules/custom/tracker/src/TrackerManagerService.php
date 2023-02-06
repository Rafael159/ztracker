<?php
namespace Drupal\tracker;

use DateTime;
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

  /**
   * Formats date string into d/m/Y H:i format.
   *
   * @param $date
   *   The date value that needs to be formatted.
   * @return string
   *   The formatted date.
   */
  public function formatDate($date): string {
    if (!empty($date)) {
      return DateTime::createFromFormat('Y-m-d\TH:i:s', $date)->format('d/m/Y H:i');
    }
  }

  /**
   * Converts minutes to hours.
   *
   * @param $minutes
   *   The minutes to be converted into hours.
   * @return string
   *   Returns the formatted hour.
   */
  public function convertsMinutesToHours($minutes): string {
    if (!empty($minutes)) {
      $sign = $minutes < 0 ? '-' : '';
      $minutes = abs($minutes);
      $hours = floor($minutes / 60);
      $remainingMinutes = $minutes % 60;
      return $sign . $hours . 'h' . $remainingMinutes . 'm';
    }
  }
}

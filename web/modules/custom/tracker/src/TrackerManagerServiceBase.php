<?php

namespace Drupal\tracker;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class TrackerManagerServiceBase.
 *
 * WARNING - Don't declare this class directly, but instead create a service that extends this class.
 *
 * @package Drupal\tracker
 */
abstract class TrackerManagerServiceBase {

  /** @var Connection $database */
  protected $database;

  /** @var \Drupal\Core\Entity\EntityStorageInterface $tracker */
  protected $trackers;

  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    Connection $connection,
  ) {
    $this->database = $connection;
    $this->trackers = $entityTypeManager->getStorage('tracker');
  }

}

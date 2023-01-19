<?php

namespace Drupal\tracker\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an Interface for defining TrackerInterface entities.
 *
 * @ingroup ztracker
 */
interface TrackerInterface extends ContentEntityInterface {

  /**
   * Gets the time needed to finish the task.
   *
   * @return string
   *   Time formatted (e.g 1h30m).
   */
  public function getTimeRequired(): string;

  /**
   * Gets the difference between the estimation and the time used.
   *
   * @return int
   *   The time left or passed of the estimation (timeAvailable - timeUsed).
   */
  public function getTimeLeftOrPassed(): int;

  /**
   * Gets the Tracker timestamp.
   *
   * @return int
   *   Creation timestamp of the Tracker.
   */
  public function getCreatedTime(): int;

  /**
   * Sets the Tracker creation timestamp.
   *
   * @param int $timestamp
   *   The Tracker creation timestamp.
   *
   * @return TrackerInterface
   *   The called Tracker entity.
   */
  public function setCreatedTime($timestamp);

}

<?php

namespace Drupal\tracker;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a class to build a listing of Tracker entities.
 *
 * @ingroup tracker
 */
class TrackerListBuilder extends EntityListBuilder {

  /** @var TrackerManagerService. */
  protected $trackerService;

  public function __construct(
    EntityTypeInterface $entity_type,
    EntityStorageInterface $storage,
    TrackerManagerService $trackerService
  ) {
    parent::__construct($entity_type, $storage);

    $this->trackerService = $trackerService;
  }

  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('tracker.manager.service')
    );
}

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Tracker ID');
    $header['ticket'] = $this->t('Ticket');
    $header['ticket_type'] = $this->t('Type');
    $header['estimated_time'] = $this->t('Estimated time');
    $header['logged_time'] = $this->t('Logged time');
    $header['time_left'] = $this->t('Time left');
    $header['date_closed'] = $this->t('Date closed');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\tracker\Entity\Tracker $entity */
    $row['id'] = $entity->id();

    $ticket = $entity->get('ticket')->value;
    $jira_url = Url::fromUri('https://zoocha.atlassian.net/browse/' . $ticket);
    $row['ticket'] = Link::fromTextAndUrl($ticket, $jira_url);

    $row['ticket_type'] = ucfirst($entity->get('ticket_type')->value);
    $row['estimated_time'] = $this->trackerService->convertsMinutesToHours($entity->get('estimated_time')->value);
    $row['logged_time'] = $this->trackerService->convertsMinutesToHours($entity->get('logged_time')->value);
    $row['time_left'] = $this->trackerService->convertsMinutesToHours($entity->getTimeLeftOrPassed());
    $row['date_closed'] = $this->trackerService->formatDate($entity->getClosedTime()) ?? 'No date found';

    return $row + parent::buildRow($entity);
  }
}

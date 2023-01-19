<?php

namespace Drupal\tracker;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Tracker entities.
 *
 * @ingroup tracker
 */
class TrackerListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Tracker ID');
    $header['ticket'] = $this->t('Ticket');
    $header['ticket_type'] = $this->t('Type');
    $header['estimated_time'] = $this->t('Estimated time');
    $header['logged_time'] = $this->t('Logged time');
    $header['date_closed'] = $this->t('Date closed');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\tracker\Entity\Tracker $entity */
    $row['id'] = $entity->id();

    $ticket_url = Url::fromUri('https://zoocha.atlassian.net/browse/');
    $row['ticket'] = Link::fromTextAndUrl($entity->get('ticket')->value, $ticket_url);

    $row['ticket_type'] = $entity->get('ticket_type')->value;
    $row['estimated_time'] = $entity->get('ticket_type')->value;
    $row['logged_time'] = $entity->get('ticket_type')->value;
    $row['date_closed'] = $entity->get('ticket_type')->value;

    return $row + parent::buildRow($entity);
  }
}

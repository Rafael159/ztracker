<?php

namespace Drupal\tracker\Entity;

use Drupal\Core\Entity\Annotation\ConfigEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Tracker entity.
 *
 * @ConfigEntityType(
 *   id = "tracker",
 *   label = @Translation("Performance Tracker"),
 *   handlers = {
 *     "list_builder" = "Drupal\tracker\TrackerListBuilder",
 *     "form" = {
 *       "default" = "Drupal\tracker\Form\TrackerFormBase",
 *       "add" = "Drupal\tracker\Form\TrackerFormBase",
 *       "edit" = "Drupal\tracker\Form\TrackerFormBase",
 *       "delete" = "Drupal\tracker\Form\TrackerDeleteForm",
 *     },
 *   },
 *   admin_permission = "administer tracker performance",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "ticket" = "ticket",
 *     "description" = "description",
 *     "estimate" = "estimate",
 *     "time_logged" = "time_logged",
 *     "quantity_code_review" = "quantity_code_review",
 *     "date_closed" = "date_closed",
 *     "complexity" = "complexity",
 *     "sprint" = "sprint",
 *     "ticket_type" = "ticket_type",
 *     "pull_request" = "pull_request",
 *     "notes" = "notes",
 *   },
 *   links = {
 *     "canonical" = "/admin/tracker/performance/add",
 *     "edit-form" = "/admin/tracker/performance/edit/{tracker}",
 *     "delete-form" = "/admin/tracker/performance/{tracker}/delete",
 *     "collection" = "/admin/tracker/performance",
 *   },
 *   config_export = {
 *     "id",
 *     "ticket",
 *     "description",
 *     "estimate",
 *     "time_logged",
 *     "quantity_code_review",
 *     "date_closed",
 *     "complexity",
 *     "sprint",
 *     "ticket_type",
 *     "pull_request",
 *     "notes",
 *   }
 * )
 */
class Tracker extends ContentEntityBase implements TrackerInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // The last part of the ticket - i.e ZSD-121.
    $fields['ticket'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Ticket'))
      ->setDescription(t('The link of ticket'))
      ->setSettings([
        'max_length' => 15,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    return $fields;
  }

}

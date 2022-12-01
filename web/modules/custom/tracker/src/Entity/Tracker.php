<?php

namespace Drupal\tracker\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Tracker entity.
 *
 * @ContentEntityType(
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
 *     "langcode" = "langcode",
 *     "ticket" = "ticket",
 *   },
 *   links = {
 *     "canonical" = "/admin/tracker/performance/add",
 *     "edit-form" = "/admin/tracker/performance/edit/{tracker}",
 *     "delete-form" = "/admin/tracker/performance/{tracker}/delete",
 *     "collection" = "/admin/tracker/performance",
 *   },
 * )
 */
class Tracker extends ContentEntityBase implements TrackerInterface {

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Point entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => -1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -1,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    // The last part of the ticket - i.e ZSD-121.
    $fields['ticket'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Ticket'))
      ->setDescription(t('The link to the ticket'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    // Description.
    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDescription(t('Brief description of the ticket.'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 2,
        'rows' => 4,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['estimated_time'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Estimated time'))
      ->setDescription(t('The estimated time for completing the task (in minutes)'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number',
        'weight' => 3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);

    $fields['logged_time'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Logged time'))
      ->setDescription(t('The time needed to finish the ticket (in minutes)'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 4,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setRequired(TRUE);

      $fields['date_closed'] = BaseFieldDefinition::create('datetime')
        ->setLabel(t('Date closed'))
        ->setDescription(t('The date when the ticket was set as closed|live|done.'))
        ->setDisplayOptions('view', [
          'label' => 'visible',
          'type' => 'datetime_default',
          'weight' => 4,
        ])
        ->setDisplayOptions('form', [
          'type' => 'datetime_default',
          'weight' => 4,
        ])
        ->setRequired(TRUE);

    $fields['complexity'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Complexity'))
      ->setDescription(t('The complexity of the ticket.'))
      ->setDefaultValue(1)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 5,
      ]);

    $fields['sprint'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Sprint'))
      ->setDescription(t('The sprint the ticket is part of.'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 6,
      ]);

    $fields['ticket_type'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Ticket type'))
      ->setDescription(t('The issue type (Bug | Task)'))
      ->setSettings([
        'allowed_values' => [
          'bug' => 'bug',
          'task' => 'task',
        ]
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'list_string',
        'weight' => 7,
      ])
      ->setDisplayOptions('form', [
        'type' => 'list_string',
        'weight' => 7,
      ]);

    $fields['pull_request'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Pull request'))
      ->setDescription(t('The link to the PR.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 8,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['support'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Support ticket'))
      ->setDescription(t('Used to count how many tickets are from SD.'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 9,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 9,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['one_code_review'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Just one code review?'))
      ->setDescription(t('Pull Request was approved in the first review.'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);


    $fields['notes'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Notes'))
      ->setDescription(t('Any detail about the ticket, the work done, etc.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 11,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 11,
        'rows' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getTimeRequired(): string
  {
    // TODO: Implement getTimeRequired() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getTimeLeftOrPassed(): int
  {
    // TODO: Implement getTimeLeftOrPassed() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime(): int
  {
    // TODO: Implement getCreatedTime() method.
  }

  /**
   *{@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  public function getChangedTime()
  {
    // TODO: Implement getChangedTime() method.
  }

  public function setChangedTime($timestamp)
  {
    // TODO: Implement setChangedTime() method.
  }

  public function getChangedTimeAcrossTranslations()
  {
    // TODO: Implement getChangedTimeAcrossTranslations() method.
  }

  public function getOwner()
  {
    // TODO: Implement getOwner() method.
  }

  public function setOwner(UserInterface $account)
  {
    // TODO: Implement setOwner() method.
  }

  public function getOwnerId()
  {
    // TODO: Implement getOwnerId() method.
  }

  public function setOwnerId($uid)
  {
    // TODO: Implement setOwnerId() method.
  }
}

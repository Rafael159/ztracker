<?php

namespace Drupal\tracker\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Game edit forms.
 *
 * @ingroup zmashit
 */
class TrackerFormBase extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()
          ->addMessage($this->t('Created a new track item.'));
        break;

      default:
        $this->messenger()
          ->addMessage($this->t('Saved the new track item.'));
    }
    $form_state->setRedirect('entity.tracker.canonical', [
      'tracker' => $entity->id(),
    ]);

    return $status;
  }
}

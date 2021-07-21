<?php
/**
 * @file
 * Contains \Drupal\csv_import\Form\ImportForm.
 */

 namespace Drupal\csv_import\Form;

 use Drupal\Core\Form\FormBase;
 use Drupal\Core\Form\FormStateInterface;
 use Drupal\Core\File\FileSystemInterface;

 class ImportForm extends FormBase {
   /**
    * {@inheritdoc}
    */
  public function getFormId() {
    return 'csv_import_form';
  }

   /**
    * {@inheritdoc}
    */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['csv_import_form_rml'] = [
      '#type' => 'file',
      '#upload_validators' => [ 'file_validate_extensions' => ['csv'] ],
      '#title' => $this->t('Residency Match Locations'),
      '#description' => $this->t('CSV File for Residency Match Locations').': '.\Drupal::state()->get('csv_import_form_rml'),
    ];
    $form['csv_import_form_staff'] = [
      '#type' => 'file',
      '#upload_validators' => [ 'file_validate_extensions' => ['csv'] ],
      '#title' => $this->t('Faculty and Staff'),
      '#description' => $this->t('CSV File for Faculty and Staff').': '.\Drupal::state()->get('csv_import_form_staff'),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );    
    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

 /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $all_files = $this->getRequest()->files->get('files', []);
    if($rml = $all_files['csv_import_form_rml']){
      \Drupal::service('file_system')->move($rml->getRealPath(), "private://csv_import/rml.csv", FileSystemInterface::EXISTS_REPLACE);  
      $messenger = \Drupal::messenger();
      $messenger->addMessage(t('New RML data uploaded, see Structure → Migrations to import the new data.'), $messenger::TYPE_STATUS);
    }
    if($staff = $all_files['csv_import_form_staff']) {
      \Drupal::service('file_system')->move($staff->getRealPath(), "private://csv_import/staff.csv", FileSystemInterface::EXISTS_REPLACE);
      $messenger = \Drupal::messenger();
      $messenger->addMessage(t('New Staff data uploaded, see Structure → Migrations to import the new data.'), $messenger::TYPE_STATUS);
    }
  }  

 }
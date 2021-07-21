<?php

use \Drupal\taxonomy\Entity\Term;
use \Drupal\Core\Entity\EntityInterface;
use \Drupal\Core\Entity\Display\EntityViewDisplayInterface;


function vcom_preprocess_node(&$variables) {
  $node = $variables['elements']['#node'];
  $bundle = $variables['elements']['#node']->bundle();
  $bundleFunc = "vcom_preprocess_node_$bundle";
  if (is_callable($bundleFunc)) {
    $bundleFunc($variables);
  }

  if($node->hasField('field_campus')) {
    $campuses = $node->get('field_campus')->referencedEntities();
    $numCampuses = count($campuses);
    if($numCampuses === 0) {
      $variables['campusclass'] = 'virginia';
    }
    if(count($campuses) > 0) {
      $term = array_shift($campuses);
      $variables['campusclass'] = $term->get('field_classname')->first()->getValue()['value'];
    }
  }

  if($node->hasField('field_date_range')) {
    $dates = $node->get('field_date_range');
    $numDates = $dates->count();
    if($numDates > 0) {
      $firstDate = $dates->value;
      $variables['date_start'] = $firstDate;
    }
  }

  if ($node->bundle() == 'testimonial') {
    if (!empty($node->field_name->first())) {
      $field_name = $node->field_name->first();
      $given = $field_name->given;
      $family = $field_name->family;
      if ($field_name->credentials != '') {
        $credentials = ', ' . $field_name->credentials;
      } else {
        $credentials = '';
      }
      $full_name = $given . ' ' . $family . $credentials;
      $variables['node_title'] = $full_name;
    }
  }
}



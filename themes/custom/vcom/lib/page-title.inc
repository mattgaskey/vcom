<?php

use Drupal\node\Entity\Node;

function vcom_preprocess_page_title(&$variables) {

  if ($node = \Drupal::request()->attributes->get('node')) {
    if ($node instanceof Node) {
      // Check if the field_title_override is not empty
      if ($node->hasField('field_title_override') && $node->field_title_override->value != null) {
        $title_override = $node->field_title_override->value;
        // Set the page title
        $variables['title'] = $title_override;
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
          $variables['title'] = $full_name;
        }
      }
    }
  }
}

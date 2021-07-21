<?php

use \Drupal\Core\Block\BlockPluginInterface;

/**
 * Implements theme_preprocess_block().
 */

function vcom_preprocess_block(&$variables) {
  if ($variables['plugin_id'] === "page_title_block") {
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      // You can get nid and anything else you need from the node object.
      $nid = $node->id();
      // load any field data you need here
      
      // set template variables on $variables['variable_name']
      if ( $node->hasField('field_display_as_hero') ) {
        $variables['display_as_hero'] = $node->field_display_as_hero->value;
      }
      
      // List of pages to hide the h1 except for screen readers
      $hide_h1 = false;
      $path = $node->toUrl()->toString();
      switch(true) {
        case $path == '/news-events':
          $hide_h1 = true;
          break;
        case stripos($path, 'people/') === 1:
          $hide_h1 = true;
          break;
        default:
          $hide_h1 = false;
          break;
      }
      $variables['hide_h1'] = $hide_h1;
      
      // Not sure this is the best way to go about replacing the title with 
      // the name, but it seems to be working. This will use the "default"
      // format for name. -- JHW
      if ($node->bundle() === "person") {
        if ( $node->hasField('field_name') ) {
          $variables['content']['#title'] = $name = $node->field_name->view()['0']['#markup'];
        }
        if ( $node->hasField('field_job_title') ) {
          $position = $node->field_job_title;
        }
      }
    }
  }
  // Helper variable.
  if (isset($variables['content']['#block_content'])) {
    $variables['block'] = $variables['content']['#block_content'];
  }
}
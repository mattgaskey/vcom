<?php 

function vcom_preprocess_paragraph(&$variables) {
  $bundle = $variables['paragraph']->bundle();
  $bundleFunc = "vcom_preprocess_paragraph_$bundle";
  if (is_callable($bundleFunc)) {
    $bundleFunc($variables);
  }
}

/*
 *  Check the parent of a grid of ctas to see if it lives in the
 *  content side of the content and feed paragraph so we can remove
 *  the top slab padding
 */

function vcom_preprocess_paragraph_calls_to_action(&$variables) {
  /* Try to identify feed position based on parent entity variables. Drupal does a 
   *  terrible job letting you walk the data structure for a page.
   */
  $myID = $variables['paragraph']->id();
  $parent = $variables['paragraph']->getParentEntity();

  /* If this is true we are probably using the content and feed paragraph or something like it.
   * We can tell if it is in the content section by whether or not those fields have a target ID
   * that matches ours.
   */
  if ($parent && $parent->hasField('field_grid') && $parent->hasField('field_right_feed')) {
    $ctas = $variables['paragraph']->getParentEntity()->get('field_grid')->getValue()[0]['target_id'];
    if ($ctas === $myID) {
      $variables['inner_slab_variant'] = ' slab--no-top-padding';
    }
  }
}

/*
 * Gathers the information needed to create a feed from the feed paragraph configuration
 * options (and data from the parent if available). 
 */
function vcom_preprocess_paragraph_feed(&$variables) {
  /* Try to identify feed position based on parent entity variables. Drupal does a 
   * terrible job letting you walk the data structure for a page.
   */
  $myID = $variables['paragraph']->id();
  $parent = $variables['paragraph']->getParentEntity();

  /* If this is true we are probably using the paired feed paragraph or something like it.
   * We can tell if it is left or right by whether or not those fields have a target ID
   * that matches ours.
   */
  if ($parent && $parent->hasField('field_left_feed') && $parent->hasField('field_right_feed')) {
    $left = $variables['paragraph']->getParentEntity()->get('field_left_feed')->getValue()[0]['target_id'];
    $right = $variables['paragraph']->getParentEntity()->get('field_right_feed')->getValue()[0]['target_id'];  
    if ($left === $myID) {
      $variables['feed_position'] = 'left';
    } elseif ($right === $myID) {
      $variables['feed_position'] = 'right';
    } else {
      $variables['feed_position'] = 'unknown';
    }
  } elseif ($parent && $parent->hasField('field_right_feed')) {
    $variables['feed_position'] = 'right';  
  } else {
    $variables['feed_position'] = 'full';
  }

  /*
   * Get a comma-delimited list of taxonomy ids
   */
  if(isset($variables['content']['field_categories']['#items']) && $categories = $variables['content']['field_categories']['#items']) {
    $num = $categories->count();
    $catid = [];
    for ($i = 0; $i < $num; $i++) {
      $thisItem = $categories->get($i);
      $catid[] = $thisItem->getValue()['target_id'];
    }
    $variables['view_cat'] = implode(',',$catid);
  } else {
    $variables['view_cat'] = '*';
  }

  /* 
   * Figure out which field content type is requested
   */
  if($content_type = $variables['content']['field_content_type']['#items']) {
    $num = $content_type->count();
    $ct = [];
    for ($i = 0; $i < $num; $i++) {
      $thisItem = $content_type->get($i);
      $ct[] = $thisItem->getValue()['value'];
    }
    $variables['view_type'] = implode(',',$ct);
  } else {
    $variables['view_type'] = '*';
  }
  /* 
   * Now determine the order strategy.
   */
  if($order_strategy = $variables['content']['field_order_strategy']['#items']) {
    $num = $order_strategy->count();
    $os = [];
    for ($i = 0; $i < $num; $i++) {
      $thisItem = $order_strategy->get($i);
      $os[] = $thisItem->getValue()['value'];
    }
    $variables['view_order'] = implode(',',$os);
  }  

  /* 
   * Promoted Content Treatment
   * This describes how to treat content that has the "promoted to front page" flag set
   * If 1, show _only_ promoted content
   * If -1, show _only_ unpromoted content
   * otherwise, show both
   * 
   */
  if($promoted_content = $variables['content']['field_promoted_content']['#items']) {
    $choice = $promoted_content[0]->getValue()['value'];
    switch($choice) {
      case '1':
        $variables['promoted'] = true;
      break;
      case '-1':
        $variables['promoted'] = false;
      break;
      default:
        $variables['promoted'] = null;
    }
  }

  /*
   * Lets figure out the view names
   */

  switch($variables['feed_position']) {
    case 'left':
      $layout = "_card";
    break;
    case 'right':
      $layout = "_col";
    break;
    default:
    $layout = "_full";
  }

  $view_name = $variables['view_order'] . $layout;

  $variables['view_block'] = $view_name;

  $variables['view_set'] = ($variables['view_type'] === 'event') ? 'events_feed' : 'content_feed';
}

/*
 * Gathers the information needed to create campus update block from the campus updates 
 * paragraph configuration options 
 */
use Drupal\taxonomy\Entity\Term;

function vcom_preprocess_paragraph_campus_updates(&$variables) {
  // Get the campus tid
  if($campus = $variables['content']['field_campus']['#items']) {
    $num = $campus->count();
    $cmp = [];
    for ($i = 0; $i < $num; $i++) {
      $thisItem = $campus->get($i);
      $cmp[] = $thisItem->getValue()['target_id'];
    }
    $variables['view_campus'] = implode(',',$cmp);
  }

  // Get the variant
  if($variant = $variables['content']['field_update_variant']['#items']) {
    $num = $variant->count();
    $cmp = [];
    for ($i = 0; $i < $num; $i++) {
      $thisItem = $variant->get($i);
      $tid = $thisItem->getValue()['target_id'];
      $term = Term::load($tid);
      $name = $term->getName();
      $cmp[] = $name;
    }
    $variables['view_variant'] = implode(',',$cmp);
  }
}
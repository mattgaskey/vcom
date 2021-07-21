<?php

use Drupal\node\Entity\Node;

/**
 * Implements hook_theme_suggestions_page_alter().
 */
function vcom_theme_suggestions_page_alter(array &$suggestions, array $variables) {

  $entity_type_manager = \Drupal::entityTypeManager();
  $header_media_id = NULL;

  if ($node = \Drupal::request()->attributes->get('node')) {
    if ($node instanceof Node) {
      // Add content type suggestions.
      array_splice($suggestions, 1, 0, 'page__node__' . $node->getType());
    }
  }

  // Add a View page suggestion.
  if ($view_id = \Drupal::request()->attributes->get('view_id')) {
    array_splice($suggestions, 1, 0, 'page__view');
    array_splice($suggestions, 1, 0, 'page__view__' . $view_id);
  }
}


use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\image\Entity\ImageStyle;
/**
 * Implements hook_preprocess_page().
 */
function vcom_preprocess_page(array &$variables) {
  if ($node = \Drupal::request()->attributes->get('node')) {
    if ($node instanceof Node) {
      // Send the value of display_as_hero boolean to the page template
      if ( $node->hasField('field_display_as_hero') && $node->field_display_as_hero->value) {
        $variables['display_as_hero'] = $node->field_display_as_hero->value;
      }
      // Send the cover image and alt text to the page template
      if ( $node->hasField('field_ambient_video') && $node->field_ambient_video->entity) {
        $ambient_video = $node->field_ambient_video->entity;
        if($ambient_video && $ambient_video->field_media_oembed_video->first()) {
          $value = $ambient_video->field_media_oembed_video->first()->value;
        }
        $variables['ambient_video'] = array_pop(explode('/',$value));
      }
      if ( $node->hasField('field_cover_image') && $node->field_cover_image->entity->field_media_image->entity) {
        $cover_image = $node->field_cover_image->entity->field_media_image->entity->uri->value;
        if ($cover_image) {
          $cover_image_path = ImageStyle::load('big_24x7')->buildUrl($cover_image);
          $variables['cover_image'] = $cover_image_path;
          $cover_image_alt = $node->field_cover_image->entity->name->value;
          $variables['cover_image_alt'] = preg_replace('/-/', ' ', $cover_image_alt);
        }
      }
      // Send the campus theme to the page template
      if ( $node->hasField('field_campus') && !empty($node->field_campus->entity) ) {
        $variables['campus_theme'] = $node->field_campus->entity->field_classname->value;
      }
      // Send the hero description to the page template
      if ( $node->hasField('field_page_description') ) {
        $variables['page_description'] = $node->field_page_description;
      }
      // Send the hero buttons to the page template
      if ( $node->hasField('field_hero_links') ) {
        $links = $node->field_hero_links;
        $rendered_links = [];
        foreach($links as &$link) {
          $build = [];
          $pattern = '/entity:node/';
          $replace = 'node';
          $build['uri'] = file_create_url(preg_replace($pattern, $replace, $link->uri));
          $build['title'] = $link->title;
          array_push($rendered_links, $build);
        }
        $variables['hero_links'] = $rendered_links;
      }
      // Check the url for top-level directories and set the default banner image for the page
      $path = $node->toUrl()->toString();
      $default_image_url = '';
      $default_image_alt = '';
      switch(true) {
        case stripos($path, 'academics') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_sidebar%402x_0.jpg';
          $default_image_alt = 'VCOM Students';
          break;
        case stripos($path, 'admissions') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_directory%402x_0.jpg';
          $default_image_alt = 'VCOM Campus';
          break;
        case stripos($path, 'students') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_sidebar%402x_0.jpg';
          $default_image_alt = 'VCOM Students';
          break;
        case stripos($path, 'research') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_carolinas%402x_0.jpg';
          $default_image_alt = 'VCOM Carolinas Campus';
          break;
        case stripos($path, 'locations/virginia') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_va%402x.jpg';
          $default_image_alt = 'VCOM Virginia Campus';
          break;
        case stripos($path, 'locations/carolinas') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_carolinas%402x_0.jpg';
          $default_image_alt = 'VCOM Carolinas Campus';
          break;
        case stripos($path, 'locations/auburn') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_alabama%402x.jpg';
          $default_image_alt = 'VCOM Auburn Campus';
          break;
        case stripos($path, 'locations/louisiana') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_louisiana%402x_0.jpg';
          $default_image_alt = 'VCOM Louisiana Campus';
          break;
        case stripos($path, 'about') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_va%402x.jpg';
          $default_image_alt = 'VCOM Virginia Campus';
          break;
        case stripos($path, 'alumni') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_directory%402x_0.jpg';
          $default_image_alt = 'VCOM Campus';
          break;
        case stripos($path, 'directory') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_directory%402x_0.jpg';
          $default_image_alt = 'VCOM Campus';
          break;
        case stripos($path, 'faculty') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_directory%402x_0.jpg';
          $default_image_alt = 'VCOM Campus';
          break;
        case stripos($path, 'giving') === 1:
          $default_image_url = '/sites/default/files/2020-03/header_image_sidebar%402x_0.jpg';
          $default_image_alt = 'VCOM Students';
          break;
        default:
          $default_image_url = '/sites/default/files/2020-03/header_image_news%402x_0.jpg';
          $default_image_alt = 'VCOM Campus';
          break;
      }
      $variables['default_image_url'] = $default_image_url;
      $variables['default_image_alt'] = $default_image_alt;

    }
  }
}

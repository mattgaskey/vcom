<?php

namespace Drupal\customizations\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Support Google site search.
 */
class GoogleCustomSearchController extends ControllerBase {

  /**
   * Just set the theme -- everything else is handled in the template.
   */
  public function search() {
    return [
      '#theme' => 'google_custom_search',
      '#cx' => '004028159701946185675:rszghbkhtpm',
      '#query_parameter' => 'keys',
    ];
  }

}

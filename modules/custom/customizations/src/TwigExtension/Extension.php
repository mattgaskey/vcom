<?php

namespace Drupal\customizations\TwigExtension;

use Drupal\Core\Render\Element;
use Drupal\taxonomy\Entity\Term;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\TypedData\Plugin\DataType\ItemList;
use Drupal\Core\Entity\ContentEntityBase;
use Twig_Environment;
use Twig_Extension;

/**
 * Newcity theme support.
 */
class Extension extends Twig_Extension {

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'customizations.twig_extensions';
  }

  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      // compatibility with fractal path
      new \Twig_SimpleFilter('path', [$this, 'path']),      
      // remove HTML comments from markup
      new \Twig_SimpleFilter('nocomment', [$this, 'removeHtmlComments']),
      // an alternate image style (from https://www.drupal.org/files/issues/twig_image_style-2361299-31.patch)
      new \Twig_SimpleFilter('resize', [$this, 'getImageFieldWithStyle']),
      // smart truncate
      new \Twig_SimpleFilter('smarttrim', [$this, 'smartTrim']),
      // get an alias for an entity
      new \Twig_SimpleFilter('alias', [$this, 'entityAlias']),
      // check if a view has any content
      new \Twig_SimpleFilter('has_rows', [$this, 'viewHasRows']),
      // remove empty items from an array
      new \Twig_SimpleFilter('array_filter', 'array_filter'),
      // remove duplications from an array
      new \Twig_SimpleFilter('array_unique', 'array_unique'),
      // run the builder on an entity
      new \Twig_SimpleFilter('entity_view', [$this, 'entityView']),
      // run builder on a field
      new \Twig_SimpleFilter('field_view', [$this, 'fieldView']),
      // html_decode_entities
      new \Twig_SimpleFilter('unescape', [$this, 'unescape']),
      // child elements
      new \Twig_SimpleFilter('children', [$this, 'children']),
      // social media matcher
      new \Twig_SimpleFilter('matchsocial', [$this, 'socialMatcher']),
      // convert a list of rendered links to a link = { url: '', title = '' }
      new \Twig_SimpleFilter('linkobjects', [$this, 'linkObjects']),
      // inject a class in a render array
      new \Twig_SimpleFilter('injectclass', [$this, 'injectClass']),
      // inject an attribute
      new \Twig_SimpleFilter('set_attr', [$this, 'setAttr']),
      // attribute splitter
      new \Twig_SimpleFilter('attr_list', [$this, 'attrList']),
      // data splitter
      new \Twig_SimpleFilter('data_list', [$this, 'dataList']),
      // array reorganization
      new \Twig_SimpleFilter('remap', [$this, 'remap']),
      // remap the item.content format of a field into an array of children
      new \Twig_SimpleFilter('itemcontent', [$this, 'itemContent']),
      // fetch field values from a reference field (like get term names from a reference field)
      new \Twig_SimpleFilter('map_entity_field', [$this, 'mapEntityField']),
      // sort an entity reference by values on the target entity
      new \Twig_SimpleFilter('sort_entity_field', [$this, 'sortEntityField']),
      // striptags, but on a render array key
      new \Twig_SimpleFilter('striptags_key', [$this, 'striptagsKey']),
      // wrap output in a particular tag
      new \Twig_SimpleFilter('tagwrap', [$this, 'tagwrap'], ['is_safe' => ['html']]),
      // change a view mode for an entity
      new \Twig_SimpleFilter('viewmode', [$this, 'changeViewMode']),
      // hide label in a render array
      new \Twig_SimpleFilter('hidelabel', [$this, 'hideLabel']),
      // build a full_html markup build
      new \Twig_SimpleFilter('full_html', [$this, 'fullHtml']),      
      // extract media source
      new \Twig_SimpleFilter('image_src', [$this, 'imageSrc']),
      // Format a date, but only if the date is numeric first
      new \Twig_SimpleFilter('safely_format_date', [$this, 'safelyFormatDate']),
      // Change the view mode of children in an array, useful for cases
      // where there are options on how to view embedded paragraphs.
      new \Twig_SimpleFilter('change_view_modes', [$this, 'changeViewModes']),
      // Convert text to a machine name using the same strategy as the Pomona Fractal implementation
      new \Twig_SimpleFilter('machine_name', [$this, 'machineName'])
    ];
  }

  /**
   * Generates a list of all Twig functions that this extension defines.
   */
  public function getFunctions()
  {
    return [
        // just wrap PHP uniqid()
        new \Twig_SimpleFunction('uniqid', [$this, 'uniqid']),
        // svg injection
        new \Twig_SimpleFunction('svg', [$this, 'svg'], ['is_safe' => ['html']]),
        // xdebug breakpoint (based on https://github.com/ajgarlag/AjglBreakpointTwigExtension)
        new \Twig_SimpleFunction('xdebug', [$this, 'setBreakpoint'], ['needs_environment' => true, 'needs_context' => true]),
        // uri -> url
        new \Twig_SimpleFunction('uritourl', [$this, 'uriToUrl']),
        // load a term from a tid
        new \Twig_SimpleFunction('term_lookup', [$this, 'termLookup']),
        // return a rendered term based on a field in the term
        new \Twig_SimpleFunction('render_term_lookup', [$this, 'renderTermLookup'], ['is_safe' => ['html']]),
        // return an alias for a language
        new \Twig_SimpleFunction('lang_alias', [$this, 'langAlias']),
        // get a video ID for a video_embed_field
        new \Twig_SimpleFunction('video_id', [$this, 'vefVideoId']),
        // figure out whether this is a functional render array
        new \Twig_SimpleFunction('is_render_array', [$this, 'isRenderArray']),
        // rearrange link output for fractal stub
        new \Twig_SimpleFunction('fractal_link', [$this, 'fractalLink']),
        // render SVG file inline
        new \Twig_SimpleFunction('svg_render', [$this, 'svgRender']),
    ];
  }

  /**
   * Fractal compatibility: assume non-FQ paths are relative to the theme root.
   */
  public function path($path) {
    if (strpos($path, '/') === 0) {
      return \Drupal::request()->getSchemeAndHttpHost() . '/' . \Drupal::theme()->getActiveTheme()->getPath() . $path;
    }
    return $path;
  }

  /**
   * Remove HTML comments (from e.g., a field with Twig debug output on)
   */
  public function removeHtmlComments($mixed) {
    if (is_array($mixed)) {
      // ugh, probably a render array
      return $mixed;
    }
    else {
      $output = trim(preg_replace('/<!--(.|\s)*?-->/', '', $mixed));
    }
    return $output;
  }


   /**
   * Function that returns a renderable array of an image field with a given
   * image style.
   *
   * @param $field
   *   Renderable array of a field or maybe a URL
   * @param $style
   *   an image style.
   *
   * @return mixed
   *   a renderable array or NULL if there is no valid input.
   */
  public function getImageFieldWithStyle($field, $style) {
    if (isset($field['#field_type']) && $field['#field_type'] == 'image') {
      $element_children = Element::children($field, TRUE);

      if(!empty($element_children)) {
        foreach ($element_children as $delta => $key) {
          $field[$key]['#image_style'] = $style;
        }
      }
      return $field;
    }

    if (is_string($field)) {
      // assume it's a URL and try to resize
      $stylerenderer = ImageStyle::load($style);
      return $stylerenderer->buildUrl($field);
    }

    return null;
  }


  /**
   * Smart trim, word count
   * assumes a string that's had tags stripped out
   *   e.g., field|render|striptags|smarttrim(n)
   * @param  arr $field
   * @return int        $word_count
   */
  public function smartTrim($field, $word_count = 10) {
    $output = strtok($field, " \n");

    while(--$word_count > 0) {
      $word = strtok(" \n");
      $output .= " " . $word;
    }
    // add a few more to get to the end of the sentence
    while (($word !== false) && (strpos($word, '.') !== strlen($word)-1)) {
      $word = strtok(" \n");
      $output .= " " . $word;
    }

    return $output;
  }

  /**
   * Return an alias for an entity
   * @param  obj $entity
   * @return str        $alias
   */
  public function entityAlias($entity) {
    $url = '';
    if ($entity) {
      $url = $entity->toUrl()->toString();
    }
    return $url;
  }

  /**
   * check if a rendered view has rows (really, just looks for divs)
   * @return bool
   */
  public function viewHasRows($view, $class = 'views-row') {
    $view = $this->removeHtmlComments($view);
    $dom = new \DOMDocument();

    // load HTML but suppress warnings
    $libxml_previous_state = libxml_use_internal_errors(true);
    $dom->loadHTML($view);
    libxml_clear_errors();
    libxml_use_internal_errors($libxml_previous_state);

    $finder = new \DomXPath($dom);
    $rows = $finder->query("//*[contains(@class, '$class')]");
    return ($rows->length >= 1);
  }

  /**
   * build a render array for an entity
   * @return a render array
   */
  public function entityView($entity, $entity_type, $view_mode = 'full') {
    $builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
    return $builder->build($builder->view($entity, $view_mode));
  }

  /**
   * Create a field render array.
   */
  public function fieldView($field, $options = NULL) {
    if (is_null($options)) {
      $options = ['label' => 'hidden'];
    }
    return $field->view($options);
  }


  /**
   * wrap html_entity_decode
   * @return str 
   */
  public function unescape($value) {
    return html_entity_decode($value);
  }

  /**
   * wrap PHP uniqid
   * @return str hash
   */
  public function uniqid() {
    return uniqid();
  }
  
  /**
   * inject an svg from a theme
   * @return str html
   */
  public function svg($filename = null, $opts = array()) {
    if (is_null($filename)) {
        return "No SVG specified.";
    }
    // figure out the current theme path
    $theme_dir = \Drupal::theme()->getActiveTheme()->getPath();
    
    // svg dir defined relative to theme dir
    $dir = isset($opts['dir'])? $opts['dir'] : 'images';
    $svg_dir = realpath($theme_dir . '/' . $dir);
    if ($svg_dir === FALSE) {
      return "SVG directory not found.";
    }
    if (strpos($filename, '.svg')===FALSE) {
      $filename .= '.svg';
    }
    $filename = basename($filename);
    $fn = $svg_dir . '/' . $filename;
    if (!file_exists($fn)) {
      return "SVG file " . $filename . " not found.";
    }
    
    $xml = simplexml_load_file($fn);
    if ($xml === FALSE) {
      return "Unable to read SVG";
    }
    
    $dom = dom_import_simplexml($xml);
    if (!$dom) {
      return "Unable to convert XML to DOM";
    }
    
    // manipulate the output
    foreach ($opts as $k => $v) {
      $dom->setAttribute($k, $v);
    }
    
    // spit out the svg tag
    $output = new \DOMDocument();
    $cloned = $dom->cloneNode(TRUE);
    $output->appendChild($output->importNode($cloned, TRUE));
    
    return $output->saveHTML();
  }

  /**
   * set an xdebug breakpoint (if the extension is available)
   */
  public function setBreakpoint(Twig_Environment $environment, $context)
  {
    if (function_exists('xdebug_break')) {
      $arguments = array_slice(func_get_args(), 2);
      xdebug_break();
    }
  }

  /**
   * convert a URI into a URL
   */
  public function uriToUrl($uri) {
    $url = \Drupal\Core\Url::fromUri($uri);
    return $url->toString();
  }

  
  /**
   * return a full term entity from a tid
   */
  public function termLookup($tid) {
    if (is_array($tid) && count($tid) == 1) {
      $tid = array_shift($tid);
    }
    if (!is_numeric($tid)) {
      // look up by label
      $query = \Drupal::service('entity.query')
        ->get('taxonomy_term')
        ->condition('name', $tid);
      $tids = $query->execute();
      if (count($tids) > 0) {
        $tid = array_shift($tids);
      }
      else {
        $tid = null;
      }
    }
    return Term::load($tid);
  }
  
  /**
   * lookup and render a term in $taxonomy
   * by matching $needle against $field values
   */
  public function renderTermLookup($taxonomy, $field, $needle) {
    $query = \Drupal::service('entity.query')
        ->get('taxonomy_term')
        ->condition('vid', $taxonomy)
        ->condition($field, $needle);
    $tids = $query->execute();
    if ($tids) {
        $term = $this->termLookup($tids);
        $view_builder = \Drupal::service('entity_type.manager')->getViewBuilder('taxonomy_term');
        return $view_builder->view($term);
    }
  }

  /** 
   * from a system path 
   * return an alias for a particular language
   */
   public function langAlias($system_path, $lang = 'en') {
     return \Drupal::service('path.alias_manager')->getAliasByPath($system_path, $lang);
   }

  /**
   * Get the render children of a field
   */
  public function children($variable) {
    if (empty($variable)) {
      return [];
    }
    return array_filter($variable, function($k) { return (is_numeric($k) || (strpos($k, '#')!==0)); }, ARRAY_FILTER_USE_KEY);
  }

  /**
   * Figure out a social media label from a URL
   */
  public function socialMatcher($url) {
    $components = parse_url($url);
    $host_pieces = explode('.', $components['host']);
    return $host_pieces[count($host_pieces) - 2];
  }

  /**
   * convert rendered links to useful objects
   *
   */
  public function linkObjects($links) {
    $objs = [];
    foreach ($links as $link) {
      $objs[] = [
        "title" => $link['#title'],
        "url" => $link['#url']->toString(),
      ];
    }
    return $objs;
  }

  /** 
   * Add a class to a render array
   */
  public function injectClass($build, $class) {
    if (is_array($build)) {
      if (is_string($class)) {
        $build['#attributes']['class'][] = $class;
      }
      if (is_array($class)) {
        $build['#attributes']['class'] += $class;
      }
      // make sure this only applies in this page's context
      $build['#cache']['contexts'][] = 'url.path';
    }
    return $build;
  }

  /**
   * Inject an attribute into a build array.
   */
  public function setAttr($build, $key, $value) {
    if (is_array($build)) {
      $build[$key] = $value;
    }
    return $build;
  }

  /**
   * Attribute splitter.
   *
   * Takes an object describing key/value pairs for attributes on an
   * html tag, then returns a string suitable for spitting into the
   * tag.
   */
  public function attrList($arr, $prefix = '') {
    $str = '';
    if (is_array($arr)) {
      $attributes = [];
      foreach ($arr as $key => $value) {
        $key = str_replace('_', '-', $key);
        $attributes[] = $prefix . $key . '=' . $value;
      }
      if (!empty($attributes)) {
        $str = implode($attributes, ' ');
      }
    }
    return $str;
  }

  /**
   * Data attribute splitter.
   *
   * Returns a bunch of data attributes.
   */
  public function dataList($arr) {
    return $this->attrList($arr, 'data-');
  }

    /**
   * Remap an array to add item root or filter fields.
   */
  public function remap($arr, $item_root = NULL, $fields = []) {
    return array_map(function ($item) use ($item_root, $fields) {
      if (!empty($fields)) {
        if (array_keys($fields) === range(0, count($fields) - 1)) {
          // Numeric array.
          $item = array_intersect_key($item, array_flip($fields));
        }
        else {
          $output = [];
          foreach ($fields as $original_key => $new_key) {
            if (isset($item[$original_key])) {
              $output[$new_key] = $item[$original_key];
            }
          }
          $item = $output;
        }
      }
      foreach ($item as $k => $v) {
        if ($v && is_object($v)) {
          if (method_exists($v, 'toString')) {
            $item[$k] = $v->toString();
          }
        }
      }
      if ($item_root) {
        $item = [
          $item_root => $item,
        ];
      }
      return $item;
    }, $arr);
  }

  /**
   * Helper function for field rendering -- pull out the item.content into an array.
   */
  public function itemcontent($items) {
    return array_map(function ($item) {
      return $item['content'];
    }, $items);
  }

  /**
   * Fetch a field value from an entity reference.
   */
  public function mapEntityField($field, $entity_field_name = 'name', $entity_field_property = 'value') {
    $output = [];
    foreach ($field as $item) {
      $entity = $item->entity;
      if ($entity->hasField($entity_field_name)) {
        $entity_field = $entity->get($entity_field_name);
        $v = $entity_field->first()->getValue($entity_field_property);
        if (isset($v[$entity_field_property])) {
          $output[] = $v[$entity_field_property];
        }
      }
    }
    return $output;
  }

  /**
   * Sort by an entity field.
   */
  public function sortEntityField($field, $entity_field_name = 'name', $entity_field_property = 'value') {
    $order = $this->mapEntityField($field, $entity_field_name, $entity_field_property);
    asort($order);

    if (count($order) != count($field)) {
      // Bail out.
      return $field;
    }

    // Use the order keys to custom-reorder $field.
    return array_map(function ($i) use ($field) {
      return $field[$i];
    }, array_keys($order));
  }

  /**
   * Run striptags on a render array key.
   */
  public function striptagsKey($build, $allowed = "", $key = "#text") {
    if (isset($build[$key])) {
      $build[$key] = strip_tags($build[$key], $allowed);
    }
    return $build;
  }

  /**
   * Takes an object describing key/value pairs for attributes on an
   * html tag, then returns a string suitable for spitting into the
   * tag.
   */
  public function tagwrap($str, $class_name, $tag_name = "div") {
    if (trim($str) == '') {
      return '';
    }
    return '<' . $tag_name . ' class="' . $class_name . '">' . $str . '</' . $tag_name . '>';
  }

  /**
   * Switch out a view mode for a build array.
   */
  public function changeViewMode($build, $view_mode) {
    if (isset($build['#view_mode'])) {
      $build['#view_mode'] = $view_mode;
    }
    foreach ($this->children($build) as $i => $child) {
      if (isset($child['#view_mode'])) {
        $build[$i]['#view_mode'] = $view_mode;
      }
    }
    // Bust the cache.
    $build['#cache']['max-age'] = 0;
    return $build;
  }

  /**
   * Hide a field label.
   */
  public function hideLabel($build) {
    if (isset($build['#label_display'])) {
      $build['#label_display'] = 'hidden';
    }
    return $build;
  }

  /**
   * Create a markup build array.
   */
  public function fullHtml($markup) {
    $markup = (string) $markup;
    return [
      '#type' => 'processed_text',
      '#text' => $markup,
      '#format' => 'full_html',
    ];
  }

  /**
   * get a video ID for a video media item
   * from https://www.drupal.org/project/video_embed_field/issues/2857432
   */
  public function vefVideoId($video_url) {
    if (\Drupal::hasService('video_embed_field.provider_manager')) {
      $providerManager = \Drupal::service('video_embed_field.provider_manager');
      $provider = $providerManager->loadProviderFromInput($video_url);
      if (FALSE !== $provider) {  
        $video_id = $provider->getIdFromInput($video_url);
        if (!empty($video_id)) {
          return $video_id;
        }
      }
    }
    return '';
  }

  /**
   * Try to guess whether this is a build array / simple string?
   *
   * Or a contextual data array?
   */
  public function isRenderArray($build, $need_array = FALSE) {
    if (!$need_array && is_string($build)) {
      return TRUE;
    }
    if (is_array($build)) {
      // Check if any of the array keys start with '#'.
      return (0 < count(array_filter(array_keys($build), function ($key) {
        return (strpos($key, '#') === 0);
      })));
    }
    // ????
    return FALSE;
  }

  /**
   * Convert a Drupal RA to an array of objects keyed "link" for fractal.
   */
  public function fractalLink($build, $classes = [], $simplify = FALSE) {
    $output = [];
    $children = Element::children($build, TRUE);
    foreach ($children as $key) {
      $url = $build[$key]['#url']->toString();
      if (empty($url)) {
        $url = '#';
      }
      $output[] = [
        'link' => [
          'title' => $build[$key]['#title'],
          'url' => $url,
          'variant' => implode(' ', $classes),
        ],
      ];
    }
    if ($simplify && count($output) == 1) {
      $output = $output[0]['link'];
    }
    return $output;
  }

  /**
   * Render an SVG file.
   */
  public function svgRender($file) {
    $type = $file->getMimeType();
    if ($type != 'image/svg+xml') {
      return NULL;
    }
    $uri = $file->getFileUri();

    // Clean up.
    $xml = \simplexml_load_file($uri);
    $dom = \dom_import_simplexml($xml);

    $output = new \DOMDocument();
    $cloned = $dom->cloneNode(TRUE);
    $output->appendChild($output->importNode($cloned, TRUE));

    foreach (['svg', 'g', 'path', 'rect'] as $tag) {
      foreach ($output->getElementsByTagName($tag) as $child) {
        if ($child->hasAttribute('fill')) {
          $child->setAttribute('fill', 'currentColor');
        }
        $child->removeAttribute('class');
        $child->removeAttribute('id');

        if ($child->tagName == 'svg') {
          $child->removeAttribute('height');
          $child->removeAttribute('width');
          $child->setAttribute('fill', 'currentColor');
        }
      }
    }

    $svg = $output->saveHTML();

    // Render array.
    $build = [
      '#type' => 'processed_text',
      '#format' => 'full_html',
      '#text' => $svg,
    ];
    return $build;
  }
    
  // field_target_entity.field_media_image.entity.uri.value
  public function imageSrc($media_item, $style = 'max_1300x1300') {
    #.get('field_media_image')
    return $media_item->get('field_media_image');
  }

  public function safelyFormatDate($possibleDate,$format = "month_day_year") {
    try {
      if(is_numeric($possibleDate)) {
        return \Drupal::service('date.formatter')->format($possibleDate,$format);
      } elseif(is_array($possibleDate)) {
          if(isset($possibleDate[0]['#markup']) && is_numeric($possibleDate[0]['#markup']))
          return \Drupal::service('date.formatter')->format($possibleDate[0]['#markup'],$format);
      } else {
        return $possibleDate;
      }
    } catch (exception $e) {
      return $possibleDate;
    }
  }

  public function changeViewModes($field_data,$view_mode) {
    if (is_array($field_data)) {
      foreach ($field_data as $key => $value) {
        if(is_numeric($key) && isset($value['#view_mode'])) {
          $field_data[$key]['#view_mode'] = $view_mode;
        }
      }
    } 
    return $field_data;
  }

  public function machineName($str) {
    return strtolower(preg_replace('/[^A-Za-z0-9_]+/','-',$str));
  }
  
}

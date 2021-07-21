<?php

namespace Drupal\vcom_imports\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipProcessException;

use Drupal\paragraphs\Entity\Paragraph;

/**
 * Creates a content entity to hold news.
 *
 * @link https://www.drupal.org/node/2771965 Online handbook documentation for substr process plugin @endlink
 *
 * @MigrateProcessPlugin(
 *   id = "save_editorial_paragraph"
 * )
 */


class SaveEditorialParagraph extends ProcessPluginBase {


  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    
    if (empty($value)) {
        throw new MigrateException('Required news content needed');
    }
    
    $paragraph = Paragraph::create([
        'type' => 'body',   // paragraph type machine name
        'field_body' => [   // paragraph's field machine name
            'value' => $value,                  // body field value
            'format' => 'basic_html',         // body text format
        ],
    ]);

    $paragraph->save();

    $return_html = [  // paragraph field attached to node
        [
            'target_id' => $paragraph->id(),
            'target_revision_id' => $paragraph->getRevisionId(),
        ],
    ];
    return $return_html;
  }

}

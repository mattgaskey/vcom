<?php

namespace Drupal\vcom_imports\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\MigrateException;
use Drupal\file\FileInterface;
use Drupal\media\Entity\Media;
use Drupal\Core\Database\Database;
use Drupal\Component\Utility\Unicode;

/**
 * Gets passed a image and/or a youtube video URL.  
 * If Youtube exists, that will be the featured media, otherwise it will be image.
 *
 * @MigrateProcessPlugin(
 *   id = "addmedia"
 * )
 */


class AddMedia extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    
    $media=null;
    $media_source = $this->configuration['media_source'];
    $media_destination = $this->configuration['media_destination'];

    if(is_array($value)) {
      if (isset($value["src"])) {
        $url = $value["src"];
        $alt = (!empty($value["alt"])) ? $value["alt"] : "";
        //\Drupal::logger('vcom_imports')->notice("$url :: $alt");
      } else {
        return null;
      }
    } else {
      $url = $value;  
      $alt = "";
    }

    if(empty($url)) return null;
    $filename = basename($url);

    if (file_prepare_directory($media_destination, FILE_CREATE_DIRECTORY)) {
      if (filter_var($url, FILTER_VALIDATE_URL)) {
        $file_contents = file_get_contents($url);
      } else {
        $file_contents = file_get_contents($media_source . $filename);
      }
      $new_destination = $media_destination . '/' . $filename;
      if (!empty($file_contents)) {
        if($file = file_save_data($file_contents, $new_destination, FILE_EXISTS_REPLACE)) {
          $mimetype = $file->getMimeType();
          if(stristr($mimetype,'image')) {
            $media = Media::create([
              'bundle' => 'image',
              'uid' => \Drupal::currentUser()->id(),
              'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
              'field_media_image' => [
                'target_id' => $file->id(),
                'alt' => $alt
              ]
            ]);
            $media->save();  
          } elseif (stristr($mimetype,'pdf')) {
            $media = Media::create([
              'bundle' => 'pdf',
              'uid' => \Drupal::currentUser()->id(),
              'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
              'field_media_file' => [
                'target_id' => $file->id()
              ]
            ]);
            $media->save();
          }
        }
      }
    }

    return $media;
    
  }

}

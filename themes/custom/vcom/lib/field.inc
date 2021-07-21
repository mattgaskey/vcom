<?php 

function vcom_preprocess_field(&$variables) {
  $variables['view_mode'] = $variables['element']['#view_mode'];
  $bundle = $variables['field_name'];
  //ksm($bundle);
  $bundleFunc = "vcom_preprocess_$bundle";
  if (is_callable($bundleFunc)) {
    $bundleFunc($variables);
  }
}

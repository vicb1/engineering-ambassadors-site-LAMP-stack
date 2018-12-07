<?php

//Set up "path" string methods which provide useful links to certain paths, so that PHP files from anywhere know where to go

$root_path = $_SERVER['DOCUMENT_ROOT'] . "/projects/RPI-Engineering-Ambassadors";

class Path
{
  public static function page() { global $root_path; return $root_path . "/"; }
  public static function model() { global $root_path; return $root_path . "/internal/models/"; }
  public static function helper() { global $root_path; return $root_path . "/internal/helpers/"; }
  public static function lib() { global $root_path; return $root_path . "/internal/libs/"; }
}

class Version
{
  public static $version = "1";
}

/*
//Display the paths for testing

echo "<b>Root path:</b> " . $root_path . "</br>";
echo "<b>Page path:</b> " . Path::page() . "</br>";
echo "<b>Model path:</b> " . Path::model() . "</br>";
echo "<b>Helper path:</b> " . Path::helper()  . "</br>";
echo "<b>Lib path:</b> " . Path::lib() . "</br>";
echo "<b>CSS path:</b> " . Path::css() . "</br>";
echo "<b>JS path:</b> " . Path::js() . "</br>";
echo "<b>Media path:</b> " . Path::media() . "</br>";
echo "<b>SampleCSS path:</b> " . get_css_link("sample.css") . "</br>";
echo "<b>SampleJS path:</b> " . get_js_link("sample.js") . "</br>";
*/

?>
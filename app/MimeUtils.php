<?php

namespace App;

/*
  A helper class for working with MIME types.
  Should be particularly useful for validating file uploads.

  Based on the MIME type list found here:
  https://gist.github.com/tylerlee/53609bff1346cebf8f0a85b6be29a88e

  Last Update: November 2017

  usage:
  $allowed_mimes = new MimeUtils;
  // You can allow all the available types:
  // $allowed_mimes->allow_all();
  // or you can allow just certain subtypes:
  $allowed_mimes->allow('image');
  $allowed_mimes->allow('video');
  // text types include htm, html, css
  $allowed_mimes->allow('text');
  $allowed_mimes->allow('audio');
  // The application types allow potentially problematic types, e.g., pdf, swf, js, class
  // enable it only if necessary.
  $allowed_mimes->allow('application');
  $allowed_mimes->allow('ms-office');
  $allowed_mimes->allow('open-office');
  $allowed_mimes->allow('wordperfect');
  $allowed_mimes->allow('iwork');
  $mime_string = 'mimes:' . $allowed_mimes->get_extensions('string');

*/
class MimeUtils
{
    public $allowed_types = array();

    public static function mime_array(){
      $mime_array = array(
        'image' => array(
          "jpg" => "image/jpeg",
          "jpeg" => "image/jpeg",
          "gif" => "image/gif",
          "png" => "image/png",
          "bmp" => "image/bmp",
          "tiff" => "image/tiff",
          "tif" => "image/tiff",
          "ico" => "image/x-icon",
          "svg" => "image/svg+xml",
        ),
        'video' => array(
          "asf" => "video/x-ms-asf",
          "asx" => "video/x-ms-asf",
          "wmv" => "video/x-ms-wmv",
          "wmx" => "video/x-ms-wmx",
          "wm" => "video/x-ms-wm",
          "avi" => "video/avi",
          "divx" => "video/divx",
          "flv" => "video/x-flv",
          "mov" => "video/quicktime",
          "qt" => "video/quicktime",
          "mpeg" => "video/mpeg",
          "mpg" => "video/mpeg",
          "mpe" => "video/mpeg",
          "mp4" => "video/mp4",
          "m4v" => "video/mp4",
          "ogv" => "video/ogg",
          "webm" => "video/webm",
          "mkv" => "video/x-matroska",
          "3gp" => "video/3gpp",
          "3gpp" => "video/3gpp",
          "3g2" => "video/3gpp2",
          "3gp2" => "video/3gpp2",
        ),
        'text' => array(
          "txt" => "text/plain",
          "csv" => "text/csv",
          "tsv" => "text/tab-separated-values",
          "ics" => "text/calendar",
          "rtx" => "text/richtext",
          "css" => "text/css",
          "htm" => "text/html",
          "html" => "text/html",
          "vtt" => "text/vtt",
          "dfxp" => "application/ttaf+xml",
        ),
        'audio' => array(
          "mp3" => "audio/mpeg",
          "m4a" => "audio/mpeg",
          "m4b" => "audio/mpeg",
          "ra" => "audio/x-realaudio",
          "ram" => "audio/x-realaudio",
          "wav" => "audio/wav",
          "ogg" => "audio/ogg",
          "oga" => "audio/ogg",
          "mid" => "audio/midi",
          "midi" => "audio/midi",
          "wma" => "audio/x-ms-wma",
          "wax" => "audio/x-ms-wax",
          "mka" => "audio/x-matroska",
        ),
        'application' => array(
          "rtf" => "application/rtf",
          "js" => "application/javascript",
          "pdf" => "application/pdf",
          "swf" => "application/x-shockwave-flash",
          "class" => "application/java",
          "tar" => "application/x-tar",
          "zip" => "application/zip",
          "gz" => "application/x-gzip",
          "gzip" => "application/x-gzip",
          "rar" => "application/rar",
          "7z" => "application/x-7z-compressed",
          "psd" => "application/octet-stream",
          "xcf" => "application/octet-stream",
          "ai" => "application/postscript",
          "indd" => "application/x-indesign",
        ),
        'ms-office' => array(
          "doc" => "application/msword",
          "pot" => "application/vnd.ms-powerpoint",
          "pps" => "application/vnd.ms-powerpoint",
          "ppt" => "application/vnd.ms-powerpoint",
          "wri" => "application/vnd.ms-write",
          "xla" => "application/vnd.ms-excel",
          "xls" => "application/vnd.ms-excel",
          "xlt" => "application/vnd.ms-excel",
          "xlw" => "application/vnd.ms-excel",
          "mdb" => "application/vnd.ms-access",
          "mpp" => "application/vnd.ms-project",
          "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
          "docm" => "application/vnd.ms-word.document.macroEnabled.12",
          "dotx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
          "dotm" => "application/vnd.ms-word.template.macroEnabled.12",
          "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          "xlsm" => "application/vnd.ms-excel.sheet.macroEnabled.12",
          "xlsb" => "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
          "xltx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
          "xltm" => "application/vnd.ms-excel.template.macroEnabled.12",
          "xlam" => "application/vnd.ms-excel.addin.macroEnabled.12",
          "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
          "pptm" => "application/vnd.ms-powerpoint.presentation.macroEnabled.12",
          "ppsx" => "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
          "ppsm" => "application/vnd.ms-powerpoint.slideshow.macroEnabled.12",
          "potx" => "application/vnd.openxmlformats-officedocument.presentationml.template",
          "potm" => "application/vnd.ms-powerpoint.template.macroEnabled.12",
          "ppam" => "application/vnd.ms-powerpoint.addin.macroEnabled.12",
          "sldx" => "application/vnd.openxmlformats-officedocument.presentationml.slide",
          "sldm" => "application/vnd.ms-powerpoint.slide.macroEnabled.12",
          "onetoc" => "application/onenote",
          "onetoc2" => "application/onenote",
          "onetmp" => "application/onenote",
          "onepkg" => "application/onenote",
          "oxps" => "application/oxps",
          "xps" => "application/vnd.ms-xpsdocument",
        ),
        'open-office' => array(
          "odt" => "application/vnd.oasis.opendocument.text",
          "odp" => "application/vnd.oasis.opendocument.presentation",
          "ods" => "application/vnd.oasis.opendocument.spreadsheet",
          "odg" => "application/vnd.oasis.opendocument.graphics",
          "odc" => "application/vnd.oasis.opendocument.chart",
          "odb" => "application/vnd.oasis.opendocument.database",
          "odf" => "application/vnd.oasis.opendocument.formula",
        ),
        'wordperfect' => array(
          "wp" => "application/wordperfect",
          "wpd" => "application/wordperfect",
        ),
        'iwork' => array(
          "key" => "application/vnd.apple.keynote",
          "numbers" => "application/vnd.apple.numbers",
          "pages" => "application/vnd.apple.pages",
        ),
      );
      return $mime_array;
    }

    public static function available_filters(){
      $all_types = self::mime_array();
      $output = array();
      foreach ($all_types as $key => $val){
        $output[] = $key;
      }
      return $output;
    }

    public function allow($key){
      if ($key == 'all'){
        $this->allow_all();
      } else {
        $all_types = self::mime_array();
        $this->allowed_types = array_merge($this->allowed_types, $all_types[$key]);
      }
    }

    public function allow_all(){
      $all_types = self::mime_array();
      $types = self::available_filters();
      foreach ( $types as $i => $key ){
        $this->allow($key);
      }
    }

    public function get_types($format = 'string'){
      switch ($format){
        case 'string':
          $output = '';
          foreach ($this->allowed_types as $ext => $type){
            $output .= $type . ',';
          }
          $output = rtrim($output, ',');
          return $output;
          break;
        case 'array':
          $output = array();
          foreach ($this->allowed_types as $ext => $type){
            $output[] = $type;
          }
          return $output;
          break;
        case 'json':
          $output = array();
          foreach ($this->allowed_types as $ext => $type){
            $output[] = $type;
          }
          return json_encode($output);
          break;
      }
    }

    public function get_extensions($format = 'string'){
      switch ($format){
        case 'string':
          $output = '';
          foreach ($this->allowed_types as $ext => $type){
            $output .= $ext . ',';
          }
          $output = rtrim($output, ',');
          return $output;
          break;
        case 'array':
          $output = array();
          foreach ($this->allowed_types as $ext => $type){
            $output[] = $ext;
          }
          return $output;
          break;
        case 'json':
          $output = array();
          foreach ($this->allowed_types as $ext => $type){
            $output[] = $ext;
          }
          return json_encode($output);
          break;
      }
    }

}

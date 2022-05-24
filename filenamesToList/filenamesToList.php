#!/usr/bin/php
<?php

function getOutputType($fileName) {
  # TODO
  // PNGLIST
  return "JPEGLIST";
}

function getHeader($type, $frameRate, $x, $y, $EOL) {
  return "$type$EOL".
  "# First line is always $type$EOL".
  "# Frame rate:$EOL".
  "$frameRate.000000$EOL".
  "# Width:$EOL".
  "$x$EOL".
  "# Height:$EOL".
  "$y$EOL".
  "# List of image files follows$EOL";
}

function getResolution($fileName) {
  $resource = new Imagick($fileName);
  return $resource->getImageGeometry();
}

function getOutputFileList($files, $framesPerFile, $repeat) {
  $output = array();
  
  for ($r = 0; $r < $repeat; $r++) {
    foreach ($files as $file) {
      for ($i = 0; $i < $framesPerFile; $i++) {
        $output[] = "./".$file;
      }
    }
  }
  
  return $output;
}

$EOL = "\n";
if (count($argv)<4) {
  echo "I expected more :($EOL".
  "Syntax: {$argv[0]} destinationFile.jpgs framesPerFile file1.jpg [file2.jpg [file3.jpg [...]]]".
  "Eg: out.jpgs 5 file1.jpg file2.jpg file3.jpg file4.jpg file5.jpg";
  
  exit (1);
}

// Get which files we will be referencing.
$srcFiles = array();
for ($i = 3; $i < $argc; $i++) {
  $srcFiles[] = $argv[$i];
}

// Misc
$resolution = getResolution($argv[3]); // Use the first file in the list to determine the resolution.
$frameRate = 25;
$framesPerFile = $argv[2];
$repeat = 1000;

// The destination file.
$dstFile = $argv[1];
$dstType = getOutputType($dstFile);
$header = getHeader($dstType, $frameRate, $resolution['width'], $resolution['height'], $EOL);

// Assemble the output.
$outputFileList = getOutputFileList($srcFiles, $framesPerFile, $repeat);
$output = $header.implode($EOL, $outputFileList)."\n";

file_put_contents($dstFile, $output);

?>

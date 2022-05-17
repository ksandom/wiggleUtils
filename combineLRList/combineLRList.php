#!/usr/bin/php
<?php
/* This has been written in a rush, but should be modifiable to your needs. Apologies for quality of the code.
 * The first file is assumed to be equal, or longer to all other files.
 * For the sake of writing this quickly, I'm simply loading all lines in to memory. But if larger files ever need to be loaded, we could instead open file handles, and read each file line by line. */

// Type of file input. Eg pngs, jpgs.
$fileType="pngs";

// Files to load.
$inFiles=array("wiggle-L.".$fileType, "wiggle-R.".$fileType);

// How many frames to take from a file, before switching to the next file. The files are advanced together. So frames not taken from a given file are simply skipped.
$framesPerTurn=5;

// File to create.
$outFile="wiggle-combined-$framesPerTurn.".$fileType;

// When we see this line, we assume that we are finishing the header.
$beginLine="# List of image files follows";


// Line ending character(s).
$lineEnding="\n";

// --- End of settings ---


$state="header";
$filesLines=array();
$firstFileLength=0;
$activeFile=0;
$out=array();
$turnPosition=0;
$howManyFiles=count($inFiles);

// Load files into memory.
foreach ($inFiles as $inFile) {
  $data=file_get_contents($inFile);
  $lines=explode($lineEnding, $data);
  $filesLines[]=$lines;
  $length=count($lines);
  
  if (!$firstFileLength) {
    $firstFileLength=$length;
  }
  
  echo "$inFile has $length lines.\n";
}

// Iterate through lines.
for ($lineNumber=0; $lineNumber<$firstFileLength; $lineNumber++) {
  $line=trim($filesLines[$activeFile][$lineNumber]);
  $out[]=$line;
  
  if ($state == 'header') {
    if ($line==$beginLine) {
        $state="normal";
    }
  } else {
    $turnPosition++;
    if ($turnPosition>=$framesPerTurn) {
      $turnPosition=0;
      $activeFile++;
      $activeFile=$activeFile%$howManyFiles;
    }
  }
}

// Write it out.
$data=implode($lineEnding, $out);
file_put_contents($outFile, $data);
?>

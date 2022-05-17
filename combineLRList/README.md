# combineLRList.php

A really simple script for combining to file lists (eg .pngs or .jpgs) together to create a wiggle gram by alternating which stream is being looked at.

For now, the settings are hard-coded in the script, but are easy to change. In particular:

```php
// Type of file input. Eg pngs, jpgs.
$fileType="pngs";

// Files to load.
$inFiles=array("wiggle-L.".$fileType, "wiggle-R.".$fileType);

// How many frames to take from a file, before switching to the next file. The files are advanced together. So frames not taken from a given file are simply skipped.
$framesPerTurn=5;

// File to create.
$outFile="wiggle-combined.".$fileType;
```

This says that:

* We'll load wiggle-L.pngs and wiggle-R.pngs.
* And take 5 frames from one of them at a time.
* And send it to wiggle-combined.pngs.

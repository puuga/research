<?php
  // open the "output" stream
  // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
  $f = fopen('php://output', 'w');

  $array = json_decode($_POST["json"]);
  foreach ($array as $line) {
    fputcsv($f, $line);
  }

  // rewrind the "file" with the csv lines
  fseek($f, 0);
  // tell the browser it's going to be a csv file
  header('Content-Type: application/csv');
  // tell the browser we want to save it instead of displaying it
  header('Content-Disposition: attachement; filename="export.csv";');
  // make php send the generated csv lines to the browser
  fpassthru($f);
?>

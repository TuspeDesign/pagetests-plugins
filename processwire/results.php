<?php

namespace ProcessWire;

// Include the SilkySpeed module
$module = $modules->getModule('SilkySpeed');

// Fetch results from external server
$results = $module->fetchResultsFromExternalServer();

// Output the results
if (isset($results['error'])) {
  echo 'Error fetching results: ' . $results['error'];
} else {
  echo 'Results from external server: ' . print_r($results, true);
}

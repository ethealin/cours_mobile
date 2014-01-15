<?php

$f3->require('framework/base.php');
$f3->set('QUIET',TRUE);  // do not show output of the active route
$f3->mock('PUT /v1/subscribe');  // set the route that f3 will run
$f3->run();  // run the route
// run tests using expect() as shown above
// ...
$f3->set('QUIET',FALSE); // allow test results to be shown later
$f3->clear('ERROR');  // clear any errors

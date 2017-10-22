<?php

$pattern = sprintf('%s/integration/*.yml', __DIR__);
foreach (glob($pattern) as $filename) {
    $loader->import($filename);
}

<?php

$pattern = sprintf('%s/services/*.yml', __DIR__);
foreach (glob($pattern) as $filename) {
    $loader->import($filename);
}

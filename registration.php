<?php
/**
 * Magenuts
 *
 * This source file is subject to the Magenuts Software License, which is available at https://magenuts.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magenuts.com for more information.
 *
 * @category  Magenuts
 * @package   magenuts/module-core
 * @version   1.0.0
 * @copyright Copyright (C) 2020 Magenuts (https://magenuts.com/)
 */


$path = __DIR__;
$ds = DIRECTORY_SEPARATOR;
if (strpos($path, 'app'.$ds.'code'.$ds.'Magenuts') === false) {
    $basePath = dirname(dirname(dirname(__DIR__)));
} else {
    $basePath = dirname(dirname(dirname(dirname(__DIR__))));
}
$registration = $basePath.$ds.'vendor'.$ds.'magenuts'.$ds.'module-core'.$ds.'src'.$ds.'Core'.$ds.
    'registration.php';
if (file_exists($registration)) {
    # module was already installed
    return;
}
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Magenuts_Core',
    __DIR__
);

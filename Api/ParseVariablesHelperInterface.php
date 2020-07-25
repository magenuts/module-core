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


namespace Magenuts\Core\Api;

use Magento\Framework\DataObject;

interface ParseVariablesHelperInterface
{
    /**
     * Parse string.
     * [product_name][, model: {product_model}!] [product_nonexists]  [buy it {product_nonexists} !]
     *
     * @param string   $str
     * @param array    $objects
     * @param array    $additional
     * @param bool|int $storeId
     * @return string
     */
    public function parse($str, $objects, $additional = [], $storeId = false);
}

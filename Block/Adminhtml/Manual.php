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



namespace Magenuts\Core\Block\Adminhtml;

use Magento\Backend\Block\Template;

/**
 * @method $this setTitle($value)
 * @method string getTitle()
 *
 * @method $this setManualUrl($value)
 * @method string getManualUrl()
 *
 * @method $this setPosition($value)
 * @method string getPosition()
 */
class Manual extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Magenuts_Core::manual.phtml';
}
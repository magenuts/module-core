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


namespace Magenuts\Core\Block;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\Template;
use Magenuts\Core\Model\Config;

class FontAwesome extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     * @param Context $context
     */
    public function __construct(
        Config $config,
        Context $context
    ) {
        $this->config = $config;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function _toHtml()
    {
        if ($this->config->isIncludeFontAwesome()) {
            return parent::_toHtml();
        }
        return '';
    }
}

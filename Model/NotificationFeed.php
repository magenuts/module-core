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



namespace Magenuts\Core\Model;

use Magento\AdminNotification\Model\Feed;

class NotificationFeed extends Feed
{
    /**
     * @var string
     */
    protected $feedUrl;

    /**
     * {@inheritdoc}
     */
    public function getFeedUrl()
    {
        if ($this->feedUrl === null) {
            $this->feedUrl = 'https://magenuts.com/blog/category/magento-2-feed/feed/';
        }

        return $this->feedUrl;
    }
}

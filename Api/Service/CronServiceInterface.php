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




namespace Magenuts\Core\Api\Service;


interface CronServiceInterface
{
    /**
     * Check if cron job is exists db table and executed less 6 hours ago
     * 
     * @param array $jobCodes
     * @return bool
     */
    public function isCronRunning(array $jobCodes = []);
}
<?php
/**
 * Catgento
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs.
 *
 * @category   Catgento
 * @package    Catgento_AdminActivity
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @license    https://kiwicommerce.co.uk/magento2-extension-license/
 */
namespace Catgento\AdminActivity\Api\Data;

/**
 * Interface LogSearchResultsInterface
 * @package Catgento\EnhancedSMTP\Api\Data
 */
interface ActivitySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get admin activity list.
     * @api
     * @return \Catgento\AdminActivity\Model\Activity[]
     */
    public function getItems();

    /**
     * Set admin activity list.
     * @api
     * @param \Catgento\AdminActivity\Model\Activity[] $items
     * @return $this
     */
    public function setItems(array $items);
}

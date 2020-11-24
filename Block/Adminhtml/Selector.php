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
namespace Catgento\AdminActivity\Block\Adminhtml;

/**
 * Class Selector
 * @package Catgento\AdminActivity\Block\Adminhtml
 */
class Selector extends \Magento\Backend\Block\Template
{
    /**
     * Revert Activity Log action URL
     * @return string
     */
    public function getRevertUrl()
    {
        return $this->getUrl('adminactivity/activity/revert');
    }
}

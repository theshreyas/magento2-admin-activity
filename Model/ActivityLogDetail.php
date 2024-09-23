<?php
/**
 * Catgento
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs.
 *
 * @category   Catgento
 * @package    Catgento_AdminActivity
 */
namespace Catgento\AdminActivity\Model;

use \Magento\Framework\Model\AbstractModel;

class ActivityLogDetail extends AbstractModel
{
    /**
     * @var string
     */
    public const ACTIVITYLOGDETAIL_ID = 'entity_id'; // We define the id fieldname

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Catgento\AdminActivity\Model\ResourceModel\ActivityLogDetail::class);
    }
}

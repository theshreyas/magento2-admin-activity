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
namespace Catgento\AdminActivity\Model\Activity;

use \Magento\Framework\Model\AbstractModel;

class Status extends AbstractModel
{
    /**
     * @var Int
     */
    public const ACTIVITY_NONE = 0;

    /**
     * @var Int
     */
    public const ACTIVITY_REVERTABLE = 1;

    /**
     * @var Int
     */
    public const ACTIVITY_REVERT_SUCCESS = 2;

    /**
     * @var Int
     */
    public const ACTIVITY_FAIL = 3;

    /**
     * @var \Catgento\AdminActivity\Model\ActivityFactory
     */
    public $activityFactory;

    /**
     * Status constructor.
     * @param \Catgento\AdminActivity\Model\ActivityFactory $activityFactory
     */
    public function __construct(
        \Catgento\AdminActivity\Model\ActivityFactory $activityFactory
    ) {
        $this->activityFactory = $activityFactory;
    }

    /**
     * Set success revert status
     * @param $activityId
     * @return void
     */
    public function markSuccess($activityId)
    {
        $activityModel = $this->activityFactory->create()->load($activityId);
        $activityModel->setIsRevertable(self::ACTIVITY_REVERT_SUCCESS);
        $activityModel->save();
    }

    /**
     * Set fail revert status
     * @param $activityId
     * @return void
     */
    public function markFail($activityId)
    {
        $activityModel = $this->activityFactory->create()->load($activityId);
        $activityModel->setIsRevertable(self::ACTIVITY_FAIL);
        $activityModel->save();
    }
}

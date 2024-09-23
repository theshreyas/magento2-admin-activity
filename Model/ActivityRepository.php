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

use Catgento\AdminActivity\Model\Activity\SystemConfig;

class ActivityRepository implements \Catgento\AdminActivity\Api\ActivityRepositoryInterface
{
    /**
     * @var string
     */
    public const THEME_MODULE = 'Themes';

    /**
     * @var string
     */
    public const QTY_FIELD = 'qty';

    /**
     * ActivityRepository constructor.
     *
     * @param ActivityFactory $activityFactory
     * @param ResourceModel\Activity\CollectionFactory $collectionFactory
     * @param ActivityLogDetailFactory $activityLogDetailFactory
     * @param ActivityLogFactory $activityLogFactory
     * @param ResourceModel\ActivityLog\CollectionFactory $logCollectionFactory
     * @param SystemConfig $systemConfig
     * @param Activity\ThemeConfig $themeConfig
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        public \Catgento\AdminActivity\Model\ActivityFactory $activityFactory,
        public \Catgento\AdminActivity\Model\ResourceModel\Activity\CollectionFactory $collectionFactory,
        public \Catgento\AdminActivity\Model\ActivityLogDetailFactory $activityLogDetailFactory,
        public \Catgento\AdminActivity\Model\ActivityLogFactory $activityLogFactory,
        public \Catgento\AdminActivity\Model\ResourceModel\ActivityLog\CollectionFactory $logCollectionFactory,
        public \Catgento\AdminActivity\Model\Activity\SystemConfig $systemConfig,
        public \Catgento\AdminActivity\Model\Activity\ThemeConfig $themeConfig,
        public \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
    }

    /**
     * Array of protected fields
     *
     * @return array
     */
    public function protectedFields()
    {
        return [
            'password'
        ];
    }

    /**
     * Get all admin activity data
     *
     * @return ResourceModel\Activity\Collection
     */
    public function getList()
    {
        $collection = $this->collectionFactory->create();
        return $collection;
    }

    /**
     * Get all admin activity data before date
     *
     * @param $endDate
     * @return $this
     */
    public function getListBeforeDate($endDate)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToSelect('entity_id')
            ->addFieldToFilter('created_at', ["lteq" => $endDate]);
        return $collection;
    }

    /**
     * Remove activity log entry
     *
     * @param $activityId
     * @return void
     */
    public function deleteActivityById($activityId)
    {
        $model = $this->activityFactory->create();
        $model->load($activityId);
        $model->delete();
    }

    /**
     * Get all admin activity detail by activity id
     *
     * @param $activityId
     * @return $this
     */
    public function getActivityDetail($activityId)
    {
        $data  = $this->activityLogDetailFactory->create()
            ->load($activityId, 'activity_id');
        return $data;
    }
    
    /**
     * Get all admin activity log by activity id
     *
     * @param $activityId
     * @return $this
     */
    public function getActivityLog($activityId)
    {
        $collection = $this->logCollectionFactory->create()
            ->addFieldToFilter('activity_id', ["eq" => $activityId]);
        return $collection;
    }

    /**
     * Get method name
     *
     * @param $field
     * @return string
     */
    public function getMethodName($field)
    {
        return implode(
            '',
            array_map(
                "ucfirst",
                array_map(
                    "strtolower",
                    explode('_', $field)
                )
            )
        );
    }

    /**
     * Revert last changes made in module
     *
     * @param $activity
     * @return bool
     */
    public function revertActivity($activity)
    {
        $logData = $this->getActivityLog($activity->getId());
        $detailModel = $this->getActivityDetail($activity->getId());

        if (\Catgento\AdminActivity\Helper\Data::isWildCardModel($detailModel->getModelClass())) {
            if ($activity->getModule()==self::THEME_MODULE) {
                return $this->themeConfig->revertData($logData, $activity->getStoreId(), $activity->getScope());
            }
            return $this->systemConfig->revertData($logData, $activity->getStoreId());
        }

        $model = $this->objectManager->get($detailModel->getModelClass())
            ->setStoreId($activity->getStoreId())
            ->setScope($activity->getScope())
            ->load($detailModel->getItemId());

        if ($model->getId()) {
            foreach ($logData as $log) {
                if ($this->isFieldProtected($log->getFieldName())) {
                    continue;
                }
                if ($log->getFieldName()==self::QTY_FIELD) {
                    $model-> setStockData(['qty' => $log->getOldValue()]);
                }
                $method = 'set'.$this->getMethodName($log->getFieldName());
                $model->{$method}($log->getOldValue());
            }

            $model->setStoreId($activity->getStoreId());
            $model->save();
            return true;
        }
        return false;
    }

    /**
     * Get old data for system config module
     *
     * @param $model
     * @return bool
     */
    public function getOldData($model)
    {
        if (\Catgento\AdminActivity\Helper\Data::isWildCardModel($model)) {
            return $this->systemConfig->getOldData($model);
        }
        $data = $this->objectManager->get(get_class($model))->load($model->getId());
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * Get admin activity by id
     *
     * @param $activityId
     * @return $this
     */
    public function getActivityById($activityId)
    {
        return $this->activityFactory->create()->load($activityId);
    }

    /**
     * Check field is protected or not
     *
     * @param $fieldName
     * @return bool
     */
    public function isFieldProtected($fieldName)
    {
        $fieldArray = $this->protectedFields();
        if (in_array($fieldName, $fieldArray)) {
            return true;
        }
        return false;
    }
}

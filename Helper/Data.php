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
namespace Catgento\AdminActivity\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Data
 * @package Catgento\AdminActivity\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var string
     */
    const ACTIVITY_ENABLE = 'admin/general_admin_activity/enable';

    /**
     * @var string
     */
    const LOGIN_ACTIVITY_ENABLE = 'admin/general_admin_activity/login_activity';

    /**
     * @var string
     */
    const PAGE_VISIT_ENABLE = 'admin/general_admin_activity/page_visit';

    /**
     * @var string
     */
    const CLEAR_LOG_DAYS = 'admin/general_admin_activity/clearlog';

    /**
     * @var string
     */
    const MODULE_ORDER = 'admin/general_admin_activity/order';

    /**
     * @var string
     */
    const MODULE_PRODUCT = 'admin/general_admin_activity/product';

    /**
     * @var string
     */
    const MODULE_CATEGORY = 'admin/general_admin_activity/category';

    /**
     * @var string
     */
    const MODULE_CUSTOMER = 'admin/general_admin_activity/customer';

    /**
     * @var string
     */
    const MODULE_PROMOTION = 'admin/general_admin_activity/promotion';

    /**
     * @var string
     */
    const MODULE_EMAIL = 'admin/general_admin_activity/email';

    /**
     * @var string
     */
    const MODULE_PAGE = 'admin/general_admin_activity/page';

    /**
     * @var string
     */
    const MODULE_BLOCK = 'admin/general_admin_activity/block';

    /**
     * @var string
     */
    const MODULE_WIDGET = 'admin/general_admin_activity/widget';

    /**
     * @var string
     */
    const MODULE_THEME = 'admin/general_admin_activity/theme';

    /**
     * @var string
     */
    const MODULE_SYSTEM_CONFIG = 'admin/general_admin_activity/system_config';

    /**
     * @var string
     */
    const MODULE_ATTRIBUTE = 'admin/general_admin_activity/attibute';

    /**
     * @var string
     */
    const MODULE_ADMIN_USER = 'admin/general_admin_activity/admin_user';

    /**
     * @var string
     */
    const MODULE_SEO = 'admin/general_admin_activity/seo';

    /**
     * @var \Catgento\AdminActivity\Model\Config
     */
    public $config;

    /**
     * @var array
     */
    public static $wildcardModels = [
        \Magento\Framework\App\Config\Value\Interceptor::class
    ];

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Catgento\AdminActivity\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Catgento\AdminActivity\Model\Config $config
    ) {
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Check and return status of module
     * @return bool
     */
    public function isEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1') {
            return true;
        }

        return false;
    }

    /**
     * Check and return status for login activity
     * @return bool
     */
    public function isLoginEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $loginStatus = $this->scopeConfig
            ->isSetFlag(self::LOGIN_ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1' && $loginStatus == '1') {
            return true;
        }

        return false;
    }

    /**
     * Check and return status for page visit history
     * @return bool
     */
    public function isPageVisitEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $pageVisitStatus = $this->scopeConfig
            ->isSetFlag(self::PAGE_VISIT_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1' && $pageVisitStatus == '1') {
            return true;
        }

        return false;
    }

    /**
     * Get value of system config from path
     * @param $path
     * @return bool
     */
    public function getConfigValue($path)
    {
        $moduleValue = $this->scopeConfig->getValue(
            constant(
                'self::'
                . $path
            ),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        if ($moduleValue) {
            return $moduleValue;
        }
        return false;
    }

    /**
     * Get translated label by action name
     * @param string $action
     * @return string
     */
    public function getActionTranslatedLabel($action)
    {
        return $this->config->getActionLabel($action);
    }

    /**
     * Get all actions
     * @return array
     */
    public function getAllActions()
    {
        return $this->config->getActions();
    }

    /**
     * Get activity module name
     * @return bool
     */
    public function getActivityModuleName($module)
    {
        return $this->config->getActivityModuleName($module);
    }

    /**
     * Get module name is valid or not
     * @param $model
     * @return bool
     */
    public static function isWildCardModel($model)
    {
        $model = is_string($model)?$model:get_class($model);
        if (in_array($model, self::$wildcardModels)) {
            return true;
        }
        return false;
    }
}

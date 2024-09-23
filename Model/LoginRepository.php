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

class LoginRepository implements \Catgento\AdminActivity\Api\LoginRepositoryInterface
{
    /**
     * @var boolean
     */
    public const LOGIN_SUCCESS = 1;

    /**
     * @var boolean
     */
    public const LOGIN_FAILED = 0;

    /**
     * @var LoginFactory
     */
    public $loginFactory;

    /**
     * @var Processor
     */
    public $processor;

    /**
     * @var object Magento\User\Model\User
     */
    public $user;

    /**
     * @var ResourceModel\Activity\CollectionFactory
     */
    public $collectionFactory;

    /**
     * LoginRepository constructor.
     *
     * @param LoginFactory $loginFactory
     * @param ResourceModel\Login\CollectionFactory $collectionFactory
     * @param Processor $processor
     */
    public function __construct(
        \Catgento\AdminActivity\Model\LoginFactory $loginFactory,
        \Catgento\AdminActivity\Model\ResourceModel\Login\CollectionFactory $collectionFactory,
        \Catgento\AdminActivity\Model\Processor $processor
    ) {
        $this->loginFactory = $loginFactory;
        $this->collectionFactory = $collectionFactory;
        $this->processor = $processor;
    }

    /**
     * Get login user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set login user
     *
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set login activity data
     *
     * @return mixed
     */
    public function initLoginActivity()
    {
        $login = $this->loginFactory->create();

        $user = $this->getUser();
        if ($user != null && $user instanceof \Magento\User\Model\User) {
            $login->setUsername($user->getUsername());
            $login->setName(ucwords($user->getName()));
        }

        $login->setRemoteIp($this->processor->remoteAddress->getRemoteAddress());
        $login->setForwardedIp($this->processor->request->getServer('HTTP_X_FORWARDED_FOR'));
        $login->setUserAgent($this->processor->handler->header->getHttpUserAgent());

        return $login;
    }

    /**
     * Set login data
     *
     * @param $status
     * @param $type
     * @param string $remark
     * @return boolean
     */
    public function addLog($status, $type, $remark = '')
    {
        $login = $this->initLoginActivity();

        $login->setStatus($status);
        $login->setType($type);
        $login->setRemarks($remark);
        $login->save();

        return true;
    }

    /**
     * Track login success log
     *
     * @return void
     */
    public function addSuccessLog()
    {
        $this->addLog(self::LOGIN_SUCCESS, 'Login');
    }

    /**
     * Track login fail log
     *
     * @param string $remark
     */
    public function addFailedLog($remark = '')
    {
        $this->addLog(self::LOGIN_FAILED, 'Login', $remark);
    }

    /**
     * Track logout success log
     *
     * @return void
     */
    public function addLogoutLog()
    {
        $this->addLog(self::LOGIN_SUCCESS, 'Logout');
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
}

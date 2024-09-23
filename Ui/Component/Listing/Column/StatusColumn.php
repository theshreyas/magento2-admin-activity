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
namespace Catgento\AdminActivity\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class StatusColumn extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item[$this->getData('name')]) {
                    $item[$this->getData('name')] =
                        '<span class="grid-severity-notice"><span>Success</span></span>';
                } else {
                    $remark = $item['remarks'];
                    $item[$this->getData('name')] =
                        '<span class="grid-severity-critical" title="'.$remark.'"><span>Faild</span></span>';
                }
            }
        }

        return $dataSource;
    }
}

<?php

namespace Meanbee\OrderStatusAudit\Model\ResourceModel\Order\Status;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Audit extends AbstractDb
{

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init("sales_order_status_audit", "entity_id");
    }
}

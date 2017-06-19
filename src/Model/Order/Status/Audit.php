<?php

namespace Meanbee\OrderStatusAudit\Model\Order\Status;

use Magento\Framework\Model\AbstractModel;
use Meanbee\OrderStatusAudit\Api\Data\OrderStatusAuditInterface;

class Audit extends AbstractModel implements OrderStatusAuditInterface
{

    protected $_eventPrefix = "sales_order_status_audit";

    protected $_eventObject = "status_audit";

    protected function _construct()
    {
        $this->_init(\Meanbee\OrderStatusAudit\Model\ResourceModel\Order\Status\Audit::class);
    }

    /**
     * @inheritdoc
     */
    public function getParentId()
    {
        return $this->getData(static::PARENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setParentId($parentId)
    {
        return $this->setData(static::PARENT_ID, $parentId);
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->getData(static::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        return $this->setData(static::STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->getData(static::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(static::CREATED_AT, $createdAt);
    }
}

<?php

namespace Meanbee\OrderStatusAudit\Model\ResourceModel\Order\Status\Audit;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Meanbee\OrderStatusAudit\Api\Data\OrderStatusAuditInterface;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = "sales_order_status_audit_collection";

    protected $_eventObject = "order_status_audit_collection";

    protected function _construct()
    {
        $this->_init(
            \Meanbee\OrderStatusAudit\Model\Order\Status\Audit::class,
            \Meanbee\OrderStatusAudit\Model\ResourceModel\Order\Status\Audit::class
        );
    }

    /**
     * Filter the status audit collection for a specific order.
     *
     * @param int|\Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return $this
     */
    public function setOrderFilter($order)
    {
        if ($order instanceof \Magento\Sales\Api\Data\OrderInterface) {
            if ($orderId = $order->getEntityId()) {
                $this->addFieldToFilter(OrderStatusAuditInterface::PARENT_ID, $orderId);
            } else {
                $this->_totalRecords = 0;
                $this->_setIsLoaded(true);
            }
        } else {
            $this->addFieldToFilter(OrderStatusAuditInterface::PARENT_ID, $order);
        }

        return $this;
    }
}

<?php

namespace Meanbee\OrderStatusAudit\Block\Adminhtml\Order\View\Tab;

use Meanbee\OrderStatusAudit\Api\Data\OrderStatusAuditInterface;
use Meanbee\OrderStatusAudit\Model\Order\Status\Audit;

class StatusHistory extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_template = "order/view/tab/statushistory.phtml";

    /** @var \Magento\Framework\Registry */
    protected $coreRegistry;

    /** @var \Meanbee\OrderStatusAudit\Model\ResourceModel\Order\Status\Audit\CollectionFactory */
    protected $statusCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Meanbee\OrderStatusAudit\Model\ResourceModel\Order\Status\Audit\CollectionFactory $statusCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->coreRegistry = $coreRegistry;
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * Get the order model instance.
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry("current_order");
    }

    /**
     * Get all status audit entries for the current order.
     *
     * @return \Meanbee\OrderStatusAudit\Model\ResourceModel\Order\Status\Audit\Collection
     */
    public function getStatusHistory()
    {
        return $this->statusCollectionFactory
            ->create()
            ->setOrderFilter($this->getOrder())
            ->setOrder(OrderStatusAuditInterface::CREATED_AT, "asc");
    }

    /**
     * Get a formatted created_at date for the given item.
     *
     * @param Audit  $item
     * @param string $dateType
     * @param int    $format
     *
     * @return string
     */
    public function formatCreatedAt(Audit $item, $dateType = "date", $format = \IntlDateFormatter::MEDIUM)
    {
        if ($date = $item->getCreatedAt()) {
            if ($dateType === "date") {
                return $this->formatDate($date, $format);
            }

            return $this->formatTime($date, $format);
        }

        return "";
    }

    /**
     * Get a formatted status label for the given item.
     *
     * @param Audit $item
     *
     * @return string
     */
    public function formatStatus(Audit $item)
    {
        if ($status = $item->getStatus()) {
            return $this->escapeHtml($this->getOrder()->getConfig()->getStatusLabel($status));
        }

        return "";
    }

    /**
     * @inheritdoc
     */
    public function getTabLabel()
    {
        return __("Order Status History");
    }

    /**
     * @inheritdoc
     */
    public function getTabTitle()
    {
        return __("Order Status History");
    }

    /**
     * @inheritdoc
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isHidden()
    {
        return false;
    }
}

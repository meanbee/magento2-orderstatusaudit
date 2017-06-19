<?php

namespace Meanbee\OrderStatusAudit\Api\Data;

/**
 * Interface OrderStatusAuditInterface
 *
 * @api
 */
interface OrderStatusAuditInterface
{
    const ENTITY_ID = "entity_id";
    const PARENT_ID = "parent_id";
    const STATUS = "status";
    const CREATED_AT = "created_at";

    /**
     * Get the ID for the order status audit.
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set the entity ID.
     *
     * @param int $entityId
     *
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get the parent order ID.
     *
     * @return int
     */
    public function getParentId();

    /**
     * Set the parent order ID.
     *
     * @param int $parentId
     *
     * @return $this
     */
    public function setParentId($parentId);

    /**
     * Get the order status.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set the order status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get the created_at timestamp.
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set the created_at timestamp.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);
}

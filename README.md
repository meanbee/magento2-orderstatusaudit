# Meanbee_OrderStatusAudit

A Magento 2 extension that provides an audit log for order status.

## Installation

Install this extension with Composer:

    composer require meanbee/magento2-orderstatusaudit

# Usage

The `sales_order_status_audit` table is automatically updated using database triggers whenever an order status changes.

You can view the log of the order status in the "Order Status History" tab on the order page in the admin area.

![screenshot](https://user-images.githubusercontent.com/613076/27288505-d227379c-54fe-11e7-883e-557f51ada995.png)

## Development

### Docker Environment

To configure a Docker development environment, run:

    mkdir magento && cd dev && docker-compose run --rm cli bash /src/dev/setup.sh

The configured environment will be available on [https://m2-meanbee-orderstatusaudit.docker/](https://m2-meanbee-orderstatusaudit.docker/)

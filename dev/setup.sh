#!/usr/bin/env bash

set -e # Exit on error

if [ $(find $MAGENTO_ROOT -maxdepth 0 -type d -empty 2>/dev/null) ]; then
    # Initial setup
    ########################################

    # Install Magento
    magento-installer

    # Add the extension using Composer
    cd $MAGENTO_ROOT
    composer config repositories.meanbee_magento2_orderstatusaudit '{"type": "path", "url": "/src", "options": {"symlink": true}}'
    composer require "meanbee/magento2-orderstatusaudit" "@dev"

    # Workaround for Magento only allowing template paths within the install root
    ln -s /src $MAGENTO_ROOT/src
fi

# Enable and configure extension
########################################

magento-command module:enable Meanbee_OrderStatusAudit
magento-command setup:upgrade
magento-command setup:di:compile
magento-command setup:static-content:deploy
magento-command cache:flush

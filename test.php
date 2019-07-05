<?php

use Genome\Lib\Model\FixedProduct;
use Genome\Lib\Model\UserInfo;
use Genome\Scriney;

require 'vendor/autoload.php';
$scriney = new Scriney('pkLive_04RuAuiW8GIj8YyMWFX84sMeZV48V2qH', 'skLive_0gvX679fqTgBNSXCryQQVtA2l14IOa4r');
$iFrame = $scriney
    ->buildButton('vuk@softwarehaus.io')
    ->setSuccessReturnUrl('https://pengiunx.test/en/order/async/capture')
    ->setDeclineReturnUrl('https://pengiunx.test/en/order?error=1')
    ->setUserInfo(
        new UserInfo(
            'vuk@softwarehaus.io'
        )
    )
    ->setCustomProducts([
        new FixedProduct(
            '18', // $productId
            'BlogCollectio', // $productName
            5, // $amount
            'EUR' // $currency
        ),
    ])
    ->buildFrame();
    echo $iFrame;

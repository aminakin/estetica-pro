<?php

use Bitrix\Main\Localization\Loc;

?>

<div class="main-profile-orders__filter">
    <a class="main-profile-orders__link<?= ((!isset($_GET['filter_status']) && !isset($_GET['filter_canceled'])) || ($_GET['filter_status'] == '' && $_GET['filter_canceled'] != 'Y')) ? ' main-profile-orders__link--active' : '' ?>"
       href="<?= $APPLICATION->GetCurPageParam("", array("filter_status", "filter_canceled")); ?>">
        <?= Loc::getMessage('ALL_ORDERS') ?><? $APPLICATION->ShowViewContent('filter_all_orders'); ?>
    </a>
    <?/*a class="main-profile-orders__link<?= ($_GET['filter_status'] == 'N') ? ' main-profile-orders__link--active' : '' ?>"
       href="<?= $APPLICATION->GetCurPageParam("filter_status=N", array("filter_status", "filter_canceled")); ?>">
        <?= Loc::getMessage('FILTER_NEW') ?><? $APPLICATION->ShowViewContent('filter_order_status_N'); ?>
    </a>
    <a class="main-profile-orders__link<?= ($_GET['filter_status'] == 'F') ? ' main-profile-orders__link--active' : '' ?>"
       href="<?= $APPLICATION->GetCurPageParam("filter_status=F", array("filter_status", "filter_canceled")); ?>">
        <?= Loc::getMessage('FILTER_COMPLETED') ?><? $APPLICATION->ShowViewContent('filter_order_status_F'); ?>
    </a*/?>
    <a class="main-profile-orders__link<?= ($_GET['filter_canceled'] == 'Y') ? ' main-profile-orders__link--active' : '' ?>"
       href="<?= $APPLICATION->GetCurPageParam("filter_canceled=Y", array("filter_status", "filter_canceled")); ?>">
        <?= Loc::getMessage('FILTER_CANCELED') ?><? $APPLICATION->ShowViewContent('filter_order_status_cancelled'); ?>
    </a>
</div>
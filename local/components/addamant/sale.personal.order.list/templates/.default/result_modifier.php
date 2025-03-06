<?php
//var_export(get_class_methods($this));
global $USER;
$defaultParams = [
    'select' => [new \Bitrix\Main\ORM\Fields\ExpressionField('CNT', 'COUNT(*)')],
    'filter' => [
        'USER_ID' => $USER->getID(),
        'LID' => SITE_ID,
    ]
];

$cnt = (new \CDBResult(\Bitrix\Sale\Order::getList($defaultParams)))->Fetch();

$this->SetViewTarget("filter_all_orders");
echo '<span class="orders_filter_count">' . $cnt['CNT'] . '</span>';
$this->EndViewTarget();

$params = $defaultParams;
$params['filter']['STATUS_ID'] = 'N';
$params['filter']['CANCELED'] = 'N';

$cnt = (new \CDBResult(\Bitrix\Sale\Order::getList($params)))->Fetch();

$this->SetViewTarget("filter_order_status_N");
echo '<span class="orders_filter_count">' . $cnt['CNT'] . '</span>';
$this->EndViewTarget();

$params = $defaultParams;
$params['filter']['STATUS_ID'] = 'F';
$params['filter']['CANCELED'] = 'N';

$cnt = (new \CDBResult(\Bitrix\Sale\Order::getList($params)))->Fetch();

$this->SetViewTarget("filter_order_status_F");
echo '<span class="orders_filter_count">' . $cnt['CNT'] . '</span>';
$this->EndViewTarget();

$params = $defaultParams;
$params['filter']['CANCELED'] = 'Y';

$cnt = (new \CDBResult(\Bitrix\Sale\Order::getList($params)))->Fetch();

$this->SetViewTarget("filter_order_status_cancelled");
echo '<span class="orders_filter_count">' . $cnt['CNT'] . '</span>';
$this->EndViewTarget();


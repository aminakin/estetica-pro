<?php
$arUrlRewrite=array (
  65 => 
  array (
    'CONDITION' => '#^={"/new-catalog/"."([^/]+?)/filter/(.+?)/apply/"}\\??(.*)#',
    'RULE' => 'SECTION_CODE=$1&SMART_FILTER_PATH=$2&$3',
    'ID' => 'bitrix:catalog.smart.filter',
    'PATH' => '/local/templates/main/components/bitrix/news/new-catalog/section.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  49 => 
  array (
    'CONDITION' => '#^/personal/orders/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/orders/index.php',
    'SORT' => 100,
  ),
  71 => 
  array (
    'CONDITION' => '#^/new-catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/new-catalog/index.php',
    'SORT' => 100,
  ),
  66 => 
  array (
    'CONDITION' => '#^/courses/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/courses/index.php',
    'SORT' => 100,
  ),
  69 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  70 => 
  array (
    'CONDITION' => '#^/palitra/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/palitra/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/brands/#',
    'RULE' => '',
    'ID' => 'bitrix:news.list',
    'PATH' => '/brands/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/brands/#',
    'RULE' => '&$1',
    'ID' => 'bitrix:catalog.section',
    'PATH' => '/brands/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/stock/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/stock/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
);

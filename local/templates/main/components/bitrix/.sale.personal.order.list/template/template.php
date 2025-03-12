<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);

if (!empty($arResult['ERRORS']['FATAL'])) {
    foreach ($arResult['ERRORS']['FATAL'] as $error) {
        ShowError($error);
    }
    $component = $this->__component;
    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }

} else {
    if (!empty($arResult['ERRORS']['NONFATAL'])) {
        foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
            ShowError($error);
        }
    }
    if (!count($arResult['ORDERS'])) {
        if ($_REQUEST["filter_history"] == 'Y') {
            if ($_REQUEST["show_canceled"] == 'Y') {
                ?>
                <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER') ?></h3>
                <?
            } else {
                ?>
                <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST') ?></h3>
                <?
            }
        } else {
            ?>
            <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST') ?></h3>
            <?
        }
    }
    ?>


    <div class="main-profile-orders section">
        <div class="main-profile-orders left_sidebar">
            <div class="main-profile-orders__filter">
                <a class="main-profile-orders__link<?=(!isset($_GET['filter']) || $_GET['filter'] == '') ? ' main-profile-orders__link--active' : ''?>"
                   href="<?= $APPLICATION->GetCurPageParam("", array("filter")); ?>">
                    <?= Loc::getMessage('FILTER_ALL') ?>
                </a>
                <a class="main-profile-orders__link<?=($_GET['filter'] == 'status_N') ? ' main-profile-orders__link--active' : ''?>"
                   href="<?= $APPLICATION->GetCurPageParam("filter=status_N", array("filter")); ?>">
                    <?= Loc::getMessage('FILTER_NEW') ?>
                </a>
                <a class="main-profile-orders__link<?=($_GET['filter'] == 'status_F') ? ' main-profile-orders__link--active' : ''?>"
                   href="<?= $APPLICATION->GetCurPageParam("filter=status_F", array("filter")); ?>">
                    <?= Loc::getMessage('FILTER_COMPLETED') ?>
                </a>
                <a class="main-profile-orders__link<?=($_GET['filter'] == 'canceled') ? ' main-profile-orders__link--active' : ''?>"
                   href="<?= $APPLICATION->GetCurPageParam("filter=canceled", array("filter")); ?>">
                    <?= Loc::getMessage('FILTER_CANCELED') ?>
                </a>
            </div>

            <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "personal_menu_left",
                array(
                    "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                    "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
                    "DELAY" => "N",    // Откладывать выполнение шаблона меню
                    "MAX_LEVEL" => "1",    // Уровень вложенности меню
                    "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                    "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
                    "MENU_CACHE_TYPE" => "N",    // Тип кеширования
                    "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
                    "ROOT_MENU_TYPE" => "profil_menu_left",    // Тип меню для первого уровня
                    "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
                    "COMPONENT_TEMPLATE" => ".default"
                ),
                false
            ); ?>
        </div>
        <div class="content">
        </div>
    </div>

    <?/*<div class="row col-md-12 col-sm-12">
		<?
		$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);
		$clearFromLink = array("filter_history","filter_status","show_all", "show_canceled");

		if ($nothing || $_REQUEST["filter_history"] == 'N')
		{
			?>
			<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
				<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
			</a>
			<?
		}
		if ($_REQUEST["filter_history"] == 'Y')
		{
			?>
			<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("", $clearFromLink, false)?>">
				<?echo Loc::getMessage("SPOL_TPL_CUR_ORDERS")?>
			</a>
			<?
			if ($_REQUEST["show_canceled"] == 'Y')
			{
				?>
				<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
					<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
				</a>
				<?
			}
			else
			{
				?>
				<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y&show_canceled=Y", $clearFromLink, false)?>">
					<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_CANCELED")?>
				</a>
				<?
			}
		}
		?>            url = '?show_all=Y';
            //d_url = '?filter_history=Y&show_canceled=Y';
            break;
        case 'can':
            url = '?filter_history=Y&show_canceled=Y';
            break;
        case 'fin':
            url = '?filter_history=Y';
            break;
        default:
            url = '?filter_status='+$(this).val();
	</div>*/
    ?>
    <? if ($arParams['AJAX'] != 'Y') {
        ?>
        <div class="row">
        <div class="left_sidebar">
            <div class="order_filter">
                <select name="of" id="order_filter">
                    <option <?= (!$_REQUEST[show_all] == 'Y') ? 'selected' : '' ?>
                            value="all"><?= GetMessage('F_ALL') ?></option>
                    <option <?= (!$_REQUEST[show_all] && !$_REQUEST['filter_history'] && !$_REQUEST['show_canceled'] && !$_REQUEST['filter_status']) ? 'selected' : '' ?>
                            value=""><?= GetMessage('F_CUR') ?></option>

                    <option <?= ($_REQUEST['filter_history'] && $_REQUEST['show_canceled']) ? 'selected' : '' ?>
                            value="can"><?= GetMessage('F_CAN') ?></option>
                    <option <?= ($_REQUEST['filter_status'] == 'F') ? 'selected' : '' ?>
                            value="fin"><?= GetMessage('F_FIN') ?></option>
                    <?/*foreach($arResult['INFO']['STATUS'] as $stat)
                {
                    if ($stat['ID']=='F') continue;
                    ?>
                    <option <?=($_REQUEST['filter_status']==$stat['ID'])?'selected':''?> value="<?=$stat['ID']?>"><?=GetMessage('F_STAT')?> "<?=$stat['NAME'];?>"</option>
                    <?
                }*/
                    ?>
                </select>
                <input type="radio" name="of" <?= (!$_REQUEST[show_all] == 'Y') ? 'selected' : '' ?>
                       value="all"><?= GetMessage('F_ALL') ?>
                <input type="radio"
                       name="of" <?= (!$_REQUEST[show_all] && !$_REQUEST['filter_history'] && !$_REQUEST['show_canceled'] && !$_REQUEST['filter_status']) ? 'selected' : '' ?>
                       value=""><?= GetMessage('F_CUR') ?>

                <input type="radio"
                       name="of" <?= ($_REQUEST['filter_history'] && $_REQUEST['show_canceled']) ? 'selected' : '' ?>
                       value="can"><?= GetMessage('F_CAN') ?>
                <input type="radio" name="of" <?= ($_REQUEST['filter_status'] == 'F') ? 'selected' : '' ?>
                       value="fin"><?= GetMessage('F_FIN') ?>

            </div>
        </div>
        <div id="ord-list" class="col-sm-9">
        <?
    }

    if ($_REQUEST["filter_history"] !== 'Y') {
        $paymentChangeData = array();
        $orderHeaderStatus = null;

        foreach ($arResult['ORDERS'] as $key => $order) {
            if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $arResult['SORT_TYPE'] == 'STATUS') {
                $orderHeaderStatus = $order['ORDER']['STATUS_ID'];

                ?>
                <h1 class="sale-order-title">
                    <?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?>
                    &laquo;<?= htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME']) ?>&raquo;
                </h1>
                <?
            }
            ?>
            <div class="col-md-12 col-sm-12 sale-order-list-container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 sale-order-list-title-container">
                        <h2 class="sale-order-list-title">
                            <?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                            <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . $order['ORDER']['ACCOUNT_NUMBER'] ?>
                            <?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
                            <?= $order['ORDER']['DATE_INSERT']->format($arParams['ACTIVE_DATE_FORMAT']) ?>,
                            <?= count($order['BASKET_ITEMS']); ?>
                            <?
                            $count = count($order['BASKET_ITEMS']) % 10;
                            if ($count == '1') {
                                echo Loc::getMessage('SPOL_TPL_GOOD');
                            } elseif ($count >= '2' && $count <= '4') {
                                echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
                            } else {
                                echo Loc::getMessage('SPOL_TPL_GOODS');
                            }
                            ?>
                            <?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
                            <?= $order['ORDER']['FORMATED_PRICE'] ?>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 sale-order-list-inner-container">
                        <div class="row sale-order-list-inner-row">
                            <div class="sale-order-list-top-border"></div>
                            <div class="col-md-8  col-sm-12 sale-order-list-about-container">
                                <a class="sale-order-list-about-link"
                                   href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>"><?= Loc::getMessage('SPOL_TPL_MORE_ON_ORDER') ?></a>
                            </div>
                            <div class="col-md-2 col-sm-12 sale-order-list-repeat-container">
                                <a class="sale-order-list-repeat-link"
                                   href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>"><?= Loc::getMessage('SPOL_TPL_REPEAT_ORDER') ?></a>
                            </div>
                            <div class="col-md-2 col-sm-12 sale-order-list-cancel-container">
                                <a class="sale-order-list-cancel-link"
                                   href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"]) ?>"><?= Loc::getMessage('SPOL_TPL_CANCEL_ORDER') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?
        }
    } else {
        $orderHeaderStatus = null;

        if ($_REQUEST["show_canceled"] === 'Y' && count($arResult['ORDERS'])) {
            ?>
            <h1 class="sale-order-title">
                <?= Loc::getMessage('SPOL_TPL_ORDERS_CANCELED_HEADER') ?>
            </h1>
            <?
        }

        foreach ($arResult['ORDERS'] as $key => $order) {
            if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $_REQUEST["show_canceled"] !== 'Y') {
                $orderHeaderStatus = $order['ORDER']['STATUS_ID'];
                ?>
                <h1 class="sale-order-title">
                    <?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?>
                    &laquo;<?= htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME']) ?>&raquo;
                </h1>
                <?
            }
            ?>
            <div class="col-md-12 col-sm-12 sale-order-list-container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 sale-order-list-accomplished-title-container">
                        <div class="row">
                            <div class="col-md-8 col-sm-12 sale-order-list-accomplished-title-container">
                                <h2 class="sale-order-list-accomplished-title">
                                    <?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                                    <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') ?>
                                    <?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>
                                    <?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
                                    <?= $order['ORDER']['DATE_INSERT'] ?>,
                                    <?= count($order['BASKET_ITEMS']); ?>
                                    <?
                                    $count = substr(count($order['BASKET_ITEMS']), -1);
                                    if ($count == '1') {
                                        echo Loc::getMessage('SPOL_TPL_GOOD');
                                    } elseif ($count >= '2' || $count <= '4') {
                                        echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
                                    } else {
                                        echo Loc::getMessage('SPOL_TPL_GOODS');
                                    }
                                    ?>
                                    <?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
                                    <?= $order['ORDER']['FORMATED_PRICE'] ?>
                                </h2>
                            </div>
                            <div class="col-md-4 col-sm-12 sale-order-list-accomplished-date-container">
                                <?
                                if ($_REQUEST["show_canceled"] !== 'Y') {
                                    ?>
                                    <span class="sale-order-list-accomplished-date">
										<?= Loc::getMessage('SPOL_TPL_ORDER_FINISHED') ?>
									</span>
                                    <?
                                } else {
                                    ?>
                                    <span class="sale-order-list-accomplished-date canceled-order">
										<?= Loc::getMessage('SPOL_TPL_ORDER_CANCELED') ?>
									</span>
                                    <?
                                }
                                ?>
                                <span class="sale-order-list-accomplished-date-number"><?= $order['ORDER']['DATE_STATUS_FORMATED'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 sale-order-list-inner-accomplished">
                        <div class="row sale-order-list-inner-row">
                            <div class="col-md-3 col-sm-12 sale-order-list-about-accomplished">
                                <a class="sale-order-list-about-link"
                                   href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>">
                                    <?= Loc::getMessage('SPOL_TPL_MORE_ON_ORDER') ?>
                                </a>
                            </div>
                            <div class="col-md-3 col-md-offset-6 col-sm-12 sale-order-list-repeat-accomplished">
                                <a class="sale-order-list-repeat-link sale-order-link-accomplished"
                                   href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>">
                                    <?= Loc::getMessage('SPOL_TPL_REPEAT_ORDER') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?
        }
    }
    ?>

    <div class="clearfix"></div>
    <?
    echo str_replace('/local/ajax/order_list.php', '/personal/orders/', $arResult["NAV_STRING"]);
    if ($arParams['AJAX'] != 'Y') {

    if ($_REQUEST["filter_history"] !== 'Y') {
        $javascriptParams = array(
            "url" => CUtil::JSEscape($this->__component->GetPath() . '/ajax.php'),
            "templateFolder" => CUtil::JSEscape($templateFolder),
            "paymentList" => $paymentChangeData
        );
        $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
        ?>
        <script>
            BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
        </script>
    <?
    } ?>
        </div>
        </div>
        <?
    }
}
?>
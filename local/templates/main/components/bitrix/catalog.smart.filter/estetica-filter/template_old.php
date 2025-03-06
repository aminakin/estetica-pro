<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>
<form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" method="get"
      class="smartfilter catalog-smart-filter">
    <? foreach ($arResult["HIDDEN"] as $arItem): ?>
        <input type="hidden" name="<? echo $arItem["CONTROL_NAME"] ?>" id="<? echo $arItem["CONTROL_ID"] ?>"
               value="<? echo $arItem["HTML_VALUE"] ?>"/>
    <? endforeach; ?>
    <? foreach ($arResult["ITEMS"] as $key => $arItem)//prices
    {
        $key = $arItem["ENCODED_ID"];
        if (isset($arItem["PRICE"])):
            if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                continue;

            $step_num = 4;
            $step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / $step_num;
            $prices = array();
            if (Bitrix\Main\Loader::includeModule("currency")) {
                for ($i = 0; $i < $step_num; $i++) {
                    $prices[$i] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MIN"]["VALUE"] + $step * $i, $arItem["VALUES"]["MIN"]["CURRENCY"], false);
                }
                $prices[$step_num] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MAX"]["VALUE"], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
            } else {
                $precision = $arItem["DECIMALS"] ? $arItem["DECIMALS"] : 0;
                for ($i = 0; $i < $step_num; $i++) {
                    $prices[$i] = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * $i, $precision, ".", "");
                }
                $prices[$step_num] = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
            }
            if (!$arItem["VALUES"]["MAX"]["HTML_VALUE"]) $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];
            if (!$arItem["VALUES"]["MIN"]["HTML_VALUE"]) $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"]; ?>
            <div class="labelf smart-filter-price">
                <span class="label-price smart-filter-price-label" onclick="handleOpenFilterCount(this)"><?= $arItem["NAME"] ?></span>
                <div class="smart-filter-price-block">
                    <br/>
                    <span>от</span>
                    <input
                        type="text"
                        name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                        id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                        value="<? echo number_format($arItem["VALUES"]["MIN"]["HTML_VALUE"], 0, '.', '') ?>"
                        onkeyup="smartFilter.keyup(this)"
                    />
                    <span>до</span>
                    <input
                        type="text"
                        name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                        id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                        value="<? echo number_format($arItem["VALUES"]["MAX"]["HTML_VALUE"], 0, '.', '') ?>"
                        onkeyup="smartFilter.keyup(this)"
                    />
                    <span>руб.</span>
                </div>
            </div>
        <?endif;
    }

    //not prices
    foreach ($arResult["ITEMS"] as $key => $arItem) {
        if (
            empty($arItem["VALUES"])
            || isset($arItem["PRICE"])
        )
            continue;

        if (
            $arItem["DISPLAY_TYPE"] == "A"
            && (
                $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
            )
        )
            continue;
        ?>
        <div class="labelf"><span><?= $arItem["NAME"] ?></span><br class="hideFull"/>
            <?
            $arCur = current($arItem["VALUES"]);
            switch ($arItem["DISPLAY_TYPE"]) {
                case "A"://NUMBERS_WITH_SLIDER
                    if (!$arItem["VALUES"]["MAX"]["HTML_VALUE"]) $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];
                    if (!$arItem["VALUES"]["MIN"]["HTML_VALUE"]) $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"]; ?>
                    от <input type="text" name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                              id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                              value="<? echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                              onkeyup="smartFilter.keyup(this)"/>
                    до <input type="text" name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                              id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                              value="<? echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                              onkeyup="smartFilter.keyup(this)"/>
                    <?
                    break;
                case "B"://NUMBERS
                    if (!$arItem["VALUES"]["MAX"]["HTML_VALUE"]) $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["VALUE"];
                    if (!$arItem["VALUES"]["MIN"]["HTML_VALUE"]) $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["VALUE"]; ?>
                    от <input type="text" name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                              id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                              value="<? echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                              onkeyup="smartFilter.keyup(this)"/>
                    до <input type="text" name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                              id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                              value="<? echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                              onkeyup="smartFilter.keyup(this)"/>
                    <?
                    break;
                case "G"://CHECKBOXES_WITH_PICTURES
                    ?>
                    <? foreach ($arItem["VALUES"] as $val => $ar):?>
                    <input
                            style="display: none"
                            type="checkbox"
                            name="<?= $ar["CONTROL_NAME"] ?>"
                            id="<?= $ar["CONTROL_ID"] ?>"
                            value="<?= $ar["HTML_VALUE"] ?>"
                        <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                    />
                    <?
                    $class = "";
                    if ($ar["CHECKED"])
                        $class .= " bx-active";
                    if ($ar["DISABLED"])
                        $class .= " disabled";
                    ?>
                    <label for="<?= $ar["CONTROL_ID"] ?>" data-role="label_<?= $ar["CONTROL_ID"] ?>"
                           class="bx-filter-param-label <?= $class ?>"
                           onclick="smartFilter.keyup(BX('<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')); BX.toggleClass(this, 'bx-active');">
                                    <span class="bx-filter-param-btn bx-color-sl">
                                        <? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                            <span class="bx-filter-btn-color-icon"
                                                  style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                        <?endif ?>
                                    </span>
                    </label>
                <?endforeach ?>
                    <?
                    break;
                case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
                    ?>
                    <? foreach ($arItem["VALUES"] as $val => $ar):?>
                    <input
                            style="display: none"
                            type="checkbox"
                            name="<?= $ar["CONTROL_NAME"] ?>"
                            id="<?= $ar["CONTROL_ID"] ?>"
                            value="<?= $ar["HTML_VALUE"] ?>"
                        <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                    />
                    <?
                    $class = "";
                    if ($ar["CHECKED"])
                        $class .= " bx-active";
                    if ($ar["DISABLED"])
                        $class .= " disabled";
                    ?>
                    <label for="<?= $ar["CONTROL_ID"] ?>" data-role="label_<?= $ar["CONTROL_ID"] ?>"
                           class="bx-filter-param-label<?= $class ?>"
                           onclick="smartFilter.keyup(BX('<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')); BX.toggleClass(this, 'bx-active');">
                                    <span class="bx-filter-param-btn bx-color-sl">
                                        <? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                            <span class="bx-filter-btn-color-icon"
                                                  style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                        <?endif ?>
                                    </span>
                        <span class="bx-filter-param-text" title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                ?> (<span
                                    data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                            endif; ?></span>
                    </label>
                <?endforeach ?>
                    <?
                    break;
                case "P"://DROPDOWN
                    $checkedItemExist = false;
                    ?>
                    <select name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
                            onchange="if($(this).val()!='all'){smartFilter.selectDropDownItem(this, $(this).find('[value='+$(this).val()+']').attr('id'))}else{$('#del_filter').click()};"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                        >
                        <option id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                value="all"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></option>
                        <? foreach ($arItem["VALUES"] as $val => $ar):?>
                            <option value="<? echo $ar["HTML_VALUE_ALT"] ?>" <? echo $ar["CHECKED"] ? 'selected' : '' ?>
                                    id="<?= $ar["CONTROL_ID"] ?>">
                                <?= $ar["VALUE"] ?>
                            </option>
                        <?endforeach ?>
                    </select>
                    <?
                    break;
                case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
                    ?>
                    <select name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
                            onchange="smartFilter.selectDropDownItem(this, $(this).find('[value='+$(this).val()+']').attr('id'))"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                        >
                        <option id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                value=""><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></option>
                        <? foreach ($arItem["VALUES"] as $val => $ar):?>
                            <option value="<? echo $ar["HTML_VALUE_ALT"] ?>" <? echo $ar["CHECKED"] ? 'selected"' : '' ?>
                                    id="<?= $ar["CONTROL_ID"] ?>">
                                <?= $ar["VALUE"] ?>
                            </option>
                        <?endforeach ?>
                    </select>
                    <?
                    break;
                case "K"://RADIO_BUTTONS
                    ?>
                    <div class="col-xs-12">
                        <div class="radio">
                            <label class="bx-filter-param-label" for="<? echo "all_" . $arCur["CONTROL_ID"] ?>">
                                    <span class="bx-filter-input-checkbox">
                                        <input
                                                type="radio"
                                                value=""
                                                name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
                                                id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                                onclick="smartFilter.click(this)"
                                        />
                                        <span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
                                    </span>
                            </label>
                        </div>
                        <? foreach ($arItem["VALUES"] as $val => $ar):?>
                            <div class="radio">
                                <label data-role="label_<?= $ar["CONTROL_ID"] ?>" class="bx-filter-param-label"
                                       for="<? echo $ar["CONTROL_ID"] ?>">
                                        <span class="bx-filter-input-checkbox <? echo $ar["DISABLED"] ? 'disabled' : '' ?>">
                                            <input
                                                    type="radio"
                                                    value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                    name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
                                                    id="<? echo $ar["CONTROL_ID"] ?>"
                                                <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                onclick="smartFilter.click(this)"
                                            />
                                            <span class="bx-filter-param-text"
                                                  title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
                                                if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                    ?>&nbsp;(<span
                                                        data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                endif; ?></span>
                                        </span>
                                </label>
                            </div>
                        <?endforeach; ?>
                    </div>
                    <?
                    break;
                case "U"://CALENDAR
                    ?>
                    <div class="col-xs-12">
                        <div class="bx-filter-parameters-box-container-block">
                            <div class="bx-filter-input-container bx-filter-calendar-container">
                                <? $APPLICATION->IncludeComponent(
                                    'bitrix:main.calendar',
                                    '',
                                    array(
                                        'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
                                        'SHOW_INPUT' => 'Y',
                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                        'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
                                        'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                        'SHOW_TIME' => 'N',
                                        'HIDE_TIMEBAR' => 'Y',
                                    ),
                                    null,
                                    array('HIDE_ICONS' => 'Y')
                                ); ?>
                            </div>
                        </div>
                        <div class="bx-filter-parameters-box-container-block">
                            <div class="bx-filter-input-container bx-filter-calendar-container">
                                <? $APPLICATION->IncludeComponent(
                                    'bitrix:main.calendar',
                                    '',
                                    array(
                                        'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
                                        'SHOW_INPUT' => 'Y',
                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                        'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
                                        'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                        'SHOW_TIME' => 'N',
                                        'HIDE_TIMEBAR' => 'Y',
                                    ),
                                    null,
                                    array('HIDE_ICONS' => 'Y')
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <?
                    break;
                default://CHECKBOXES
                    ?>
                    <div class="col-xs-12">
                        <? foreach ($arItem["VALUES"] as $val => $ar):?>
                            <div class="checkbox">
                                <label data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                       class="bx-filter-param-label <? echo $ar["DISABLED"] ? 'disabled' : '' ?>"
                                       for="<? echo $ar["CONTROL_ID"] ?>">
                                        <span class="bx-filter-input-checkbox">
                                            <input
                                                    type="checkbox"
                                                    value="<? echo $ar["HTML_VALUE"] ?>"
                                                    name="<? echo $ar["CONTROL_NAME"] ?>"
                                                    id="<? echo $ar["CONTROL_ID"] ?>"
                                                <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                onclick="smartFilter.click(this)"
                                            />
                                            <span class="bx-filter-param-text"
                                                  title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
                                                if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                    ?>&nbsp;(<span
                                                        data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                endif; ?></span>
                                        </span>
                                </label>
                            </div>
                        <?endforeach; ?>
                    </div>
                <?
            }
            ?>
        </div>
        <?
    }
    ?>
    <div class="labelf catalog-smart-filter-sorting"><span class="label-price">Сортировать:</span><br class="hideFull"/>
        <?
        $sort = 'NAME';
        $order = 'asc';
        if ($APPLICATION->GetCurPage() == ' /catalog/zhidkosti_de_lakrua/') {
            $sort = 'NAME';
        }
        if ($APPLICATION->GetCurPage() == '/catalog/plastiny_dlya_stempinga/') {
            $sort = 'SORT';
        }

        //var_export($APPLICATION->GetCurPage());

        switch ($_REQUEST['order']) {
            case 'asc':
                $order = 'asc';
                break;
            case 'desc':
                $order = 'desc';
                break;
        };
        switch ($_REQUEST['sort']) {
            case 'price':
                $sort = 'CATALOG_PRICE_1';
                break;
            case 'name':
                $sort = 'NAME';
                break;
            case 'pop':
                $sort = 'SHOW_COUNTER';
                break;
            case 'default' :
                $sort = 'NAME';
                $order = 'asc';
                break;
        };
        if ($APPLICATION->GetCurPage() == '/catalog/plastiny_dlya_stempinga_moyra/') {
            $sort = 'NAME';
            $order = 'desc';
        }
        if ($APPLICATION->GetCurPage() == '/catalog/plastiny_dlya_stempinga_swanky_stamping/') {
            $sort = 'NAME';
            $order = 'desc';
        }
        /* if($APPLICATION->GetCurPage() == '/catalog/plastiny_dlya_stempinga_swanky_stamping/'){
             $sort = 'name';
             $order = 'desc';
             //echo "string";/catalog/plastiny_dlya_stempinga_swanky_stamping/
         }*/
        if ($_REQUEST['sort'] == 'price') {
            if ($_REQUEST['order'] == 'asc') {
                $sort = 'PROPERTY_MINIMUM_PRICE';
            } else {
                $sort = 'PROPERTY_MAXIMUM_PRICE';
            }
        }
        global $sortCatalog;
        global $orderCatalog;
        $sortCatalog = $sort;
        $orderCatalog = $order;
        ?>
        <select id="sortc" name="sort" class="catalog-smart-filter-sort-select">
            <option <?= ($_REQUEST['sort'] == 'default') ? 'selected' : '' ?>
                    value="<?= $APPLICATION->GetCurPageParam("sort=default&order=asc", array("sort", "order")) ?>"
                    class="catalog-smart-filter-sort-option catalog-smart-filter-sort-option-default"
            >
                популярные
            </option>
            <option <?= ($_REQUEST['sort'] == 'name') ? 'selected' : '' ?>
                    value="<?= $APPLICATION->GetCurPageParam("sort=name&order=asc", array("sort", "order")) ?>"
                    class="catalog-smart-filter-sort-option catalog-smart-filter-sort-option-append-sort"
            >
                от А до Я
            </option>

<!--            <option --><?//= ($_REQUEST['sort'] == 'pop') ? 'selected' : '' ?>
<!--                    value="--><?//= $APPLICATION->GetCurPageParam("sort=pop&order=desc", array("sort", "order")) ?><!--">по-->
<!--                популярности-->
<!--            </option>-->

            <option <?= ($_REQUEST['sort'] == 'price' && $order == 'asc') ? 'selected' : '' ?>
                    value="<?= $APPLICATION->GetCurPageParam("sort=price&order=asc", array("sort", "order")) ?>"
                    class="catalog-smart-filter-sort-option catalog-smart-filter-sort-option-count-down"
            >
                Цена
            </option>
            <option <?= ($_REQUEST['sort'] == 'price' && $order == 'desc') ? 'selected' : '' ?>
                    value="<?= $APPLICATION->GetCurPageParam("sort=price&order=desc", array("sort", "order")) ?>"
                    class="catalog-smart-filter-sort-option catalog-smart-filter-sort-option-count-up"
            >
                Цена
            </option>

        </select>
    </div>
    <div class="noshow">
        <div class="col-xs-12 bx-filter-button-box">
            <div class="bx-filter-block">
                <div class="bx-filter-parameters-box-container">
                    <input
                            class="btn btn-themes"
                            type="submit"
                            id="set_filter"
                            name="set_filter"
                            value="<?= GetMessage("CT_BCSF_SET_FILTER") ?>"
                    />
                    <input
                            class="btn btn-link"
                            type="submit"
                            id="del_filter"
                            name="del_filter"
                            value="<?= GetMessage("CT_BCSF_DEL_FILTER") ?>"
                    />
                    <div class="bx-filter-popup-result <? if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"] ?>"
                         id="modef" <? if (!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?>
                         style="display: inline-block;">
                        <? echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">' . intval($arResult["ELEMENT_COUNT"]) . '</span>')); ?>
                        <span class="arrow"></span>
                        <br/>
                        <a id='modify' href="<? echo $arResult["FILTER_URL"] ?>"
                           target=""><? echo GetMessage("CT_BCSF_FILTER_SHOW") ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--    <div class="clb"></div>-->
</form>
<script type="text/javascript">
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>
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
CModule::IncludeModule("iblock");
global $sortCatalog;
global $orderCatalog;
global $sortCatalog2;
global $orderCatalog2;
global $notAvailable;
?>
<form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>" method="get"
      class="smartfilter bx-filter">
    <div class="filter-title">
        Сортировать по:
    </div>
    <div class="filter_conditions">
        <?
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
                if ($_REQUEST['order'] == 'asc') {
                    $sort = 'PROPERTY_MINIMUM_PRICE';
                } else {
                    $sort = 'PROPERTY_MAXIMUM_PRICE';
                }
                break;
            case 'name':
                $sort = 'NAME';
                $order = 'asc';
                break;
            case 'pop':
                $sort = 'SHOW_COUNTER';
                break;
            case 'new':
                $sort = 'PROPERTYSORT_NEW';
                break;
            case 'default' :
                $sort = 'NAME';
                $order = 'asc';
                break;
        };

        /*if ($APPLICATION->GetCurPage() == '/catalog/plastiny_dlya_stempinga_moyra/') {
            $sort = 'NAME';
            $order = 'desc';
        }
        if ($APPLICATION->GetCurPage() == '/catalog/plastiny_dlya_stempinga_swanky_stamping/') {
            $sort = 'NAME';
            $order = 'desc';
        }
        if ($APPLICATION->GetCurPage() == ' /catalog/zhidkosti_de_lakrua/') {
            $sort = 'NAME';
        }
        if ($APPLICATION->GetCurPage() == '/catalog/plastiny_dlya_stempinga/') {
            $sort = 'SORT';
        }*/
        if (
            CSite::InDir('/catalog/dezinfektsiya_i_sterilizatsiya/')
            || CSite::InDir('/catalog/antiseptiki_i_sredstva_dlya_obrabotki_ruk_/')
            || CSite::InDir('/catalog/dezinfitsiruyushchie_sredstva/')
            || CSite::InDir('/catalog/konteynery_yemkosti_pakety_dlya_sterilizatsii/')
            || CSite::InDir('/catalog/aksessuary_dlya_okrashivaniya_brovey/')
            || CSite::InDir('/catalog/kisti_dlya_okrashivaniya_brovey/')
            || CSite::InDir('/catalog/pintsety_dlya_brovey/')
            || CSite::InDir('/catalog/artex_bazovye_i_topovye_pokrytiya_dlya_gel_laka/')
            || CSite::InDir('/catalog/artex_gel_kraski/')
            || CSite::InDir('/catalog/gel_kraski_diva/')
            || CSite::InDir('/catalog/topovye_pokrytiya_diva/')
            || CSite::InDir('/catalog/akrilovaya_sistema_ingarden/')
            || CSite::InDir('/catalog/gel_kraski_ingarden/')
            || CSite::InDir('/catalog/topovye_pokrytiya_ingarden/')
            || CSite::InDir('/catalog/spa_ukhod_i_soputstvuyushchie_zhidkosti_iva_nails/')
            || CSite::InDir('/catalog/topovye_pokrytiya_iva_nails/')
            || CSite::InDir('/catalog/sredstva_dlya_naturalnykh_nogtey_kinetics/')
            || CSite::InDir('/catalog/bazy_i_topy_kinetics/')
            || CSite::InDir('/catalog/spa_ukhod_1/')
            || CSite::InDir('/catalog/akrigeli_klio/')
            || CSite::InDir('/catalog/soputstvuyushchie_zhidkosti_linto/')
            || CSite::InDir('/catalog/akrigeli_lusso/')
            || CSite::InDir('/catalog/gelevaya_sistema_lusso/')
            || CSite::InDir('/catalog/sredstva_dlya_ukhoda_za_rukami_swanky_monami/')
            || CSite::InDir('/catalog/spa_ukhod/')
            || CSite::InDir('/catalog/bazovye_pokrytiya_neonail/')
            || CSite::InDir('/catalog/gel_kraski_neonail/')
            || CSite::InDir('/catalog/soputstvuyushchie_zhidkosti_neonail/')
            || CSite::InDir('/catalog/rocknail/')
            || CSite::InDir('/catalog/zhidkosti_de_lakrua/')
            || CSite::InDir('/catalog/kisti-dlya-modelirovaniya-i-dizayna/')
            || CSite::InDir('/catalog/soputstvuyushchie_materialy_dlya_manikyura/')
            || CSite::InDir('/catalog/manikyurnyy_instrument_/')
            || CSite::InDir('/catalog/apparaty_dlya_manikyura_i_pedikyura/')
            || CSite::InDir('/catalog/lampy_dlya_sushki_gelya_i_gel_laka/')
            || CSite::InDir('/catalog/pylesborniki/')
            || CSite::InDir('/catalog/strazy_steklo_analog_swarovski/')
            || CSite::InDir('/catalog/strazy_swarovski_originalnye/')
            || CSite::InDir('/catalog/pigmenty_dlya_dizayna/')
            || CSite::InDir('/catalog/materialy_dlya_dizayna_gibkie_lenty_zhemchug_bulonki/')
            || CSite::InDir('/catalog/blesk_dlya_zerkalnogo_manikyura/')
            || CSite::InDir('/catalog/aksessuary_dlya_stempinga_/')
            || CSite::InDir('/catalog/aksessuary_dlya_spilki_dlya_naturalnykh_i_iskusstvennykh_nogteytempinga_/')
            || CSite::InDir('/catalog/shlifovochnye_pilki_bafy/')
            || CSite::InDir('/catalog/frezy_dlya_manikyura/')
            || CSite::InDir('/catalog/soputstvuyushchie_materialy_dlya_manikyura/')
            || CSite::InDir('/catalog/kley_extreme_look/')
            || CSite::InDir('/catalog/zashchita_mastera_ot_allergii/')
            || CSite::InDir('/catalog/izolyatsiya_nizhnikh_resnits/')
            || CSite::InDir('/catalog/kushetki_massazhnye/')
            || CSite::InDir('/catalog/soputstvuyushchie_materialy/')
            || CSite::InDir('/catalog/odezhda_lechi_krasivo/')
            || CSite::InDir('/catalog/odnorazovaya_produktsiya/')
        ) {
            $notAvailable = 'L';
        } else {
            $notAvailable = 'N';
        }

        if (stripos($APPLICATION->GetCurDir(), '/catalog/') !== false) {
            $sortCatalog = 'PROPERTYSORT_NEW';
            $orderCatalog = 'desc';
            $sortCatalog2 = 'NAME';
            $orderCatalog2 = 'asc';
        }
        if (
            CSite::InDir('/catalog/slayder_dizayn_ibdi_nails/')
            || CSite::InDir('/catalog/slayder_dizayny_aksioma/')
            || CSite::InDir('/catalog/slayder_dizayny_lak/')
            || CSite::InDir('/catalog/slayder_dizayny_laque/')) {
            $sortCatalog = 'PROPERTYSORT_NEW';
            $orderCatalog = 'desc';
            $sortCatalog2 = 'NAME';
            $orderCatalog2 = 'desc';
        }

        ?>
        <div class="smart-filter__wrap">
            <a class="smart-filter__item <? if ($_GET == array() or $_GET["sort"] == "default" or $_GET["sort"] == "") {
                echo 'smart-filter__item-active';
            } ?>" href="<?= $APPLICATION->GetCurPageParam("sort=default&order=asc", array("sort", "order")) ?>">
                По умолчанию
            </a>
            <a class="smart-filter__item <? if ($_GET["sort"] == "price" and $_GET["order"] == "asc") {
                echo 'smart-filter__item-active';
            } ?>" href="<?= $APPLICATION->GetCurPageParam("sort=price&order=asc", array("sort", "order")) ?>">
                По убыванию цены
            </a>
            <a class="smart-filter__item <? if ($_GET["sort"] == "price" and $_GET["order"] == "desc") {
                echo 'smart-filter__item-active';
            } ?>" href="<?= $APPLICATION->GetCurPageParam("sort=price&order=desc", array("sort", "order")) ?>">
                Возрастанию цены
            </a>
            <? if ($_REQUEST['sort'] == 'new') { ?>
                <a class="smart-filter__item <? if ($_GET["sort"] == 'new') {
                    echo 'smart-filter__item-active';
                } ?>" href="<?= $APPLICATION->GetCurPageParam("sort=new&order=asc", array("sort", "order")) ?>">
                    Новинкам
                </a>
            <? } else { ?>
                <a class="smart-filter__item <? if ($_GET["sort"] == 'new') {
                    echo 'smart-filter__item-active';
                } ?>" href="<?= $APPLICATION->GetCurPageParam("sort=new&order=desc", array("sort", "order")) ?>">
                    Новинкам
                </a>
            <? } ?>
        </div>
    </div>


    <? foreach ($arResult["ITEMS"] as $key => $arItem)//prices
    {
        $key = $arItem["ENCODED_ID"];
        if (isset($arItem["PRICE"])):
            if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                continue;

            $step_num = 0;
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
            ?>

            <div class="filter__price">
                    <?
                    $min_price = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
                    if ($min_price == '') {
                        $min_price = 0;
                    }
                    $max_price = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
                    if ($max_price == '') {
                        $max_price = $arItem["VALUES"]["MAX"]['VALUE'];
                    }
                    ?>
                    <div class="filter__input-wrap">
                        <div class="filter__price-wrap">
                            <div class="filter__price-button">
                                <?//if($_GET["arrFilter_P1_MIN"] == 0 or $_GET["arrFilter_P1_MIN"] == ""){?>
                                <img src="/local/templates/main/images/filter.svg" alt="фильтр">
                                <?//} else{ ?>
<!--                                    <img onclick="filterReset()"; class="filter__reset-btn" src="/local/templates/main/images/filter_reset.svg" alt="фильтр">-->
                                <?//}?>
                            </div>
                            <div class="filter__price-title">
                                Цена от
                            </div>
                        </div>

                        <script>
                            function filterReset(){
                                <?
                                $min_price = 0;
                                //LocalRedirect($_SERVER['HTTP_REFERER']);
                                ?>
                            }
                        </script>
						<pre style="display: none;">
							<? var_dump($arItem["VALUES"]["MIN"]["CONTROL_ID"]); ?>
						</pre>
                        <input
                                class="min-price__estetica"
                                type="text"
                                name="<? echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                id="<? echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                value="<? echo $min_price ?>"
                                size="5"
                                onkeyup="smartFilter.keyup(this)"
                        />
                        <span class="filter_ruble">₽</span>
                        <span class="filter__price-sub"><?= GetMessage("CT_BCSF_FILTER_TO") ?></span>
                    </div>
                    <div class="filter__input-wrap">
                        <input
                                class="max-price__estetica"
                                type="text"
                                name="<? echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                id="<? echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                value="<? echo number_format($max_price, 0, '.', '') ?>"
                                size="5"
                                onkeyup="smartFilter.keyup(this)"
                        />
                        <span class="filter_ruble">₽</span>
                    </div>
                <input
                        class="btn btn-themes"
                        type="hidden"
                        id="set_filter"
                        name="set_filter"
                        value="применить"
                />
            </div>

		<div style="display: none;" id="colorUnavailableActive_test">
		
		</div>
        <?
        $arJsParams = array(
            "leftSlider" => 'left_slider_' . $key,
            "rightSlider" => 'right_slider_' . $key,
            "tracker" => "drag_tracker_" . $key,
            "trackerWrap" => "drag_track_" . $key,
            "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
            "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
            "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
            "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
            "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
            "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
            "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
            "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
            "precision" => $precision,
			"colorUnavailableActive" => 'colorUnavailableActive_test',
            "colorAvailableActive" => 'colorUnavailableActive_test',
            "colorAvailableInactive" => 'colorUnavailableActive_test',
            //"colorUnavailableActive" => 'colorUnavailableActive_' . $key,
            //"colorAvailableActive" => 'colorAvailableActive_' . $key,
            //"colorAvailableInactive" => 'colorAvailableInactive_' . $key,
        );
        ?>
            <script type="text/javascript">
                BX.ready(function () {
                    window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
                });
            </script>
        <?endif;
    }
    ?>

    <? // echo '<pre>' . print_r($getValue, 1) . '</pre>'; ?>
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

            $step_num = 0;
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
            ?>
        <?
        $arJsParams = array(
            "leftSlider" => 'left_slider_' . $key,
            "rightSlider" => 'right_slider_' . $key,
            "tracker" => "drag_tracker_" . $key,
            "trackerWrap" => "drag_track_" . $key,
            "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
            "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
            "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
            "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
            "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
            "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
            "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
            "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
            "precision" => $precision,
			"colorUnavailableActive" => 'colorUnavailableActive_test',
            "colorAvailableActive" => 'colorUnavailableActive_test',
            "colorAvailableInactive" => 'colorUnavailableActive_test',
            //"colorUnavailableActive" => 'colorUnavailableActive_' . $key,
            //"colorAvailableActive" => 'colorAvailableActive_' . $key,
            //"colorAvailableInactive" => 'colorAvailableInactive_' . $key,
        );
        ?>
            <script type="text/javascript">
                BX.ready(function () {
                    window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
                });
            </script>
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
        <div class="labelf"><span><?= $arItem["NAME"] ?></span>
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
                    <? foreach ($arItem["VALUES"] as $val => $ar): ?>
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
                                        <? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])): ?>
                                            <span class="bx-filter-btn-color-icon"
                                                  style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                        <? endif ?>
                                    </span>
                    </label>
                <? endforeach ?>
                    <?
                    break;
                case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
                    ?>
                    <? foreach ($arItem["VALUES"] as $val => $ar): ?>
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
                                        <? if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])): ?>
                                            <span class="bx-filter-btn-color-icon"
                                                  style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                        <? endif ?>
                                    </span>
                        <span class="bx-filter-param-text" title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                ?> (<span
                                    data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                            endif; ?></span>
                    </label>
                <? endforeach ?>
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
                        <? foreach ($arItem["VALUES"] as $val => $ar): ?>
                            <option value="<? echo $ar["HTML_VALUE_ALT"] ?>" <? echo $ar["CHECKED"] ? 'selected' : '' ?>
                                    id="<?= $ar["CONTROL_ID"] ?>">
                                <?= $ar["VALUE"] ?>
                            </option>
                        <? endforeach ?>
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
                        <? foreach ($arItem["VALUES"] as $val => $ar): ?>
                            <option value="<? echo $ar["HTML_VALUE_ALT"] ?>" <? echo $ar["CHECKED"] ? 'selected"' : '' ?>
                                    id="<?= $ar["CONTROL_ID"] ?>">
                                <?= $ar["VALUE"] ?>
                            </option>
                        <? endforeach ?>
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
                        <? foreach ($arItem["VALUES"] as $val => $ar): ?>
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
                        <? endforeach; ?>
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
                        <? foreach ($arItem["VALUES"] as $val => $ar): ?>
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
                        <? endforeach; ?>
                    </div>
                <?
            }
            ?>
        </div>
        <?
    }
    ?>
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
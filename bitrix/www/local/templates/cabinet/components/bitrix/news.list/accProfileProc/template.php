<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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


    if ($USER->IsAuthorized()) { ?>
        <script>
            // Создаем пустой массив для дальнейшего перебора подключенных аккаунтов из php в JS
            accounts = [];
        </script>
        <?
        // Подключаем модуль инфоблоков
        if (CModule::IncludeModule("iblock")):
            // Параметры выборки полей
            $arSelect = Array("ID", "NAME", "PROPERTY_SSID", "PROPERTY_ID", "PROPERTY_CSRFTOKEN", "PROPERTY_MAIN");
            // ID инфоблока где хранятся аккаунты задается в setting.php
            $arFilter = Array("IBLOCK_ID" => $IBLOCK_ID);
            // Запрос
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
            $i = 0;
            // Цикл перебора результатов
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                // Условие что запись аккаунта пренадлежит пользователю
                if ($arFields['PROPERTY_ID_VALUE'] == $USER->GetId()) {
                    ?>
                    <script>
                        // Формируем массив с аккаунтами
                        accounts.push(["<?=$arFields['NAME']?>", "<?=$i?>", "<?=$arFields['PROPERTY_SSID_VALUE']?>", "<?=$arFields['ID']?>", "<?=$arFields['PROPERTY_CSRFTOKEN_VALUE']?>", "<?=$arFields['PROPERTY_MAIN_VALUE']?>"]);
                    </script>
                    <?
                }
                $i++;
            }
        endif;
        ?>
    <!--    Блок предварительной загрузки-->
    <div class="" id="spinner">
        <div class="d-flex  ht-300 pos-relative align-items-center">
            <img src="<?= SITE_TEMPLATE_PATH ?>/img/loading.gif" alt="Загрузка">
        </div>
        <div class="d-flex  ht-200 pos-relative align-items-center">
            <div class="progress-bar progress-bar-lg wd-100p" role="progressbar" id="progressLine" aria-valuenow="0"
                 aria-valuemin="0" aria-valuemax="100"><span id="progress"></span></div>
        </div>
        <div class="row">
            <button type="button" onclick="reloadPage()"
                    class="btn btn-success tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Остановить
            </button>
        </div>
    </div>
    <!--Главный блок-->
    <div class="br-pagebody" id="pageWindow">
        <div class="br-section-wrapper">
            <!--Блок ошибок-->
            <div class="row" id="alert"></div>
            <!--            Форма запроса постов-->
            <form>
                <div class="row" id="searchPanel">
                    <div class="col-lg">
                        Имя аккаунта:<input class="form-control mr-sm-2 my-sm-0" id="accName" placeholder="Имя аккаунта"
                                            type="text">
                    </div><!-- col -->
                    <div class="col-lg mg-t-10 mg-lg-t-0">
                        Профиль:<select class="form-control mr-sm-2 my-sm-0" id="accCount">
                            <?foreach($arResult["ITEMS"] as $arItem):?>
                                <?foreach($arResult["ITEMS"] as $arItem):?>
                                    <option data-id="<?=$arItem["ID"]?>"
                                            data-acccountmin="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_COUNT_MIN"]["VALUE"]?>"
                                            data-accpostmin="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_POST_MIN"]["VALUE"]?>"
                                            data-accpostmax="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_POST_MAX"]["VALUE"]?>"
                                            data-accpostvideop="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_POST_VIDEO_P"]["VALUE"]?>"
                                            data-acca="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_A"]["VALUE"]?>"
                                            data-accb="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_B"]["VALUE"]?>"
                                            data-accc="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_C"]["VALUE"]?>"
                                            data-accx1="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_X1"]["VALUE"]?>"
                                            data-accx2="<?=$arItem["DISPLAY_PROPERTIES"]["ACC_X2"]["VALUE"]?>"><?echo $arItem["NAME"]?></option>
                                <?endforeach;?>
                            <?endforeach;?>
                        </select>
                    </div><!-- col -->

                    <div class="col-lg mg-t-10 mg-lg-t-0">
                        запрос от:<select id="accountsMenu" class="form-control mr-sm-2 my-sm-0">
                        </select>
                    </div><!-- col -->
                    <div class="col-lg mg-t-10 mg-lg-t-0">
                        <br>
                        <button type="button" onclick="getPost(this.form)"
                                class="btn btn-success tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Запросить
                        </button>
                    </div>
                </div>
            </form>
            <!--            Блок в который складываем посты-->
            <div class="row" id="postContentDiv"></div>
        </div>
    </div>
    <script>
        // Подключаемся к серверу ws node.js
        ws = new WebSocket('ws://15cek.ru:8090/sessid=<?=session_id()?>');
        // Скрываем вспомогательные блоки
        document.getElementById("spinner").style.display = "none";
        accounts.forEach(function (item) {
// Условие если аккаунт помечен как главный, выбираем его по дефаулту
            if (item[5] == 1) {
                $("#accountsMenu").append('<option selected  data-item="' + item[1] + '" value="' + item[0] + '" data-csrftoken="' + item[4] + '" data-ssid="' + item[2] + '" data-id="' + item[3] + '">' + item[0] + '</option>');

            } else {
                $("#accountsMenu").append('<option  data-item="' + item[1] + '" value="' + item[0] + '" data-csrftoken="' + item[4] + '" data-ssid="' + item[2] + '" data-id="' + item[3] + '">' + item[0] + '</option>');
            }
        })
    </script>
<? } ?>



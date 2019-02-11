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
?>

<div class="br-pagebody">
    <div class="br-section-wrapper">
        <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Настройка профилей для многопоточного анализа </h6>
        <p class="mg-b-25 mg-lg-b-30">
            Создание профилей
        </p>
        <div class="row">
            <div class="col-md-4 mg-r-0 mg-lg-r-0">
                <input class="form-control" id="name" placeholder="введите имя профиля" type="text">
            </div>
            <div class="col-md-4 mg-r-0 mg-lg-r-0">

            <select class="form-control select2 select2-hidden-accessible" id="accountsMenuDefault" aria-hidden="true" onChange="selectProfile(this.options [this.selectedIndex])">
                <option disabled selected >Не выбрано</option>
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
            </select>

            </div>
        </div>
        <p class="mg-b-10 mg-lg-b-10 mg-t-30 mg-lg-t-30">
            Количество постов на аккаунте (если меньше, то пропускаем):
        </p>
        <div class="row">
            <div class="col-md-4">
                <input class="form-control" id="accCountMin" min="0" placeholder="введите число" type="number">
            </div>
            <!-- col -->
        </div>
        <p class="mg-b-10 mg-lg-b-10 mg-t-30 mg-lg-t-30">
            Анализ постов с.. по .. (пост №1 - самый последний пост):
        </p>
        <div class="row">
            <div class="col-md-2 mg-r-0 mg-lg-r-0">
                С: <input class="form-control" min="0" id="accPostMin" placeholder="введите число" type="number">
            </div>
            <div class="col-md-2 mg-r-0 mg-lg-r-0">
                По: <input class="form-control" min="0" id="accPostMax" placeholder="введите число" type="number">
            </div>
        </div>
        <p class="mg-b-10 mg-lg-b-10 mg-t-30 mg-lg-t-30">
            Не менее % постов с видео из выборки (если меньше, то пропускаем):
        </p>
        <div class="row">
            <div class="col-md-4">
                <input class="form-control" min="0" id="accPostVideoP" placeholder="введите число" type="number">
            </div>
            <!-- col -->
        </div>
        <p class="mg-b-25 mg-lg-b-30 mg-t-25 mg-lg-t-30">
            Анализ активности:
        </p>
        <p class="mg-b-10 mg-lg-b-10 mg-t-30 mg-lg-t-30">
            Для аккаунтов больше X1 подписчиков вовлеченность должна быть не менее А процентов:
        </p>
        <div class="row">
            <div class="col-md-2 mg-r-0 mg-lg-r-0">
                A: <input class="form-control" min="0" max="0" id="accA" placeholder="введите %" type="number">
            </div>
            <div class="col-md-2 mg-r-0 mg-lg-r-0">
                X1: <input class="form-control" min="0" id="accX1" placeholder="введите число" type="number">
            </div>
        </div>
        <p class="mg-b-10 mg-lg-b-10 mg-t-30 mg-lg-t-30">
            Для аккаунтов от X2 до X1 подписчиков вовлеченность должна быть не менее B процентов:
        </p>
        <div class="row">
            <div class="col-md-2 mg-r-0 mg-lg-r-0">
                B: <input class="form-control" min="0" max="0" id="accB" placeholder="введите %" type="number">
            </div>
            <div class="col-md-2 mg-r-0 mg-lg-r-0">
                X2: <input class="form-control" min="0" id="accX2" placeholder="введите число" type="number">
            </div>
        </div>
        <p class="mg-b-10 mg-lg-b-10 mg-t-30 mg-lg-t-30">
            Для аккаунтов от меньше X2 подписчиков вовлеченность должна быть не менее С процентов:
        </p>
        <div class="row">
            <div class="col-md-2 mg-r-0 mg-lg-r-0">
                C: <input class="form-control" min="0" max="0" id="accC" placeholder="введите %" type="number">
            </div>
            <div class="col-md-2 mg-r-0 mg-lg-r-0 mg-t-20 mg-lg-t-20">
                <a href="#" onclick="addProfileAcc()" class="btn btn-success tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Сохранить</a>
            </div>
            <div class="col-md-2 mg-r-0 mg-lg-r-0 mg-t-20 mg-lg-t-20">
                <a href="#" onclick="delProfileAcc(this.dataset.id)" style="display: none" id="delButton" class="btn btn-danger tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Удалить</a>
            </div>
        </div>
        <input type="hidden" id="accId" value="0">
    </div>
</div>
<script>
function selectProfile(elem) {
    console.log(elem.dataset.acccountmin)
document.querySelector('#delButton').style.display = 'block'
    document.querySelector('#delButton').dataset.id = elem.dataset.id
    document.querySelector('#name').value = elem.value
    document.querySelector('#accId').value = elem.dataset.id
    document.querySelector('#accCountMin').value = elem.dataset.acccountmin
    document.querySelector('#accPostMin').value = elem.dataset.accpostmin
    document.querySelector('#accPostMax').value = elem.dataset.accpostmax
    document.querySelector('#accPostVideoP').value = elem.dataset.accpostvideop
    document.querySelector('#accA').value = elem.dataset.acca
    document.querySelector('#accB').value = elem.dataset.accb
    document.querySelector('#accC').value = elem.dataset.accc
    document.querySelector('#accX1').value = elem.dataset.accx1
    document.querySelector('#accX2').value = elem.dataset.accx2


}

function delProfileAcc(id) {
console.log(id)

    BX.ajax({
        url: '/cabinet/api.php',
        data: {
            sessid: BX.bitrix_sessid(),
            type: 'delProfileAcc',
            id: id
        },
        method: 'POST',
        dataType: 'json',
        timeout: 30,
        async: true,
        processData: true,
        scriptsRunFirst: true,
        emulateOnload: true,
        start: true,
        cache: false,
        onsuccess: function (data) {
            console.log(data)
            if(data=='Success'){

                    alert('Профиль удален!')

                window.location.reload();
            }

        },
        onfailure: function () {
            console.log("error");

        }
    });
}
    function addProfileAcc() {
        accId = document.querySelector('#accId').value;
        accName = document.querySelector('#name').value;
        accCountMin = document.querySelector('#accCountMin').value;
        accPostMin = document.querySelector('#accPostMin').value;
        accPostMax = document.querySelector('#accPostMax').value;
        accPostVideoP = document.querySelector('#accPostVideoP').value;
        accA = document.querySelector('#accA').value;
        accB = document.querySelector('#accB').value;
        accC = document.querySelector('#accC').value;
        accX1 = document.querySelector('#accX1').value;
        accX2 = document.querySelector('#accX2').value;

        if(accName, accCountMin, accPostMin, accPostMax, accPostVideoP, accA, accB, accC, accX1, accX2){
            console.log('good')

            BX.ajax({
                url: '/cabinet/api.php',
                data: {
                    sessid: BX.bitrix_sessid(),
                    type: 'addProfileAcc',
                    accName: accName,
                    accId: accId,
                    accCountMin: accCountMin,
                    accPostMin: accPostMin,
                    accPostMax: accPostMax,
                    accPostVideoP: accPostVideoP,
                    accA: accA,
                    accB: accB,
                    accC: accC,
                    accX1: accX1,
                    accX2: accX2
                },
                method: 'POST',
                dataType: 'json',
                timeout: 30,
                async: true,
                processData: true,
                scriptsRunFirst: true,
                emulateOnload: true,
                start: true,
                cache: false,
                onsuccess: function (data) {
                    console.log(data)
                    if(data=='Success'){
                        if(accId==0){
                            alert('Профиль создан!')

                        }
                        else{
                            alert('Профиль изменен!')

                        }
                        window.location.reload();
                    }

                },
                onfailure: function () {
                    console.log("error");

                }
            });
        }
        else{
            console.log('bad')
        }

    }
</script>


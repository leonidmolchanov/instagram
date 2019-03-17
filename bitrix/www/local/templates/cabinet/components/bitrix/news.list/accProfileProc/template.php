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
            accountsList=[];
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
                    <div class="col-lg" id="accNameDiv">
                        Имя аккаунта:<input class="form-control mr-sm-2 my-sm-0" id="accName" placeholder="Имя аккаунта"
                                            type="text">
                    </div><!-- col -->
                    <div id="accCountDiv" class="col-lg mg-t-10 mg-lg-t-0">
                        Профиль:<select onchange="changeAccountSelect(this)" class="form-control mr-sm-2 my-sm-0" id="accCount">
                                <?
                                if($_REQUEST['accID']){
                                    $accID = $_REQUEST['accID'];
                                }

                                foreach($arResult["ITEMS"] as $arItem):
                                    if(!$accID) {
                                        $accID = $arItem['ID'];
                                    }
                                    ?>
                                    <option<?if($arItem["ID"]==$accID): if($arItem["DISPLAY_PROPERTIES"]["STATUS"]["VALUE"]==1):$state=true; else:$state=false; endif;?> selected <?endif;?>  data-id="<?=$arItem["ID"]?>"
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
                    </div><!-- col -->

                    <div id="accountsMenuDiv" class="col-lg mg-t-10 mg-lg-t-0">
                        запрос от:<select id="accountsMenu" class="form-control mr-sm-2 my-sm-0">
                        </select>
                    </div><!-- col -->
                    <div  id="accProcessDiv" class="col-lg mg-t-10 mg-lg-t-0">
                        <br>
                        <button type="button" id="accProcess" onclick="getAccList(this.form)"
                                class="btn btn-success tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Запросить
                        </button>
                    </div>
                </div>
            </form>
            <!--            Блок в который складываем посты-->
            <div class="row" id="accListDiv">
                <ul id="accList">
                </ul>

            </div>
            <div class="col-lg mg-t-10 mg-lg-t-0">
                <button id="create_button_list" onclick = "createAccList1()" style="display: none" type="button" onclick=""
                        class="btn btn-success tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Создать список №1
                </button>
                <div class="row">
                    <div class="col-lg mg-t-10 mg-lg-t-0">
                <?if(!$state):?>
                <button id="startProc" type="button" onclick="changeAccState('start')"
                        class="btn btn-success tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Запустить процесс сканирования
                </button>
                <?else:?>
                <button type="button" id="stopProc" onclick="changeAccState('stop')"
                        class="btn btn-danger tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Остановить процесс сканирования
                </button>
                <?endif;?>
                    </div>
                    <div class="col-lg mg-t-10 mg-lg-t-0">
                <select  onchange="selectListFn(this.options[this.selectedIndex].value)" class="form-control mr-sm-2 my-sm-0" id="selectList">
                    <option value="list0">Список 0</option>
                    <option value="list1">Список 1</option>
                    <option value="list2"> Список 2</option>
                    <option selected  value="all"> Все</option>
                </select>
                    </div>
                </div>
                <script>
                </script>
            </div><?
            $accList=[];
            // Подключаем модуль инфоблоков
            if (CModule::IncludeModule("iblock")):
            // Параметры выборки полей
            $arSelect = Array("ID", "NAME", "PROPERTY_PROFILE.ID", "PROPERTY_STATUS");
            // ID инфоблока где хранятся аккаунты задается в setting.php
            $arFilter = Array("IBLOCK_ID" => 7, "PROPERTY_PROFILE.ID"=>$accID);
            // Запрос
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
            $i = 0;
            // Цикл перебора результатов
            while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
                array_push($accList, $arFields);
            }
        endif;
            if (!empty($accList)){
                ?>
                <script>
                    $("#accountsMenuDiv").hide()
                    $("#accNameDiv").hide()
                    $("#accProcessDiv").hide()


                </script>
                <?
            }
            else{
                ?>
                <script>
                    $("#startProc").hide()
                    $("#stopProc").hide()
                </script>

                <?
            }
        ?>
<?if(!empty($accList)):?>
    <div class="row">
            <table id="datatable1" class="table display responsive nowrap dataTable no-footer dtr-inline collapsed" role="grid" aria-describedby="datatable1_info" style="width: 2066px;">
                <thead>
                <tr role="row">


                    <th class="wd-15p" style="width: 286px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Имя</th>
                    <th class="wd-10p "  style="width: 182px;" aria-label="Salary: activate to sort column ascending">Статус</th>
                </tr>
                </thead>
                <tbody>
                <?foreach ($accList as $item):?>
                <tr role="row" class="odd
<?if($item["PROPERTY_STATUS_VALUE"]==0):?>
list0
<?endif;?>
<?if($item["PROPERTY_STATUS_VALUE"]==1):?>
list1
<?endif;?>
<?if($item["PROPERTY_STATUS_VALUE"]==2):?>
list2
<?endif;?>
">
                    <td tabindex="0" class="sorting_1"><?=$item["NAME"]?></td>
                    <td>
                    <?if($item["PROPERTY_STATUS_VALUE"]==0):?>
                        Не отсканирован<?endif;?>
                        <?if($item["PROPERTY_STATUS_VALUE"]==1):?>
                        Прошел фильтрацию<?endif;?>
                        <?if($item["PROPERTY_STATUS_VALUE"]==2):?>
                        Отброшен<?endif;?></td>
                </tr>
                <?endforeach;?>
                </tbody>
            </table>
    </div>
            <?endif;?>
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

        function post(URL, PARAMS) {
            var temp=document.createElement("form");
            temp.action=URL;
            temp.method="POST";
            temp.style.display="none";
            for(var x in PARAMS) {
                var opt=document.createElement("textarea");
                opt.name=x;
                opt.value=PARAMS[x];
                temp.appendChild(opt);
            }
            document.body.appendChild(temp);
            temp.submit();
            return temp;
        }
function changeAccountSelect(elem) {
    console.log($(elem).find(":selected").data("id"))
    post(window.location.href, {
        accID:$(elem).find(":selected").data("id")
    })
}
function selectListFn(className) {
    console.log(className)
    $('#datatable1 tbody tr').hide()
    if(className=='all') {
        $('#datatable1 tbody tr').show()
    }
    else{
        $('#datatable1 tbody tr.' + className + '').show()

    }
}
function changeAccState(proc) {
    BX.ajax({
        url: '/cabinet/api.php',
        data: {
            sessid: BX.bitrix_sessid(),
            type: 'changeAccState',
            proc: proc,
            iblockid: <?=$accID?>
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
           alert("Cтатус изменен!")
            post(window.location.href, {
                accID:$('#accCount').find(":selected").data("id")
            })
        },
        onfailure: function () {
            console.log("error");

        }
    });

}
        function getAccList(data) {
            // Проверяем что введено поле имяни анализируемого аккаунта
            if ($("#accName").val()) {
                // Отправляем запрос на сервер
                ws.send(JSON.stringify({
                    type: 'startAccProfile',
                    csrftoken: $("#accountsMenu option:selected").attr('data-csrftoken'), // Забираем токен через свойство
                    sessionid: $("#accountsMenu option:selected").attr('data-ssid'), // Забираем ssid через свойство
                    accName: $("#accName").val(),
                    count: 2 // Переменная которая отвечает за кол-во проходов (постов) если 0 то неограниченно
                }));
                // Показываем прелоадер
                // document.getElementById("spinner").style.display = "block";
                // document.getElementById("pageWindow").style.display = "none";
            }
            else {
                alert("Не введено имя аккаунта!")
            }
        }

        // Подкючение к серверу
        ws.onopen = function() {
            console.log("Соединение установлено...");
        };
        // Если произошло отключение от сервера
        ws.onclose = function(event) {
            console.log("соединение закрыто...");
        }
        // Секция взаимодействия общения клиент-сервер
        ws.onmessage = function(message) {
            // Парсим ответ от сервера
            message = JSON.parse(message.data)
            // Если сервер сообщает о начале загрузки постов
            if(message.action == "loadPostModule"){
                document.getElementById('progress').innerText = "";
                console.log("Запускаем процесс загрузки постов...");
                getPostArr=[];
            }
            // Если сервер подписывает нас на аккаунт
            else if(message.action == "subscription"){
                // Если у него это получилось
                if(message.head=="OK"){
                    // Чистим окно сошибкой
                    $("#alert").empty();
                    // Вставляем окно с информацией
                    $("#alert").append('<div class="alert alert-bordered pd-y-20" role="alert">\n' +
                        '                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '                        <span aria-hidden="true">&times;</span>\n' +
                        '                    </button>\n' +
                        '                    <div class="d-flex align-items-center justify-content-start">\n' +
                        '                        <i class="icon ion-ios-close success-icon tx-52 tx-success mg-r-20"></i>\n' +
                        '                        <div>\n' +
                        '                            <h5 class="mg-b-2 tx-success">Заявка на подписку отправлена!</h5>\n' +
                        '                        </div>\n' +
                        '                    </div>\n' +
                        '                </div>');
                }
                else{
                    alert("Ошибка подписки на сервер!")
                }
            }
// Начало выгрузки постов (первый пост за которым пойдет серия или не пойдет
            else if(message.action == "startSendPosts"){
                // Чистим ранее полученный контент
                $("#postContentDiv").empty()
                // Чистим ошибки
                $("#alert").empty();
                // Определяем массив куда будем складывать полученные посты или очищаем его если он не пустой.
                getPostArr=[];
                // Собираем посты в массив
                message.body.forEach(function (item){
                    getPostArr.push(item)
                })
                console.log('Получена первая партия постов...')
            }
            // Дальнейший процесс выгрузки частями
            else if(message.action == "procSendPosts") {
                // Добавляем следующие посты в общий массиы
                message.body.forEach(function (item){
                    getPostArr.push(item)
                })
                // Обновляем информацию о ходе выполнения
                document.getElementById('progress').innerText = message.procent+"%";
                procentLine = (message.procent / 10).toFixed()
                $("#progressLine").attr('class', 'progress-bar progress-bar-lg wd-'+procentLine *10+'p');
            }
            // Конец выгрузки
            else if(message.action == "endSendPosts") {
                // Получаем последнюю партию постов
                message.body.forEach(function (item){
                    getPostArr.push(item)
                })
                console.log("Конец запроса...");
                // Скрываем лоадер показываем контент
                $("#create_button_list").hide();
                document.getElementById("spinner").style.display = "none";
                document.getElementById("pageWindow").style.display="block";
// Применяем фильтры к полученному контенту если по Рейтингу то:
                if($("#filterType option:selected").val() =="rang") {
                    console.log("Работает фильтр Рейтинг...")
                    getPostArr.sort(function (a, b) {
                        return b[0] - a[0];
                    });
                }
                // Применяем фильтры к полученному контенту если по Умному фильтру то:
                else if($("#filterType option:selected").val()=="highFiler")
                {
                    console.log("Работает Умный фильтр ...")
                    getPostArr.forEach(function(item, key){
                        // Получаем параметры умного фильтра
                        start = key -  Number($("#HighFilter option:selected").val());
                        end = key +  Number($("#HighFilter option:selected").val());
                        startModify = start ;
                        endModify = end ;
                        // Цикл устанавливает края, чтобы не попасть на не существующие ячейки
                        for (i = start; i < end; i++) {
                            if(!getPostArr[i]){
                                if(i<0){
                                    startModify +=1;
                                    endModify +=1;
                                }
                                else if(i>0){
                                    startModify -=1;
                                    endModify -=1;
                                }
                            }
                        }
                        countSum = 0;
                        y=0;
                        for (i = startModify; i < endModify; i++) {
                            if(i !== key) {
                                countSum += getPostArr[i][0];
                                y++
                            }
                        }
                        countSum = countSum / y ;
                        magicInt = getPostArr[key][0] / countSum
                        getPostArr[key][3] = magicInt.toFixed(2) ;
                    });
                    getPostArr.sort(function (a, b) {
                        return b[3] - a[3];
                    });
                }
// Показываем контент
                showLoadContent(getPostArr);
            }
            // Если получили ошибку
            else if(message.action == "error") {
                console.log("Получена ошибка...");
                subscriptionContent = "";
                // Если обибка по подписке вставляем кнопку подписаться...
                if(message.pageId){
                    subscriptionContent =  '<button type="button" onclick="subscriptionAccount('+message.pageId+')" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Пописаться</button>';
                }
                $("#postContentDiv").empty();
                document.getElementById("spinner").style.display = "none";
                document.getElementById("pageWindow").style.display="block";
                $("#alert").append('<div class="alert alert-danger alert-bordered pd-y-20" role="alert">\n' +
                    '                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                    '                        <span aria-hidden="true">&times;</span>\n' +
                    '                    </button>\n' +
                    '                    <div class="d-flex align-items-center justify-content-start">\n' +
                    '                        <i class="icon ion-ios-close alert-icon tx-52 tx-danger mg-r-20"></i>\n' +
                    '                        <div>\n' +
                    '                            <h5 class="mg-b-2 tx-danger">'+message.head+'</h5>\n' +
                    '                            <p class="mg-b-0 tx-gray">'+message.body+'</p>\n' +
                    subscriptionContent+
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                </div>');
            }
            // Если получен прогресс
            else if(message.action == "inform") {
                document.getElementById('progress').innerText = message.body;
            }
            else if (message.action=="accListLoad"){
                accountsList=[];
                $("#create_button_list").show();

                arr=message.body;

                let result = arr.reduce(function(sum, current) {
                    accountsList.push(current.node.username)
                    return sum + '<li>'+current.node.username+'</li>';
                }, 0)
document.getElementById('accList').innerHTML = result
                console.log(accountsList)
            }
        };
        // Если подключение к серверу закрылось
        ws.onclose = function(error){
            // Скрываем панель поиска
            document.getElementById("searchPanel").style.display="none";
            // Выводит алерт
            $("#alert").append('<div class="alert alert-danger alert-bordered pd-y-20" role="alert">\n' +
                '                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '                        <span aria-hidden="true">&times;</span>\n' +
                '                    </button>\n' +
                '                    <div class="d-flex align-items-center justify-content-start">\n' +
                '                        <i class="icon ion-ios-close alert-icon tx-52 tx-danger mg-r-20"></i>\n' +
                '                        <div>\n' +
                '                            <h5 class="mg-b-2 tx-danger">Произошло отключение от сервера!</h5>\n' +
                '                            <p class="mg-b-0 tx-gray">Проблемы с подключением к серверу код ошибки: '+error.code+', Попробуйте обновить страницу или обратитесь к администратору.</p>\n' +
                ' <button type="button" onclick="reloadPage()"  data-dismiss="alert" aria-label="Close">\n' +
                '                        <span aria-hidden="true">Обновить</span>\n' +
                '                    </button>\n' +
                '</div>\n' +
                '                    </div>\n' +
                '                </div>');
        }

        
        function createAccList1() {
            BX.ajax({
                url: '/cabinet/api.php',
                data: {
                    sessid: BX.bitrix_sessid(),
                    type: 'createAccList1',
                    data: JSON.stringify(accountsList),
                    iblockid: <?=$accID?>
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

                },
                onfailure: function () {
                    console.log("error");

                }
            });
        }
    </script>
        <? } ?>



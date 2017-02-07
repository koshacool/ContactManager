;
define(['Helper'], function (baseScript) {

    /**
     * [getNewData description]
     * Get new sorted data from server by ajax technology
     *
     * @param  object buttonObj Pushed button object
     * @return boolean
     */
    var getNewData = function () {
        var url = baseScript.getUrl();//Get url without params
        url += baseScript.getUrlParams();//Get and add standart url params
        url += '&' + this.name + '=' + this.value;//Add to url params pushed button name and value

        if (baseScript.getUrl() == '/contact/select') {
            saveFormToCookie('post', this);
        }

        $.ajax({ //Start AJAX query
            type: "GET",
            url: url,
            headers: {"Accept": "application/ajax"},
            success: function (result) {
                displayContactList(JSON.parse(result), baseScript.getUrl());//Display contact list with new data
                displayPagination(JSON.parse(result));//Display pagination buttons with new data

                url = baseScript.getUrl();//Get url without params
                url += baseScript.getUrlParams();//Add standart url params
                baseScript.setLocation(url);//Set valid url

                setEvents();
            }
        });
    };

    /**
     * [displayContactList description]
     * Display new data on page without reload page
     * @param  object modelObj Object getted from server
     * @return void
     */
    var displayContactList = function (modelObj, url) {
        var additionalData = modelObj.additionalData;//Get additional data
        delete modelObj.additionalData;

        //Set additional data in page
        $('#currentPage').val(additionalData['currentPage']);
        $('#mainSortColumn').val(additionalData['mainSortColumn']);
        $('#sortDirectionMainColumn').val(additionalData['sortDirectionMainColumn']);
        $('#sortDirectionSecondaryColumn').val(additionalData['sortDirectionSecondaryColumn']);

        //Additional data for /contact/select page
        if (url == '/contact/select') {
            // document.getElementById('arrSelectedEmails').value = additionalData['arrSelectedEmails'];
            $('#arrayLastShownRowsOnPage').val(additionalData['arrayLastShownRowsOnPage']);
            $('#emails').val(additionalData['emails']);
            $('#selectAll').prop('checked', additionalData['selectAll']);
        } else {
            var offset = additionalData.offset; //Get contact sequence number
        }

        var mainDiv = $('<div></div>').attr({
            id: 'list',
        });//Create main block


        //Create contact data for display in page
        for (var contact in modelObj) {
            var divBlock = $('<div></div>').attr({
                class: 'list',
            }); //Create div block

            //Display checkbox for each contact if it's page '/contact/select'
            if (url == '/contact/select') {
                var select = false;
                var arrEmails = additionalData['arrForVerifySelect'];

                if (arrEmails != null && arrEmails.hasOwnProperty(modelObj[contact]['id'])) {
                    select = true;
                }

                //Create select block and add it to main block
                var selectContact = $('<input></input>').attr({
                    class: 'sequence checkbox',
                    type: 'checkbox',
                    name: modelObj[contact]['id'],
                    checked: select,
                    value: modelObj[contact]['email'],

                });
                divBlock.append(selectContact);

            } else {
                //Create Sequence block and add it to main block for main page
                offset++;
                var sequenceNumber = $('<div></div>').attr({
                    class: 'sequence',
                }).text(offset + "."); //Create div block
                divBlock.append(sequenceNumber);
            }

            //Create block with last name and add it to main block
            var last = $('<div></div>').attr({
                class: 'lastForJs',
                id: 'last'
            }).text(((!modelObj[contact]['last']) ? '' : modelObj[contact]['last']));
            divBlock.append(last);

            //Create block with first name and add it to main block
            var first = $('<div></div>').attr({
                class: 'first',
                id: 'first'
            }).text(((!modelObj[contact]['first']) ? '' : modelObj[contact]['first']));
            divBlock.append(first);

            //Create block with email name and add it to main block
            var email = $('<div></div>').attr({
                class: 'emailForJs',
                id: 'email'
            }).text(modelObj[contact]['email']);
            divBlock.append(email);

            //Create block with phone name and add it to main block
            var phone = $('<div></div>').attr({
                class: (url == '/contact/select') ? 'phoneForJs' : 'phoneForJsMainPage',
                id: 'phone'
            }).text(((!modelObj[contact]['phone']) ? '' : modelObj[contact]['phone']));
            divBlock.append(phone);

            //Create buttons for edit/view/remove contact
            if (url == '/contact/showlist') {
                //Create main block for buttons
                var bottons = $('<div></div>').attr({
                    class: 'actionsBottom'
                });

                //Create edit button
                var edit = $('<div></div>').attr({
                    class: 'editButton'
                });
                var div = $('<div></div>');
                var a = $('<a></a>').attr({
                    href: '/contact/record/' + modelObj[contact]['id']
                }).html('<span>edit</span>');
                div.append(a);
                edit.append(div);

                //Create delete buttons
                var blockDeleteButtons = $('<div></div>').attr({
                    class: 'actionDelete'
                });

                //Create view/delete block
                var deleteButton = $('<div></div>').attr({
                    class: 'deleteButton'
                });

                //Create edit button
                var div = $('<div></div>');
                var a = $('<a></a>').attr({
                    href: '/contact/view/' + modelObj[contact]['id']
                }).html('<span>view</span>');
                div.append(a);
                deleteButton.append(div);

                //Create delete button
                var xButton = $('<div></div>').attr({
                    class: 'xButton'
                }).css('marginLeft', '1px');
                var div = $('<div></div>');
                var a = $('<a></a>').attr({
                    href: '/contact/confirm/' + modelObj[contact]['id']
                }).html('<span>X</span>');
                div.append(a);
                xButton.append(div);

                blockDeleteButtons.append(deleteButton);
                blockDeleteButtons.append(xButton);

                bottons.append(edit);
                bottons.append(blockDeleteButtons);
                divBlock.append(bottons);
            }
            mainDiv.append(divBlock);
        }

        $('#list').replaceWith(mainDiv);
        $('#first').prop('class', (additionalData['mainSortColumn'] == 'first') ? 'sortColorActiveButton' : 'sortColorNotActiveButton'); //Set class for active or not active button
        $('#last').prop('class', (additionalData['mainSortColumn'] == 'last') ? 'sortColorActiveButton' : 'sortColorNotActiveButton'); //Set class for active or not active button
    };

    /**
     * [displayPagination description]
     * Display pagination button with new data
     *
     * @param  object modelObj Object getted from server
     * @return void
     */
    var displayPagination = function (modelObj) {
        var additionalData = modelObj.additionalData;//Get additional data

        //Create pagination button for display on page
        if (additionalData['numberOfPages'] > 1) {
            $('#prev').attr({
                value: additionalData["currentPage"] - 1,
                disabled: (additionalData['currentPage'] == 1) ? true : false,
            });//Change prev button's values


            $('#next').attr({
                value: (+additionalData['currentPage'] + +1),
                disabled: (additionalData['currentPage'] == additionalData['numberOfPages']) ? true : false,
            });//Change next button's values

            //Create div block for change block pagination in page
            var mainDiv = $('<div></div>').attr({
                class: 'numberPagesBlock',
                id: 'numberPagesBlock',
            });

            mainDiv.append($('<span></span>').text('Page:'));//Add text to block with page's numbers

            //Create first button with not active params
            if (additionalData['firstShowPage'] > 1) {
                var firstShowButton = $('<button></button>').attr({
                    class: 'pageNumber',
                    id: '1',
                    name: 'showPage',
                    type: 'submit',
                    value: '1',
                }).text('1');
                mainDiv.append(firstShowButton);
            }

            //When active page is bigger then params 'showNumberPages', display button '...' to go back
            if (additionalData['firstShowPage'] > additionalData['showNumberPages']) {
                var buttonOffsetPrev = $('<button></button>').attr({
                    class: 'pageNumber',
                    id: (additionalData['firstShowPage'] - additionalData['showNumberPages']) < 1 ? 1 : additionalData['firstShowPage'] - additionalData['showNumberPages'],
                    name: 'showPage',
                    type: 'submit',
                    value: (additionalData['firstShowPage'] - additionalData['showNumberPages']) < 1 ? 1 : additionalData['firstShowPage'] - additionalData['showNumberPages'],
                }).text('...');

                mainDiv.append(buttonOffsetPrev);
            }

            //Create number buttons
            while (additionalData['firstShowPage'] <= additionalData['lastShowPage']) {
                var buttonNumber = $('<button></button>').attr({
                    class: (additionalData['firstShowPage'] == additionalData['currentPage']) ? 'activePageNumber' : 'pageNumber',
                    id: additionalData['firstShowPage'],
                    name: 'showPage',
                    type: 'submit',
                    value: additionalData['firstShowPage'],
                }).text(additionalData['firstShowPage']);

                mainDiv.append(buttonNumber);
                additionalData['firstShowPage']++;
            }

            //When last shown page is less then params 'numberOfPages', display button '...' to go forward
            if (additionalData['lastShowPage'] < additionalData['numberOfPages']) {
                var buttonOffsetNext = $('<button></button>').attr({
                    class: 'pageNumber',
                    id: (additionalData['lastShowPage'] == additionalData['numberOfPages']) ? additionalData['lastShowPage'] : +additionalData['lastShowPage'] + +1,
                    name: 'showPage',
                    type: 'submit',
                    value: (additionalData['lastShowPage'] == additionalData['numberOfPages']) ? additionalData['lastShowPage'] : +additionalData['lastShowPage'] + +1,
                }).text('...');

                mainDiv.append(buttonOffsetNext);

                //Create last button
                var lastButton = $('<button></button>').attr({
                    class: 'pageNumber',
                    id: additionalData['numberOfPages'],
                    name: 'showPage',
                    type: 'submit',
                    value: additionalData['numberOfPages'],
                }).text(additionalData['numberOfPages']);

                mainDiv.append(lastButton);
            }

            $('#numberPagesBlock').replaceWith(mainDiv);//Display new data
        }
    };

    /**
     * [saveFormToCookie description]
     * Save post data to cookie
     *
     * @param  string cookieName Cookie name for save data
     * @param  object buttonObj  Pushed button in form
     * @return void
     */
    var saveFormToCookie = function (cookieName, buttonObj) {
        var jsonForm = {};
        var form = $('form').serializeArray();
        $.each(form, function () {
            if (jsonForm[this.name]) {
                if (!jsonForm[this.name].push) {
                    jsonForm[this.name] = [jsonForm[this.name]];
                }
                jsonForm[this.name].push(this.value || '');
            } else {
                jsonForm[this.name] = this.value || '';
            }
        });

        jsonForm[buttonObj.name] = buttonObj.value;
        saveCookie(cookieName, jsonForm);
    };

    /**
     * [saveCookie description]
     * Save data to cookie
     *
     * @param  string cookieName Cookie name for save data
     * @param  data
     * @return void
     */
    var saveCookie = function (cookieName, data) {
        cookieValues = JSON.stringify(data);
        document.cookie = cookieName + '=' + cookieValues;
    };

    /**
     * [eventForButton description]
     * Stop standart browser action and
     * call function getNewData and  pass as options pushed button
     * when clicked only button for sort-pagination action
     *
     * @return void
     */
    var eventForButton = function (event) {
        var idSortPaginationButton = {
            'sortButtonLast': '',
            'sortButtonFirst': '',
            'prev': '',
            'next': ''
        };
        if (idSortPaginationButton.hasOwnProperty(this.id) || !isNaN(this.id)) {
            event.preventDefault();
            getNewData.call(this);
        }
    };

    /**
     * [confirmRemove description]
     * Display Buttons for confirm remove contact
     *
     * @param  {[object]} event
     * @return {[void]}
     */
    var confirmRemove = function (event) {
        event.preventDefault();

        var elementA = this.getElementsByTagName('a');
        var url = elementA[0].getAttribute('href');
        url = url.replace('confirm', 'delete');

        var mainDiv = document.createElement('div');
        mainDiv.className = 'deleteContact';
        mainDiv.id = 'deleteContact';

        var h3 = document.createElement('h3');
        h3.innerHTML = 'Are you sure to remove this contact?';
        mainDiv.appendChild(h3);

        var buttonsBlock = document.createElement('div');
        buttonsBlock.className = 'buttons';

        var buttonYes = document.createElement('div');
        buttonYes.className = 'linkStyle';
        buttonYes.id = 'yes';
        var a1 = document.createElement('a');
        a1.setAttribute('href', url);
        var spanA1 = document.createElement('span');
        spanA1.innerHTML = 'Yes';
        a1.appendChild(spanA1);
        buttonYes.appendChild(a1);

        var buttonNo = document.createElement('div');
        buttonNo.className = 'linkStyle';
        buttonNo.id = 'cancel';
        var a2 = document.createElement('a');
        var spanA2 = document.createElement('span');
        spanA2.innerHTML = 'No';
        a2.appendChild(spanA2);
        buttonNo.appendChild(a2);

        buttonsBlock.appendChild(buttonYes);
        buttonsBlock.appendChild(buttonNo);
        mainDiv.appendChild(buttonsBlock);

        document.getElementById('contactList').style.opacity = 0.3;

        mainDiv.style.zIndex = 999;
        mainDiv.style.position = 'absolute';
        mainDiv.style.margin = 'auto';
        mainDiv.style.height = '100px';
        mainDiv.style.top = 0;
        mainDiv.style.left = 0;
        mainDiv.style.bottom = 0;
        mainDiv.style.right = 0;
        mainDiv.style.marginTop = '15%';

        document.body.insertBefore(mainDiv, document.getElementById('contactList'));
        document.getElementById('cancel').addEventListener('click', hideRemoveButtons);
        document.getElementById('yes').addEventListener('click', removeContact);
    };

    /**
     * [removeContact description]
     * Remove contact and then change data on page via ajax
     *
     * @param  {[object]} event [description]
     * @return {[void]}
     */
    var removeContact = function (event) {
        event.preventDefault();
        var url = this.firstChild.getAttribute('href');//Get url for ajax query
        //Start AJAX query
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                getNewData();//When contact remove - change data on page
                hideRemoveButtons();//Hide button for remove contact
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.setRequestHeader('Accept', 'application/ajax');
        xmlhttp.send();
    };

    /**
     * [hideRemoveButtons description]
     *Hide block buttons for remove contact and set body opacity '1'
     *
     * @return {[void]}
     */
    var hideRemoveButtons = function () {
        var blockButtons = document.getElementById('deleteContact');
        blockButtons.remove();
        document.getElementById('contactList').style.opacity = 1;
    };

    /**
     *Select/unselect all checkboxes
     *If click checkbox 'selectAll'- select or unselect all checkboxes
     * @return void
     */
    var selectAll = function () {
        if (this.checked == true) {
            $('input[type=checkbox]').prop('checked', true);
            getNewData();
        } else {
            $('input[type=checkbox]').prop('checked', false);
        }
    };

    /**
     *Check for select checkbox 'selectAll'
     *
     *
     * @return void
     */
    var checkForSelectAll = function () {
        var count = $('input[type=checkbox]:checked').length;

        //if selected all checkboxes - select checkbox 'selectAll'
        if (count == $('input[type=checkbox]').length - 1) {
            $('#selectAll').prop("checked", true);
            getNewData();
        } else {
            if ($('#selectAll').prop("checked")) {
                getNewData();
            }
        }
    };

    /**
     * [setEvents description]
     * Set events on page for this script
     *
     */
    var setEvents = function () {
        if (baseScript.allowedUrls(baseScript.getUrl())) {

            $('button').unbind('click', eventForButton).click(eventForButton);//Sorting and pagination


            $("input[type=checkbox]").unbind('change', checkForSelectAll).change(checkForSelectAll);//Event for checkboxes
            $('#selectAll').unbind('change', checkForSelectAll).unbind('change', selectAll).change(selectAll);//Event for 'select all checkboxes' checkbox


            $('.xButton').click(confirmRemove);//Set event for button remove contact
        }
    };

    return {
        'setEvents': setEvents,
        'getNewData': getNewData,
    };

});
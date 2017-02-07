;
define('validation',
    ['Helper'], function (baseScript) {

        /**
         *Validation  input data by pattern and display massage
         * when data isn't valid
         * @return boolean
         */
        var validate = function () {

            var patternNames = {
                'login': 'login',
                'password': 'password',
                'repeatPassword': 'password',
                'home': 'phone',
                'work': 'phone',
                'cell': 'phone',
                'best_phone': 'best_phone',
                'first': 'word',
                'last': 'word',
                'address1': 'address',
                'address2': 'address',
                'city': 'word',
                'state': 'word',
                'country': 'word',
                'email': 'email',
                'zip': 'phone'
            };//Object with field's name and patterns name

            var patterns = {
                'login': /^[\w]{3,12}$/,
                'password': /^[a-zA-Z0-9_-]{6,18}$/,
                'phone': /^[0-9]{3,10}$/,
                'best_phone': /^(work)|(cell)|(home)$/,
                'word': /^[a-zA-Z]{1,20}$/,
                'address': /^([a-zA-Z]{1,20})\s(([a-zA-Z]{1,20}|[0-9]{1,5})|([a-zA-Z]{1,20}|[0-9]{1,5})\s[0-9]{1,5})$/,
                'email': /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/
            };//Object with patterns for validation

            var errorMessages = {
                'login': 'Bad login. Use only latin chars and numbers.',
                'password': 'Bad password. Length 6-18 symbols.',
                'phone': 'Bad data. Use only numbers. Min length 3 max length 10',
                'best_phone': '',
                'word': 'Bad data. Use only latin chars',
                'address': 'Bad data. Use only latin chars and numbers',
                'email': 'Bad email. Use only latin chars and numbers',
                'emails': 'You enter bad emails. After each email except the last, must be a comma.',
                'birthday': 'bad birthday',
            };//Messages for each pattern

            var specialValidation = {
                'emails': 'checkEmailsString',
                'day': 'checkDate',
                'month': 'checkDate',
                'year': 'checkDate',
                'birthday': 'checkDate'
            };//Function for specific falidation

            //If isset such name in specialValidation - validate it by special function
            if (this.name in specialValidation) {
                var funcName = specialValidation[this.name];
                return eval(funcName + '(this, errorMessages[this.name])');

                //If exist such attribute name in patternNames - validate it
            } else if (this.name in patternNames) {

                //If empty value - display message
                switch (this.value) {
                    case '':
                        displayMessage(this, 'Entert ' + this.name);
                        $(this).css('border', '1px solid red');
                        return false;
                        break;

                    default:
                        //If not valid value - display error message
                        if (!patterns[patternNames[this.name]].test(this.value)) {
                            displayMessage(this, errorMessages[patternNames[this.name]]);
                            // this.style.border = '1px solid red';
                            return false;
                        }
                }
            }

            var messageElement = this.name + 'Message';//Element id for display message
            $('#' + messageElement).text(null);//Set empty message
            $(this).css('border', 0);

            return true;
        };

        /**
         * [checkDate description]
         * Validate date
         *
         * @return boolean
         */
        var checkDate = function () {
            var day = $('#day').val();
            var month = $('#month').val();
            var year = $('#year').val();
            var birthdayMessage = $('#birthdayMessage');

            var selectDate = new Date(year, month, day);
            var currentDate = new Date();

            if (selectDate == 'Invalid Date') {
                birthdayMessage.text('Select date');
                return false
            } else {
                var yearsDifference = currentDate.getFullYear() - selectDate.getFullYear();
                if (yearsDifference < 18) {
                    birthdayMessage.text('Contact is younger than 18. It\'s bad');
                    return false
                } else if (yearsDifference > 60) {
                    birthdayMessage.text('Contact is very old. It\'s bad');
                    return false
                }
            }

            birthdayMessage.text(null);
            return true;
        };

        /**
         * [displayMessage description]
         * Display message for not valid data and change border style
         *
         * @param  object inputObj Input object
         * @param  string message Message for display
         * @return boolean
         */
        var displayMessage = function (inputObj, message) {
            var elementStyle = {
                'text': '1px solid red'
            };//Style for not valid elements in page

            var messageElementId = inputObj.name + 'Message';//Element for display message
            $('#' + messageElementId).text(message);//Set empty message
            $(inputObj).css('border', 0);
        };

        /**
         * [checkEmailsString description]
         * Parse string with email, end validation all emails
         *
         * @param  object inputObj Input object
         * @return boolean
         */
        var checkEmailsString = function (inputObj, message) {
            //If empty value - set message
            if (inputObj.value === '') {
                message = 'Entert ' + inputObj.name;
                displayMessage(inputObj, message);
                return false;
            }

            var emailsArray = inputObj.value.split(','); //Parse string and save to array

            //Save email as object and validate it
            for (var i = 0; i < emailsArray.length; i++) {

                var emailObj = {
                    'name': 'email',
                    'value': emailsArray[i].trim()
                }

                if (!validate.call(emailObj)) {
                    displayMessage(inputObj, message);
                    return false;
                }
            }
            return true;
        };

        /**
         * [setActiveInput description]
         * Set style for active input element and hide validation message
         * 
         * @return void
         */
        var setActiveInput = function () {
            var messageElementId = this.name + 'Message';//Element for display message
            $('#' + messageElementId).text(null);
            $(this).css({
                border: '1px solid blue'
            })
        };
       

        /**
         * [validateForm description]
         * Validation all input fields in form
         *
         * @param  object form Form with all attributes
         * @return boolean
         */
        var validateForm = function (event) {
            event.preventDefault();

            // var elements = this.getElementsByTagName('input');//Get all form's inputs fields
            var elements = $('#' + this.id + ' input')//Get all form's inputs fields
            var validData = true;//Set default value for result validation

            //Validate all input fields
            for (var i = 0; i < elements.length; i++) {
                if (!validate.call(elements[i])) {
                    validData = false;
                }
            }

            if (validData) {
                this.submit();
            }
        };

        /**
         * [buttonsAction description]
         * Find button('buttons's attributes') in page and set fot it event
         *
         * @return void
         */
        var buttonsAction = function () {
            var buttonsAction = {
                'selectEmails': 'disableFormValidation',
                'saveEmails': 'disableFormValidation',
                'send': 'sendButtonAction',

                // 'confirm': 'disableFormValidation'
            };//Buttons Id and function names for call special function when click button

            //find in page buttons which id exist in buttonsAction key
            for (var key in buttonsAction) {
                if ($('#' + key).length) {
                    $('#' + key).click(eval(buttonsAction[key]));//On click button call such function
                }
            }
        };

        /**
         * [disableFormValidation description]
         * When click this button - turn off validation form
         *
         * @return void
         */
        var disableFormValidation = function () {
            $('#emails').unbind('submit', validateForm);
        };

        /**
         * [sendButtonAction description]
         * When click this button - validate form
         *
         * @return void
         */
        var sendButtonAction = function (event) {
            $('#emails').unbind('submit', validateForm);
            if (!validate.call(document.getElementById('inputEmails'))) {
                event.preventDefault();
            }
        };

        /**
         * [setEvents description]
         * Set events on page for this script
         *
         */
        var setEvents = function () {
            $('.forValidation').focusin(setActiveInput).focusout(validate);

            if (!baseScript.allowedUrls(baseScript.getUrl())) { //In such page we do not need to validate form
                $('form').submit(validateForm);
            }

            buttonsAction();
        };

        return {
            'setEvents': setEvents,
            'validate': validate,
        };

    });
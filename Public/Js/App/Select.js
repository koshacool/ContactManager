;
define(['helper', 'validation'], function (baseScript, validation) {

    /**
     * [checkSelectValue description]
     * Put selected value to input field
     *
     * @return void
     */
    var checkSelectedValue = function () {
        var arraySelectedValues = this.value.split(':');//Split selected element value to array
        var inputElement = $(this).parent().parent().find("input").val(arraySelectedValues[1]);

        switch (this.name) {
            case 'cities': //If user select city value, do ajax query for getting city's zips

                $.ajax({ //Start AJAX query
                    type: "POST",
                    url: baseScript.getUrl(),
                    data: $('#formRecord').serialize(),
                    headers: {"Accept": "application/ajax"},
                    success: function (result) {
                        displayZipSelect(JSON.parse(result));
                    }
                });
                break;

            case 'zipSelect':
                inputElement[0].value = arraySelectedValues[0];
                $(this).hide().siblings('button').hide();
                $('#cities option[value="false"]').prop('selected', true);
                break;
        }
        validation.validate.call(inputElement[0]);
    };

    /**
     * [displayZipSelect description]
     * Display menu for select zip
     *
     * @param  object data Data getted from server by ajax
     * @return void
     */
    var displayZipSelect = function (data) {
        var zipBlock = $('#zipSelectBlock');
        if (zipBlock.length) {
            zipBlock.remove();//If exist div block for display zips - remove it
        }


        if (typeof(data) == 'number') { //If reterned data is number - set it to input value and validate
            var zip = $('#zip').val(data);
            validation.validate.call(zip[0]);
        } else {//If returned json object - display block for select zip
            $('#zip').val(null);//Set empty 'zip' input value

            //Create selecting zips list
            var select = $('<select></select>').attr({
                id: 'zipSelect',
                name: 'zipSelect'
            });

            //Create default selected value in list
            var optionDefault = $('<option></option>').attr({
                value: false,
                selected: true,
            });
            optionDefault.text('zips');
            select.append(optionDefault);//Add value to select list

            for (var key in data) {//Create value in list for select
                var option = $('<option></option>').attr({
                    value: key + ':' + data[key],
                    selected: false,
                });
                option.text(key);
                select.append(option);//Add value to select list
            }

            var div = $('<div></div>').attr({
                class: 'additionalField',
                id: 'zipSelectBlock'
            });//Create div element
            div.append(select);

            $('#zipBlock').append(div);//Add select block to page
            setEvents();//Start function for check to select this block
        }
    };

    /**
     * [setEvents description]
     * Set events on page for this script
     *
     */
    var setEvents = function () {
        $('.additionalField select').change(checkSelectedValue);
        $('.date select').change(validation.validate);
    };

    return {
        'setEvents': setEvents,
    };

});
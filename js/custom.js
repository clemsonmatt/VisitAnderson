/*
 * Custom JQuery file
 */

$(function(){
    /* date picker */
    $('#datetimepicker1').datetimepicker({
        language: 'en',
        pick12HourFormat: true
    });
    $('#datetimepicker2').datetimepicker({
        language: 'en',
        pick12HourFormat: true
    });

    /* add row to table */
    $('#add-row').on('click', function(e) { Table.addRow($('#table-name').val()) });
    /* close row */
    $('#close-row').on('click', function(e) { Table.closeRow($('#table-name').val()) });

    /* edit row visitor table */
    $('.edit-row').on('click', function(e) { Visitor.editRow($(this).val()) });
    $('.edit-row-tr').on('click', function(e) { Visitor.editRow($(this).attr('id')) });

    /* edit row visiting table */
    $('.visiting-edit-row').on('click', function(e) { Visiting.editRow($(this).val()) });
    $('.visiting-edit-row-tr').on('click', function(e) { Visiting.editRow($(this).attr('id'), $(this).attr('title')) });

    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == "id")
        {
            Visitor.editRow(sParameterName[1]);
        }
    }

    /* select multiple in dropdown */
    $('#demo').multiselect();
})


var Table = {
    addRow: function(tableName) {
        /* clear out any values */
        $('.form-control').val("");

        /* set actionType to add */
        $('#actionType').val('add');

        /* display */
        $('#' + tableName + '-form').show();
    },

    closeRow: function(tableName) {
        /* hide */
        $('#' + tableName + '-form').hide();
    },
}


var Visitor = {
    editRow: function(rowId) {
        //console.log(rowId);
        location.href = "#top";
        /* change value to edit */
        $('#actionType').val('edit');

        /* show form */
        $('#visitor-form').show();

        /* populate */
        $('#form-fname').val($('#person-fname-' + rowId).text().replace(/\s/g, ''));
        $('#form-lname').val($('#person-lname-' + rowId).text().replace(/\s/g, ''));
        $('#form-email').val($('#person-email-' + rowId).text().replace(/\s/g, ''));
        $('#form-mobile').val($('#person-mobile-' + rowId).text().replace(/\s/g, ''));
        $('#form-home').val($('#person-home-' + rowId).text().replace(/\s/g, ''));
        $('#form-work').val($('#person-work-' + rowId).text().replace(/\s/g, ''));
        $('#form-address').val($('#person-address-' + rowId).val());
        $('#form-city').val($('#person-city-' + rowId).val());
        $('#form-state').val($('#person-state-' + rowId).val());
        $('#form-zip').val($('#person-zip-' + rowId).val());

        $('#person-id').val(rowId);
    }
}


var Visiting = {
    editRow: function(rowId, visitingId) {
        location.href = "#top";
        /* change value to edit */
        $('#actionType').val('edit');

        /* show form */
        $('#visiting-form').show();

        /* populate visitor */
        $('#form-fname').val($('#visiting-fname-' + rowId).val().replace(/\s/g, ''));
        $('#form-lname').val($('#visiting-lname-' + rowId).val().replace(/\s/g, ''));
        $('#form-email').val($('#visiting-email-' + rowId).text().replace(/\s/g, ''));
        $('#form-mobile').val($('#visiting-mobile-' + rowId).val().replace(/\s/g, ''));
        $('#form-home').val($('#visiting-home-' + rowId).val().replace(/\s/g, ''));
        $('#form-work').val($('#visiting-work-' + rowId).val().replace(/\s/g, ''));
        $('#form-address').val($('#visiting-address-' + rowId).val());
        $('#form-city').val($('#visiting-city-' + rowId).val());
        $('#form-state').val($('#visiting-state-' + rowId).val());
        $('#form-zip').val($('#visiting-zip-' + rowId).val());

        /* populate visiting */
        $('#form-reason').val($('#visiting-reason-' + rowId).val());

        var counter = 0;
        var titleArray = '';
        $('.interest-option').each(function(key, value) {
            interests = $('#visiting-interests-' + rowId).val();
            if (interests == 'false') {
                $('.multiselect').attr('title', 'None selected');
                $('.multiselect').text('None selected ');
                $('.multiselect').append('<b class="caret"></b>');
                $('.checkbox input').each(function(subkey, subvalue){
                    $(subvalue).prop('checked', false);
                });
            }
            if (interests.toString().search($(value).val()) > -1) {
                //console.log($(value).val());
                $('.checkbox input').each(function(subkey, subvalue){
                    //console.log(subkey +' '+ subvalue);
                    if ($(value).val() == $(subvalue).val()) {
                        titleArray = titleArray + $(value).val() + ', ';
                        $(subvalue).prop('checked', true);
                        counter++;
                    }
                });
            }
        });
        if (counter > 0) {
            $('.multiselect').text(counter + ' selected ');
            $('.multiselect').append('<b class="caret"></b>');
            $('#interests').val(titleArray);
        }

        $('#form-visit-date').val($('#visiting-date-' + rowId).val());
        $('#form-visit-date-start').val($('#visiting-date-start-' + rowId).val());
        $('#form-visit-date-end').val($('#visiting-date-end-' + rowId).val());

        $('#visitor-id').val(rowId);
        $('#visiting-id').val(visitingId);
    },

    addInterest: function(value) {
        console.log(value);
    }
}

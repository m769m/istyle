
$(document).ready(function(){
    $(".checkbox-filter-js").on('change', function () {
        let paramValue = $(this).val();
        let paramName = $(this).attr('name');
        if($(this).prop('checked') === false) {
            paramValue = 0;
        }
        setInputValueByName(paramName, paramValue);
        sendFiltersForm();
    });
    $(".radio-filter-js, .select-filter-js").on('change', function () {
        defaultFilter(this);
    });
    $(".input-filter-js").on('keyup', function () {
        defaultFilter(this);
    });
    $(".button-filter-js").on('click', function () {
        let paramValue = $(this).data('query');
        let paramName = $(this).data('name');
        setInputValueByName(paramName, paramValue);
        sendFiltersForm();
    });
});

function defaultFilter(elem)
{
    let paramValue = $(elem).val();
    let paramName = $(elem).attr('name');
    setInputValueByName(paramName, paramValue);
    sendFiltersForm();
}

function setInputValueByID(id, value)
{
    let input = $('.filters-form-js #'+id);
    $(input).val(value);
}

function setInputValueByName(name, value)
{
    let input = $('.filters-form-js [name='+name+']');
    $(input).val(value);
}

function sendFiltersForm()
{
    let formData = $('.filters-form-js').serialize();
    $('#filters_content_js').addClass('preloader-container');
    // console.log(formData);
    $.ajax({
        method: 'get',
        url: document.location.href.split('?')[0]+'?'+formData,
        dataType: 'text',
        success: function(data){
            // console.log(data);
            let content = $(data).find('#filters_content_js').html();
            $('#filters_content_js').html(content);
            $('#filters_content_js').removeClass('preloader-container');
        }
    });
}

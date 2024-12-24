'use strict';

export class SaleSearchCity {

    constructor()
    {
        this.sessid = BX.bitrix_sessid();
        this.locationBlock = $('#location')
        this.cityUl = $('#js-city-ul')
        this.initEventListener()
    }

    initEventListener()
    {
        const instance = this
        instance.ajax = false;
        $(document).on('keyup', '#location', function(){
            if ($(this).val().length > 2 && !instance.ajax) {
                instance.ajax = true
                instance.sendRequest()
            }
        })

        $(document).on('click', "#js-city-ul li", function(){
            let city = instance.cityLast.ITEMS[$(this).data('code')]
            let name = city.DISPLAY
            city.PATH.forEach(function(item) {
                name += ", "+instance.cityLast.ETC.PATH_ITEMS[item].DISPLAY
            })
            instance.locationBlock.parent().find('input[type="hidden"]').val(city.CODE);
            instance.locationBlock.val(name)
            instance.cityUl.addClass('d-none')
            instance.locationBlock.parent().find('input[type="hidden"]').trigger('change');
        })
    }

    sendRequest()
    {
        const instance = this

        const select = {
            'VALUE': 'ID',
            'DISPLAY': 'NAME.NAME',
            '1': 'TYPE_ID',
            '2': 'CODE'
        };
        const additionals = {
            '1': 'PATH'
        }
        let filter = {
            '=PHRASE': $('#location').val(),
            '=NAME.LANGUAGE_ID': 'ru',
            '=SITE_ID': 's1'
        }
        let result = {
            'select': select,
            'additionals': additionals,
            'filter': filter,
            'version': 2,
            'PAGE_SIZE': 10,
            'PAGE': 0
        }

        $.ajax({
            url: '/bitrix/components/bitrix/sale.location.selector.search/get.php',
            data: result,
            type: 'POST',
            dataType: 'html',
            success: function(e)
            {
                instance.ajax = false
                try {
                    //hotfix
                    let string = e.replace(/\'/g, "\"");
                    let json = JSON.parse(string)
                    instance.createElems(json)
                } catch (ex) {
                    console.error(ex)
                }
            }
        })
    }

    createElems(json)
    {
        let text = '';
        this.cityLast = json.data;
        for (var key in json.data.ITEMS) {
            text += "<li data-code='"+key+"'>"+json.data.ITEMS[key].DISPLAY+"</li>"
        }
        this.cityUl.removeClass('d-none').find('ul').html(text);
    }
}

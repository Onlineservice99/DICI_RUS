'use strict';

export class SaleOrderAjax {

    constructor(sign, options)
    {
        this.sessid = BX.bitrix_sessid();
        this.sign = sign;
        this.options = options;
        this.initEventListener()
    }

    initEventListener()
    {
        const instance = this
        $(document).on('change', '.js-refresh-elem', function(){
            let data = $('#soa-form').serializeArray()
            let obj = {};
            for (let key in data) {
                obj[data[key].name] = data[key].value;

            }
            instance.sendRequest(obj)
        })
        $(document).on('click', '#soa-submit-button', function(e){
            e.preventDefault()
            let form = $('#soa-form');
            let data = form.serializeArray()
            let obj = {};
            for (let key in data) {
                obj[data[key].name] = data[key].value;
            }
            let nameFile = form.find('input[type="file"]');
            if (nameFile[0].files[0] !== undefined) {
                obj[nameFile.attr('name')] = nameFile[0].files[0];
            }
            instance.saveOrderAjax(obj)
        })
        this.sendRequest()
    }

    saveOrderAjax(data)
    {
        const instance = this
        let obj = {
            sessid: this.sessid,
            signedParamsString: this.sign,
            via_ajax: "Y",
            SITE_ID: this.options.siteId,
            'soa-action': 'saveOrderAjax',
        }

        obj = Object.assign(data, obj);

        let test = new FormData();

        for (let key in obj ) {
            test.append(key, obj[key]);
        }

        $.ajax({
            url: '/bitrix/components/bitrix/sale.order.ajax/ajax.php',
            data: test,
            method: "POST",
            processData: false,
            contentType: false,
            success: function(e)
            {
                if (e.order.ID > 0) {
                    window.location = e.order.REDIRECT_URL;
                }
                //instance.parseResponse(e)
                if (e.order.ERROR) {
                    let errors = '';
                    console.log(e.order.ERROR);
                    if (e.order.ERROR.hasOwnProperty('PROPERTY')) {
                        errors = e.order.ERROR.PROPERTY.join('<br>');
                    }
                    if (e.order.ERROR.hasOwnProperty('MAIN')) {
                        errors = e.order.ERROR.MAIN.join('<br>');
                    }
    
                    $('.basket--order .error').remove();
                    $('#soa-form').before('<div class="text-red error">' + errors + '</div>');
                    $(document).scrollTop($('h1').offset().top);
                }
            }
        })
    }

    sendRequest(data = {}, action = 'refreshOrderAjax')
    {
		var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
        const instance = this
        let obj = {
            sessid: this.sessid,
            signedParamsString: this.sign,
            via_ajax: "Y",
            SITE_ID: this.options.siteId,
            'soa-action': action,
        }

        let result = {}

        if (action === 'saveOrderAjax') {
            result = Object.assign(data, obj);
        } else {
            obj.order = data;

            result = obj;
        }



        $.ajax({
            url: '/local/components/bitrix/sale.order.ajax/ajax.php',
            data: result,
            method: "POST",
            success: function(e)
            {
                instance.parseResponse(e)
            }
        })
    }

    parseResponse(json)
    {
        this.createDeliveries(json.order.DELIVERY);
        this.createPayments(json.order.PAY_SYSTEM)
        this.createItems(json.order.GRID.ROWS)
        this.updatePropFields(json.order.ORDER_PROP)
        this.updateRightBlock(json.order)
    }

    createDeliveries(deliveries)
    {
        let block = $('[data-delivery]');
        let html = '';
        for (let key in deliveries) {
            let ch = '';
            if (deliveries[key].CHECKED === 'Y') {
                ch = 'checked="checked"';
            }
            html +='<div class="form-block">' +
                '<label class="form-block__checkbox form-block__checkbox--radio">' +
                '<input class="js-refresh-elem" type="radio" name="DELIVERY_ID" value="'+deliveries[key].ID+'" '+ch+'><span' +
                ' class="p-md">'+deliveries[key].OWN_NAME+'</span>' +
                '</label>' +
                '</div>'
            
            if (deliveries[key].CHECKED === 'Y' && deliveries[key].DESCRIPTION) {
                html += '<div class="delivery-description">' + deliveries[key].DESCRIPTION + '</div>'
            }
			/*sok_dev_30/03/23*/
			if(deliveries[key].CHECKED === 'Y' && deliveries[key].CALCULATE_ERRORS){
				html += '<div class="delivery-description">Доставка выбраным способом невозможна. Пожалуйста выберите другой способ доставки!</div>'
			}
			/*sok_dev_30/03/23*/
            if (deliveries[key].CHECKED === 'Y') {
                if (this.options.courierDeliveryIds.indexOf(deliveries[key].ID) !== -1) {
                    $('#delivery-address').show();
                } else {
                    $('#delivery-address').hide();
                }
            }

            html +='</div>'
        }

        block.html(html)
    }

    createPayments(payments)
    {
        let block = $('[data-payments]');
        let html = '';
        for (let key in payments) {
            let ch = '';
            if (payments[key].CHECKED === 'Y') {
                ch = 'checked="checked"';
            }
            html += '<div class="form-block mb-16">' +
                '<label class="form-block__checkbox form-block__checkbox--radio">' +
                '<input class="js-refresh-elem" type="radio" name="PAY_SYSTEM_ID" value="'+payments[key].ID+'" '+ch+'><span' +
                ' class="p-md">'+payments[key].NAME+'</span>' +
                '</label>' +
                '</div>'
        }

        block.html(html)
    }

    createItems(items)
    {
        let block = $('[data-items]');
        let html = '';
        let num = 1;
        for (let key in items) {
            let price = items[key].data.PRICE_FORMATED;
            if (items[key].data.BASE_PRICE != items[key].data.SUM_NUM) {
                price = '<s>'+items[key].data.BASE_PRICE_FORMATED+'</s>'+items[key].data.PRICE_FORMATED;
            }
            html += '<div class="basket-item">' +
                '<div class="basket-item__num"><span>'+num+'</span>00429</div>' +
                '<a class="basket-item__img-wrap" href="'+items[key].data.DETAIL_PAGE_URL+'">' +
                '<img src="'+items[key].data.PREVIEW_PICTURE_SRC+'" alt="">' +
                '</a>' +
                '<div class="basket-item__name-wrap">' +
                '<div class="basket-item__brand p-xs text-gray mb-16">ENSTO</div>' +
                '<a class="basket-item__title link" href="'+items[key].data.DETAIL_PAGE_URL+'">'+items[key].data.NAME+'</a>' +
                '</div>' +
                '<div class="basket-item__price-wrap">' +
                '<div class="d-flex align-items-center justify-content-between">' +
                '<div class="basket-item__price text-xl-right">' +
                price +
                '</div>' +
                '<div class="basket-item__count">x '+items[key].data.QUANTITY+'</div>' +
                '<div class="basket-item__price basket-item__price--total"><span' +
                ' class="fw-600">'+items[key].data.SUM+'</span></div>' +
                '</div>' +
                '</div>' +
                '</div>'
        }
        num++;

        block.html(html)
    }

    updatePropFields(info)
    {
        let block = $('[data-properies]');

        let groupId = block.attr('data-properies');
        console.log(groupId);
        let html = '';
        let arprops={};

        for (let i in props.properties) {
            if (props.properties[i].PROPS_GROUP_ID != groupId) {
                continue;
            }

            html += '<div class="form-block"><input class="form-block__input" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '*' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>'
        }

        for (var i in props.properties) {
            if (props.properties[i].CODE != 'INDEX') {
                continue;
            }

            html += '<div class="form-block"><input class="form-block__input" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '*' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>'
        }

        block.html(html)
    }

    updateRightBlock(info)
    {
        let quantity = 0
        for (let key in info.GRID.ROWS) {
            quantity += info.GRID.ROWS[key].data.QUANTITY;
        }
        $('[data-all-quantity]').html(quantity)
        $('[data-all-price]').html(info.TOTAL.ORDER_TOTAL_PRICE_FORMATED)
        $('[data-all-discount]').html("- "+info.TOTAL.DISCOUNT_PRICE_FORMATED)
        $('[data-all-delivery]').html("+ "+info.TOTAL.DELIVERY_PRICE_FORMATED)
    }
}
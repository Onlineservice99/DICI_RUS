'use strict';

export class CartPage {

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

        $('.js-spin-count').on('change', function() {
            let elem = $(this)
            let parent = elem.parents('.basket-item');
            let id = parent.data('element-basket');
            let price = instance.update(id, elem.val(), parent)

        })

        $('#coupon_input').on('blur', function(e){
            let field = $(this)
            let basket = {
                coupon: field.val()
            }
            let data = {
                basket
            }
            instance.sendRequest(data);
        })

        $('#clear_basket').on('click', function(e){
            e.preventDefault()
            $.ajax({
                url: '/local/ajax/api/basket/clear.php',
                success: function() {
                    location.reload();
                }
            })
        })
    }

    update(id, value, elem)
    {
        const instance = this

        $.ajax({
            url: '/local/ajax/api/basket/update.php',
            data: {id: id, quantity: value},
            method: "POST",
            success: function(e) {
                //elem.find('.basket-item__price--total span').html(e.price);
                instance.sendRequest({});
            }
        })
    }

    sendRequest(data, action = 'recalculateAjax')
    {
        const instance = this
        let obj = {
            sessid: this.sessid,
            signedParamsString: this.sign,
            via_ajax: "Y",
            site_id: this.options.siteId,
            site_template_id: this.options.siteTemplateId,
            template: this.options.templateFolder,
            basketAction: action
        }

        let dataSend = Object.assign(obj, data);
        $.ajax({
            url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
            data: dataSend,
            method: "POST",
            success: function(e)
            {
                instance.parseResponse(e)
            }
        })
    }

    generatePDF() {
        const element = document.getElementById('basket-download');
        html2pdf()
            .from(element)
            .save();
    }

    sendBasket() {
        let basket = '<table><tbody>'
        $('.basket-item').each(function(){
            basket += "<tr>"
            basket += "<td>"+$(this).find('.basket-item__num').html()+"</td>"
            basket += "<td>"+$(this).find('.basket-item__title').html()+"</td>"
            basket += "</tr>"
        })
        basket += '</tbody></table>'

        $('.js-send-basket').find('[name="basket-items"]').val(basket);

        $.ajax({
            url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
            data: dataSend,
            method: "POST",
            success: function(e)
            {
                instance.parseResponse(e)
            }
        })

    }

    parseResponse(json)
    {
        $(".basket__total-price").html(json.BASKET_DATA.allSum_FORMATED);
        let count = 0;
        for (let i in json.BASKET_DATA.GRID.ROWS) {
            count += json.BASKET_DATA.GRID.ROWS[i].QUANTITY;
        }
        $("[data-cart-count]").html(count);
        if (json.BASKET_DATA.DISCOUNT_PRICE_ALL > 0) {
            $("#discount-price").html(json.BASKET_DATA.DISCOUNT_PRICE_FORMATED);  
            $("#discount-price").parent().parent().removeClass('d-none');
        } else {
            $("#discount-price").parent().parent().addClass('d-none');
        }
        let couponLength = json.BASKET_DATA.COUPON_LIST.length

        if (couponLength > 0) {
            if (json.BASKET_DATA.COUPON_LIST[couponLength-1].JS_STATUS == 'BAD') {
                $('#coupon_input').css('color', 'red');
            } else {
                $('#coupon_input').css('color', 'green');
                $('#coupon_input').attr('disabled', 'disabled');
            }
        }


        let items = json.BASKET_DATA.GRID.ROWS
        for (let key in items) {
            let elem = $('.basket-item[data-element-basket="'+items[key].PRODUCT_ID+'"]');
            elem.find('.basket-item__price--total span').html(items[key].SUM)
            elem.find('.js-spin-count').val(items[key].QUANTITY)
        }

    }
}

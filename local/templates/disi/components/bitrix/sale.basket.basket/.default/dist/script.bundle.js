this.Meven = this.Meven || {};
(function (exports) {
    'use strict';

    var CartPage = /*#__PURE__*/function () {
      function CartPage(sign, options) {
        babelHelpers.classCallCheck(this, CartPage);
        this.sessid = BX.bitrix_sessid();
        this.sign = sign;
        this.options = options;
        this.initEventListener();
      }

      babelHelpers.createClass(CartPage, [{
        key: "initEventListener",
        value: function initEventListener() {
          var instance = this;
          $('.js-spin-count').on('change', function () {
            var elem = $(this);
            var parent = elem.parents('.basket-item');
            var id = parent.data('element-basket');
            var price = instance.update(id, elem.val(), parent);
          });
          $('#coupon_input').on('blur', function (e) {
            var field = $(this);
            var basket = {
              coupon: field.val()
            };
            var data = {
              basket: basket
            };
            instance.sendRequest(data);
          });
          $('#clear_basket').on('click', function (e) {
            e.preventDefault();
            $.ajax({
              url: '/local/ajax/api/basket/clear.php',
              success: function success() {
                location.reload();
              }
            });
          });
        }
      }, {
        key: "update",
        value: function update(id, value, elem) {
          var instance = this;
          $.ajax({
            url: '/local/ajax/api/basket/update.php',
            data: {
              id: id,
              quantity: value
            },
            method: "POST",
            success: function success(e) {
              //elem.find('.basket-item__price--total span').html(e.price);
              instance.sendRequest({});
            }
          });
        }
      }, {
        key: "sendRequest",
        value: function sendRequest(data) {
          var action = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'recalculateAjax';
          var instance = this;
          var obj = {
            sessid: this.sessid,
            signedParamsString: this.sign,
            via_ajax: "Y",
            site_id: this.options.siteId,
            site_template_id: this.options.siteTemplateId,
            template: this.options.templateFolder,
            basketAction: action
          };
          var dataSend = Object.assign(obj, data);
          $.ajax({
            url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
            data: dataSend,
            method: "POST",
            success: function success(e) {
              instance.parseResponse(e);
            }
          });
        }
      }, {
        key: "generatePDF",
        value: function generatePDF() {
          var element = document.getElementById('basket-download');
          html2pdf().from(element).save();
        }
      }, {
        key: "sendBasket",
        value: function sendBasket() {
          var basket = '<table><tbody>';
          $('.basket-item').each(function () {
            basket += "<tr>";
            basket += "<td>" + $(this).find('.basket-item__num').html() + "</td>";
            basket += "<td>" + $(this).find('.basket-item__title').html() + "</td>";
            basket += "</tr>";
          });
          basket += '</tbody></table>';
          $('.js-send-basket').find('[name="basket-items"]').val(basket);
          $.ajax({
            url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
            data: dataSend,
            method: "POST",
            success: function success(e) {
              instance.parseResponse(e);
            }
          });
        }
      }, {
        key: "parseResponse",
        value: function parseResponse(json) {
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

          var couponLength = json.BASKET_DATA.COUPON_LIST.length;

          if (couponLength > 0) {
            if (json.BASKET_DATA.COUPON_LIST[couponLength - 1].JS_STATUS == 'BAD') {
              $('#coupon_input').css('color', 'red');
            } else {
              $('#coupon_input').css('color', 'green');
              $('#coupon_input').attr('disabled', 'disabled');
            }
          }

          var items = json.BASKET_DATA.GRID.ROWS;

          for (var key in items) {
            var elem = $('.basket-item[data-element-basket="' + items[key].PRODUCT_ID + '"]');
            elem.find('.basket-item__price--total span').html(items[key].SUM);
            elem.find('.js-spin-count').val(items[key].QUANTITY);
          }
        }
      }]);
      return CartPage;
    }();

    exports.CartPage = CartPage;

}((this.Meven.Components = this.Meven.Components || {})));
//# sourceMappingURL=script.bundle.js.map

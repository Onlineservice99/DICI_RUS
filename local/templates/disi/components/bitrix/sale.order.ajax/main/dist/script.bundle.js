this.Meven = this.Meven || {};
(function (exports) {
    'use strict';

    var SaleOrderAjax = /*#__PURE__*/function () {
      function SaleOrderAjax(sign, options) {
        babelHelpers.classCallCheck(this, SaleOrderAjax);
        this.sessid = BX.bitrix_sessid();
        this.sign = sign;
        this.options = options;
        this.initEventListener();
      }

      babelHelpers.createClass(SaleOrderAjax, [{
        key: "initEventListener",
        value: function initEventListener() {
          var instance = this;
          $(document).on('change', '.js-refresh-elem', function () {
            var data = $('#soa-form').serializeArray();
            var obj = {};

            for (var key in data) {
              obj[data[key].name] = data[key].value;
            }

            instance.sendRequest(obj);
          });
          $(document).on('click', '#soa-submit-button', function (e) {
            e.preventDefault();
            var form = $('#soa-form');
            var data = form.serializeArray();
            var obj = {};

            for (var key in data) {
              obj[data[key].name] = data[key].value;
            }

            var nameFile = form.find('input[type="file"]');

            if (nameFile[0].files[0] !== undefined) {
              obj[nameFile.attr('name')] = nameFile[0].files[0];
            }

            instance.saveOrderAjax(obj);
          });
          this.sendRequest();
        }
      }, {
        key: "saveOrderAjax",
        value: function saveOrderAjax(data) {
          var obj = {
            sessid: this.sessid,
            signedParamsString: this.sign,
            via_ajax: "Y",
            SITE_ID: this.options.siteId,
            'soa-action': 'saveOrderAjax'
          };
          obj = Object.assign(data, obj);
          var test = new FormData();

          for (var key in obj) {
            test.append(key, obj[key]);
          }

          $.ajax({
            url: '/bitrix/components/bitrix/sale.order.ajax/ajax.php',
            data: test,
            method: "POST",
            processData: false,
            contentType: false,
            success: function success(e) {
              if (e.order.ID > 0) {
                window.location = e.order.REDIRECT_URL;
              } //instance.parseResponse(e)
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
          });
        }
      }, {
        key: "sendRequest",
        value: function sendRequest() {
          var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
          var action = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'refreshOrderAjax';
          var instance = this;
          var obj = {
            sessid: this.sessid,
            signedParamsString: this.sign,
            via_ajax: "Y",
            SITE_ID: this.options.siteId,
            'soa-action': action
          };
          var result = {};

          if (action === 'saveOrderAjax') {
            result = Object.assign(data, obj);
          } else {
            obj.order = data;
            result = obj;
          }

          $.ajax({
            url: '/bitrix/components/bitrix/sale.order.ajax/ajax.php',
            data: result,
            method: "POST",
            success: function success(e) {
              instance.parseResponse(e);
            }
          });
        }
      }, {
        key: "parseResponse",
        value: function parseResponse(json) {
          this.createDeliveries(json.order.DELIVERY);
          this.createPayments(json.order.PAY_SYSTEM);
          this.createItems(json.order.GRID.ROWS);
          this.updatePropFields(json.order.ORDER_PROP);
          this.updateRightBlock(json.order);
        }
      }, {
        key: "createDeliveries",
        value: function createDeliveries(deliveries) {
          var block = $('[data-delivery]');
          var html = '';

          for (var key in deliveries) {
            var ch = '';

            if (deliveries[key].CHECKED === 'Y') {
              ch = 'checked="checked"';
            }
            
            html += '<div class="form-block">' + '<label class="form-block__checkbox form-block__checkbox--radio">' + '<input class="js-refresh-elem" type="radio" name="DELIVERY_ID" value="' + deliveries[key].ID + '" ' + ch + '><span' + ' class="p-md">' + deliveries[key].OWN_NAME + '</span>' + '</label>';

            if (deliveries[key].CHECKED === 'Y' && deliveries[key].DESCRIPTION) {
                html += '<div class="delivery-description">' + deliveries[key].DESCRIPTION + '</div>';
            }

            html += '</div>';
          }

          block.html(html);
        }
      }, {
        key: "createPayments",
        value: function createPayments(payments) {
          var block = $('[data-payments]');
          var html = '';

          for (var key in payments) {
            var ch = '';

            if (payments[key].CHECKED === 'Y') {
              ch = 'checked="checked"';
            }

            html += '<div class="form-block mb-16">' + '<label class="form-block__checkbox form-block__checkbox--radio">' + '<input class="js-refresh-elem" type="radio" name="PAY_SYSTEM_ID" value="' + payments[key].ID + '" ' + ch + '><span' + ' class="p-md">' + payments[key].NAME + '</span>' + '</label>' + '</div>';
          }

          block.html(html);
        }
      }, {
        key: "createItems",
        value: function createItems(items) {
          var block = $('[data-items]');
          var html = '';
          var num = 1;

          for (var key in items) {
            var price = items[key].data.PRICE_FORMATED;

            if (items[key].data.BASE_PRICE != items[key].data.SUM_NUM) {
              price = '<s>' + items[key].data.BASE_PRICE_FORMATED + '</s>' + items[key].data.PRICE_FORMATED;
            }

            html += '<div class="basket-item">' + '<div class="basket-item__num"><span>' + num + '</span>00429</div>' + '<a class="basket-item__img-wrap" href="' + items[key].data.DETAIL_PAGE_URL + '">' + '<img src="' + items[key].data.PREVIEW_PICTURE_SRC + '" alt="">' + '</a>' + '<div class="basket-item__name-wrap">' + '<div class="basket-item__brand p-xs text-gray mb-16">ENSTO</div>' + '<a class="basket-item__title link" href="' + items[key].data.DETAIL_PAGE_URL + '">' + items[key].data.NAME + '</a>' + '</div>' + '<div class="basket-item__price-wrap">' + '<div class="d-flex align-items-center justify-content-between">' + '<div class="basket-item__price text-xl-right">' + price + '</div>' + '<div class="basket-item__count">x ' + items[key].data.QUANTITY + '</div>' + '<div class="basket-item__price basket-item__price--total"><span' + ' class="fw-600">' + items[key].data.SUM + '</span></div>' + '</div>' + '</div>' + '</div>';
          }

          num++;
          block.html(html);
        }
      }, {
        key: "updatePropFields",
        value: function updatePropFields(props) {
          var block = $('[data-properies]');
          var groupId = block.attr('data-properies');
          var html = '';

          for (var i in props.properties) {
            if (props.properties[i].PROPS_GROUP_ID != groupId) {
                continue;
            }

            html += '<div class="form-block"><input class="form-block__input" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '*' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>';
          }

          for (var i in props.properties) {
            if (props.properties[i].CODE != 'INDEX') {
                continue;
            }

            html += '<div class="form-block"><input class="form-block__input" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '*' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>';
          }

          block.html(html);
        }
      }, {
        key: "updateRightBlock",
        value: function updateRightBlock(info) {
          var quantity = 0;

          for (var key in info.GRID.ROWS) {
            quantity += info.GRID.ROWS[key].data.QUANTITY;
          }

          $('[data-all-quantity]').html(quantity);
          $('[data-all-price]').html(info.TOTAL.ORDER_TOTAL_PRICE_FORMATED);
          $('[data-all-discount]').html("- " + info.TOTAL.DISCOUNT_PRICE_FORMATED);
          $('[data-all-delivery]').html("+ " + info.TOTAL.DELIVERY_PRICE_FORMATED);
        }
      }]);
      return SaleOrderAjax;
    }();

    exports.SaleOrderAjax = SaleOrderAjax;

}((this.Meven.Components = this.Meven.Components || {})));
//# sourceMappingURL=script.bundle.js.map

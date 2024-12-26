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
          
          
          $(document).on("click", ".pt_tabs a", function(){
            $(".pt_id").val($(this).data("ptid")).change();
          });
          $(document).on("click", "input[data-code='PVZ']", function(){
            instance.getPickPoint();
          });
          $(document).on("change", ".terms_input", function(){
            let disabled=false;
            $(".terms_input").each(function(){
              if(!$(this).prop("checked")){
                disabled=true;
              }
            })
            if(disabled){
              $("#soa-submit-button").addClass("disabled");
            }
            else{
              $("#soa-submit-button").removeClass("disabled");
              $(".terms_error").hide();
            }

          });
          $(document).ready(function () {
            if (typeof orderModal !== 'undefined' && orderModal && typeof storeResultModal !== 'undefined' && storeResultModal){
              $.fancybox.open('<div class="popup text-center"><div class="text-center mb-28">'+ orderModal +'</div><button class="btn btn--red" data-fancybox-close>Продолжить</button></div>')
            }
          });
          $(document).on('click', '#soa-submit-button', function (e) {
            e.preventDefault();
            if($(this).hasClass("disabled")){
              $(".terms_error").show();
              return false;
            }
            var form = $('#soa-form');
            var data = form.serializeArray();
            var obj = {};

            for (var key in data) {
              obj[data[key].name] = data[key].value;
            }

            /*var nameFile = form.find('input[type="file"]');

            if (nameFile[0].files[0] !== undefined) {
              obj[nameFile.attr('name')] = nameFile[0].files[0];
            }*/

            instance.saveOrderAjax(obj);
          });

 	var data1 = $('#soa-form').serializeArray();
            var obj1 = {};

            for (var key in data1) {
              obj1[data1[key].name] = data1[key].value;
            }
          this.sendRequest(obj1);

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
              } else {
                  dataLayer.push({'event': 'order'});
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
            url: '/local/components/bitrix/sale.order.ajax/ajax.php',
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
          this.createDeliveries(json.order.DELIVERY,json.deliveryId);
          this.createPayments(json.order.PAY_SYSTEM);
          this.createItems(json.order.GRID.ROWS);
          this.updatePropFields(json.order.ORDER_PROP);
          this.updateRightBlock(json.order);
        }
      }, {
        key: "createDeliveries",
        value: function createDeliveries(deliveries,$setted_value) {
          var blocks = $('[data-delivery]');
          let showTabs=true;
          let stopChecking=false;
          var activeBlock=false;
          $.each(blocks, function(i,v){
              let block = $(v);
              var deliveryIds = block.attr('data-delivery').split(",");
              var html = '';

              for (var key in deliveries) {
                  if (deliveryIds.indexOf(deliveries[key].ID) != -1) {
                      var ch = '';
                      if ($setted_value == null) {
                          if (deliveries[key].CHECKED === 'Y') {
                              ch = 'checked="checked"';
                              activeBlock = block.attr("id");
                          }
                      } else {
                          if (deliveries[key].ID === $setted_value) {
                              ch = 'checked="checked"';
                              activeBlock = block.attr("id");
                          }
                      }

                      html += '<div class="form-block">' +
                          '<label class="form-block__checkbox form-block__checkbox--radio">' +
                          '<input class="js-refresh-elem" type="radio" name="DELIVERY_ID" value="' + deliveries[key].ID + '" ' + ch + '><span class="p-md">' + deliveries[key].OWN_NAME + '</span>' +
                          '</label>';

                      if (deliveries[key].CHECKED === 'Y' && deliveries[key].DESCRIPTION) {
                          html += '<div class="delivery-description">' + deliveries[key].DESCRIPTION + deliveries[key].CALCULATE_DESCRIPTION + '</div>';
                      }

                      html += '</div>';
                  }
              }

              if(html=='' && !stopChecking){
                showTabs=false;
                stopChecking=true;
              }
              block.html(html);
            });

          $('a[href="#'+activeBlock+'"]').click();
          if(showTabs){
            $(".delivery_block").find(".delivery_tabs").addClass("d-lg-flex").show();
          }
          else{
            $(".delivery_block").find(".delivery_tabs").removeClass("d-lg-flex").hide();
          }
        }
      }, {
        key: "createPayments",
        value: function createPayments(payments) {
          var block = $('[data-payments]');
          var html = '';

          for (var key in payments) {
            var ch = '';
            let checkedClass = '';
            if (payments[key].CHECKED === 'Y') {
              ch = 'checked="checked"';
              checkedClass = 'checked';
            }
            html += '<div class="form-block mb-16">' + '<label class="form-block__checkbox form-block__checkbox--radio '+checkedClass+'">' + '<input class="js-refresh-elem" type="radio" name="PAY_SYSTEM_ID" value="' + payments[key].ID + '" ' + ch + '><span' + ' class="p-md">' + payments[key].NAME + '</span>' + '<img class="payment-logo" src="'+payments[key].PSA_LOGOTIP_SRC+'"/></label>' + '</div>';
          }

          block.html(html);
          $('[data-payments] input[type=radio]').on('change', (e)=>{
            if($(e.target).is(':checked')){
              $('[data-payments] .form-block__checkbox').removeClass('checked');
              $(e.target).closest('label').addClass('checked');
            }
            else{
              $(e.target).closest('label').removeClass('checked');
            }
          })
        }
      }, {
        key: "createItems",
        value: function createItems(items) {
          var block = $('[data-items]');
          var html = '';
          var num = 1;

          for (var key in items) {
            var price = items[key].data.PRICE_FORMATED;
            var productId = items[key].data.PRODUCT_ID;
/*система лояльности пока не понятно как должна была работать, но этот код не верный
            if (items[key].data.BASE_PRICE != items[key].data.SUM_NUM) {
              price = '<s>' + items[key].data.BASE_PRICE_FORMATED + '</s>' + items[key].data.PRICE_FORMATED;
            }
*/

            if (orderInfoStores[items[key].data.PRODUCT_ID]?.text) {
              html += '<div class="basket-item" data-element-basket="' + items[key].data.PRODUCT_ID + '"><div class="basket-item__num"><span>' + num + '</span>00429</div>' + '<a class="basket-item__img-wrap" href="' + items[key].data.DETAIL_PAGE_URL + '">' + '<img src="' + items[key].data.PREVIEW_PICTURE_SRC + '" alt="">' + '</a>' + '<div class="basket-item__name-wrap">' + '<div class="basket-item__brand p-xs text-gray mb-16">ENSTO</div>' + '<a class="basket-item__title link" href="' + items[key].data.DETAIL_PAGE_URL + '">' + items[key].data.NAME + '</a>' + '<div class="card__shipment">' + 'Отгрузка <span>через <span>' + orderInfoStores[items[key].data.PRODUCT_ID].text +'</span></span>' + '</div>' + '</div>' + '<div class="basket-item__price-wrap">' + '<div class="d-flex align-items-center justify-content-between">' + '<div class="basket-item__price text-xl-right">' + price + '</div>' + '<div class="basket-item__count">x ' + items[key].data.QUANTITY + '</div>' + '<div class="basket-item__price basket-item__price--total"><span' + ' class="fw-600">' + items[key].data.SUM + '</span></div>' + '</div>' + '</div>' + '</div>';
            } else {
              html += '<div class="basket-item" data-element-basket="' + items[key].data.PRODUCT_ID + '"><div class="basket-item__num"><span>' + num + '</span>00429</div>' + '<a class="basket-item__img-wrap" href="' + items[key].data.DETAIL_PAGE_URL + '">' + '<img src="' + items[key].data.PREVIEW_PICTURE_SRC + '" alt="">' + '</a>' + '<div class="basket-item__name-wrap">' + '<div class="basket-item__brand p-xs text-gray mb-16">ENSTO</div>' + '<a class="basket-item__title link" href="' + items[key].data.DETAIL_PAGE_URL + '">' + items[key].data.NAME + '</a>' + '</div>' + '<div class="basket-item__price-wrap">' + '<div class="d-flex align-items-center justify-content-between">' + '<div class="basket-item__price text-xl-right">' + price + '</div>' + '<div class="basket-item__count">x ' + items[key].data.QUANTITY + '</div>' + '<div class="basket-item__price basket-item__price--total"><span' + ' class="fw-600">' + items[key].data.SUM + '</span></div>' + '</div>' + '</div>' + '</div>';
            }
          }

          num++;
          block.html(html);
        }
      }, {
        key: "updatePropFields",
        value: function updatePropFields(props) {
          function compareNumeric(a, b) {
            if (a.SORT > b.SORT) return 1;
            if (a.SORT == b.SORT) return 0;
            if (a.SORT < b.SORT) return -1;
          }
          //props.properties.sort(compareNumeric);
          let blocks = $('[data-properies]');
          $.each(blocks, function(i,v){
              let block = $(v);

              let groupId = block.attr('data-properies');

              let html = '';

              let groupName = '';
              $.each(props.groups, function(gi, gv){
                if(gv.ID==groupId)
                  groupName=gv.NAME;
              });
              

              for (let i in props.properties) {
                  if (props.properties[i].PROPS_GROUP_ID != groupId) {
                      continue;
                  }
                  if(props.properties[i].IS_ZIP=="Y"){
                    html += '<div class="form-block"><input class="form-block__input" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '<span>*</span>' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>'
                  }
                  else if(props.properties[i].IS_PHONE=="Y"){
                    html += `<div class="form-block">
                                <input class="form-block__input js-mask-tel"
                                        id="soa-property-` + props.properties[i].ID + `"
                                       name="ORDER_PROP_` + props.properties[i].ID + `"
                                       type="tel"
                                       placeholder="+7(9__)-___-__-__"
                                       value="` + props.properties[i].VALUE[0] + `"
                                >
                                <label class="form-block__label">` + props.properties[i].NAME + `` + (props.properties[i].REQUIRED == `Y` ? `<span>*</span>` : ``) + `</label>
                                <div class="form-block__label form-block__label--error">Номер введен не корректно</div>
                            </div>`
                  }
                  else if(props.properties[i].IS_EMAIL=="Y"){
                    html+= `<div class="form-block">
                                <input class="form-block__input"
                                      id="soa-property-` + props.properties[i].ID + `"
                                       name="ORDER_PROP_` + props.properties[i].ID + `"
                                       value="` + props.properties[i].VALUE[0] + `"
                                       type="email">
                                <label class="form-block__label">` + props.properties[i].NAME + `` + (props.properties[i].REQUIRED == `Y` ? `<span>*</span>` : ``) + `</label>
                                <div class="form-block__label form-block__label--error">E-mail введен не корректно</div>
                            </div>`;
                  }
                  else if(props.properties[i].CODE=="PVZ"){
                    html += '<div class="form-block"><input class="form-block__input" placeholder="'+props.properties[i].DESCRIPTION+'" data-code="'+props.properties[i].CODE+'" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '<span>*</span>' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>'
                  }
                  else if(props.properties[i].CODE=="STREET"){
                    html += '<div class="form-block"><input type="hidden" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><input class="form-block__input" placeholder="'+props.properties[i].DESCRIPTION+'" data-code="'+props.properties[i].CODE+'" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '<span>*</span>' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>'
                  }
                  else{
                    html += '<div class="form-block"><input class="form-block__input" placeholder="'+props.properties[i].DESCRIPTION+'" data-code="'+props.properties[i].CODE+'" id="soa-property-' + props.properties[i].ID + '" name="ORDER_PROP_' + props.properties[i].ID + '" value="' + props.properties[i].VALUE[0] + '"><label class="form-block__label">' + props.properties[i].NAME + '' + (props.properties[i].REQUIRED == 'Y' ? '<span>*</span>' : '') + '</label><div class="form-block__label form-block__label--error">ошибка</div></div>'
                  }

              }

              for (var i in props.properties) {
                  if(props.properties[i].CODE!="LOCATION"){
                      continue;
                  }

                  $(".location_hidden_field").attr("name", "ORDER_PROP_" + props.properties[i].ID)
              }

              block.html(html)
              //if(groupName.length!='')
                block.parent().find(".group_name").text("");
                if(html!="")
                  block.parent().find(".group_name").text(groupName);
              $('.js-mask-tel').inputmask({
                showMaskOnHover: false,
                mask: "+7(X99)-999-99-99",
                clearIncomplete: true,
                definitions: {
                    "X": {
                        validator: "[9]",
                    }
                }
              });
              $("input[data-code='STREET']").suggestions({
                token: "a42c2d4c4c3eac2c58f0390cebbae7c12babc393",
                type: "ADDRESS",
                constraints: {locations: {kladr_id:$(".kladr_id").val()}},
                onSelect: function(suggestion) {
                    $("#soa-property-35").val(suggestion.data.postal_code);
                    $("#soa-property-23").val(suggestion.value);
                    /*var data = $('#soa-form').serializeArray();
                    var obj = {};

                    for (var key in data) {
                      obj[data[key].name] = data[key].value;
                    }

                    instance.sendRequest(obj);*/
                  }
                    
              });
              $("input[data-code='ORGNAME']").suggestions({
                  token: "a42c2d4c4c3eac2c58f0390cebbae7c12babc393",
                  type: "PARTY",

                  onSelect: function(suggestion) {
                      $("input[data-code='INN']").val(suggestion.data.inn);
                      $("input[data-code='KPP']").val(suggestion.data.kpp);
                      $("input[data-code='UR_ADRESS']").val(suggestion.data.address.unrestricted_value);

                  }
              });

          });
        }
      }, {
        key: "updateRightBlock",
        value: function updateRightBlock(info) {
          var quantity = 0, period_text = "";

          for (var key in info.GRID.ROWS) {
            quantity += info.GRID.ROWS[key].data.QUANTITY;
          }
          for (var key in info.DELIVERY) {
              if(info.DELIVERY[key].CHECKED === "Y") {
                  period_text = info.DELIVERY[key].PERIOD_TEXT;
              }
          }

          $('[data-all-quantity]').html(quantity);
          $('[data-all-price]').html(info.TOTAL.ORDER_TOTAL_PRICE_FORMATED);
          $('[data-all-discount]').html("- " + info.TOTAL.DISCOUNT_PRICE_FORMATED);
          $('[data-all-delivery]').html("+ " + info.TOTAL.DELIVERY_PRICE_FORMATED);
          $('[data-period-text]').html(period_text)
        }
      },{
        key: "getPickPoint",
        value:function getPickPoint(string){
          $("input[name='DELIVERY_ID']:checked").parents(".form-block").find("button").click();
        }

      }
      ]);
      return SaleOrderAjax;
    }();

    exports.SaleOrderAjax = SaleOrderAjax;

}((this.Meven.Components = this.Meven.Components || {})));


//# sourceMappingURL=script.bundle.js.map

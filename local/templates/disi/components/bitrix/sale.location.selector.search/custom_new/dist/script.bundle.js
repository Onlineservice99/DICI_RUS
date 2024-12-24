this.Meven = this.Meven || {};
(function (exports) {
    'use strict';

    var SaleSearchCity = /*#__PURE__*/function () {
      function SaleSearchCity() {
        babelHelpers.classCallCheck(this, SaleSearchCity);
        this.sessid = BX.bitrix_sessid();
        this.locationBlock = $('#location');
        this.cityUl = $('#js-city-ul');
        this.initEventListener();
      }

      babelHelpers.createClass(SaleSearchCity, [{
        key: "initEventListener",
        value: function initEventListener() {
          var instance = this;
          instance.ajax = false;
          $(document).on('keyup', '#location', function () {
            if ($(this).val().length > 2 && !instance.ajax) {
              instance.ajax = true;
              instance.sendRequest();
		//console.log(instance);
            }
          });
			$(function(){
				instance.getDadata($("#location").val());
			});
          $(document).on('click', "#js-city-ul li", function () {
            var city = instance.cityLast.ITEMS[$(this).data('code')];
            var name = city.DISPLAY;
            var nname = city.DISPLAY;
            /*city.PATH.forEach(function (item) {
              name += ", " + instance.cityLast.ETC.PATH_ITEMS[item].DISPLAY;
            });*/
            console.log()
            instance.getDadata(nname);
            instance.locationBlock.parent().find('input[type="hidden"]').val(city.CODE);
            instance.locationBlock.val(name);
            instance.cityUl.addClass('d-none');
            instance.locationBlock.parent().find('input[type="hidden"]').trigger('change');
          });
        }
      }, 
      {
        key:"getDadata",
        value: function getDadata(query) {
          var instance = this;
          var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
          var token = "a42c2d4c4c3eac2c58f0390cebbae7c12babc393";

          var options = {
              method: "POST",
              mode: "cors",
              headers: {
                  "Content-Type": "application/json",
                  "Accept": "application/json",
                  "Authorization": "Token " + token
              },
              body: JSON.stringify({query: query, from_bound: { value: "city" }, to_bound: { value: "settlement" }})
          }

          fetch(url, options)
          .then(response => response.json())
          .then(result => instance.getSattlement(result.suggestions, query))
          .catch(error => console.log("error", error));
        }
      },
      {
       key:"getSattlement",
       value: function getSattlement(suggestions, query){
          console.log(suggestions);
          if(suggestions[0].data.settlement_type=="с" && (suggestions[0].data.settlement+" "+suggestions[0].data.settlement_type_full)!=query){
            $(".kladr_id").val(suggestions[1].data.kladr_id)
          }else{
            $(".kladr_id").val(suggestions[0].data.kladr_id)
          }
       } 
      },
      {
        key: "sendRequest",
        value: function sendRequest() {
          var instance = this;
          var select = {
            'VALUE': 'ID',
            'DISPLAY': 'NAME.NAME',
            '1': 'TYPE_ID',
            '2': 'CODE'
          };
          var additionals = {
            '1': 'PATH'
          };
          var filter = {
            '=PHRASE': $('#location').val(),
            '=NAME.LANGUAGE_ID': 'ru',
            '=SITE_ID': 's1'
          };
          var result = {
            'select': select,
            'additionals': additionals,
            'filter': filter,
            'version': 2,
            'PAGE_SIZE': 10,
            'PAGE': 0
          };
          $.ajax({
            url: '/bitrix/components/bitrix/sale.location.selector.search/get.php',
            data: result,
            type: 'POST',
            dataType: 'html',
            success: function success(e, b) {
              instance.ajax = false;

              try {
                //hotfix
                var string = e.replace(/\'/g, "\"");
                var json = JSON.parse(string);
                console.log(json);
                instance.createElems(json);
              } catch (ex) {
                console.error(ex);
              }
            }
          });
        }
      }, {
        key: "createElems",
        value: function createElems(json) {
          var text = '';
          this.cityLast = json.data;

          for (var key in json.data.ITEMS) {
            text += "<li data-code='" + key + "'>" + json.data.ITEMS[key].DISPLAY + "</li>";
          }

          this.cityUl.removeClass('d-none').find('ul').html(text);
        }
      }]);
      return SaleSearchCity;
    }();

    exports.SaleSearchCity = SaleSearchCity;

}((this.Meven.Components = this.Meven.Components || {})));
//# sourceMappingURL=script.bundle.js.map

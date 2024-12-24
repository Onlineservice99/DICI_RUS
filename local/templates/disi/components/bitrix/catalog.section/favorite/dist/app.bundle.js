this.Meven = this.Meven || {};
(function (exports,main_core) {
	'use strict';

	var FavoritePage = /*#__PURE__*/function () {
	  function FavoritePage() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, FavoritePage);
	    this.name = options.name;
	    this.initEventListener();
	  }

	  babelHelpers.createClass(FavoritePage, [{
	    key: "initEventListener",
	    value: function initEventListener() {
	      var instance = this;
	      $('input[name="favorites[]"]').on('change', function (e) {
	        instance.updateCount();
	      });
	      $('#favorite-page-add-cart').on('click', function (e) {
	        var arr = [];
	        $('input[name="favorites[]"]:checked').each(function () {
	          arr.push($(this).val());
	        });
	        instance.addCart(arr);
	      });
	      $('#favorite-page-remove').on('click', function (e) {
	        var arr = [];
	        $('input[name="favorites[]"]:checked').each(function () {
	          arr.push($(this).val());
	        });
	        instance.removeFavorite(arr);
	      });
	      var timeOut = null;
	      $('#favorite-page-filter').on('keyup', function (e) {
	        if (timeOut != null) {
	          clearTimeout(timeOut);
	        }

	        var str = $(this).val();
	        timeOut = setTimeout(function (e) {
	          instance.filterElements(str);
	        }, 500);
	      });
	    }
	  }, {
	    key: "updateCount",
	    value: function updateCount() {
	      var count = $('input[name="favorites[]"]:checked').length;
	      $('.favorite-page-count').html(count);
	    }
	  }, {
	    key: "filterElements",
	    value: function filterElements(str) {
	      $.ajax({
	        url: '/personal/favorite/',
	        data: {
	          name: str
	        },
	        method: 'POST',
	        success: function success(e) {
	          $('#favorite-inner-elements').html(e.CONTENT);
	          $('#favorite-inner-pages').html(e.PAGINATION);
	          console.log(e);
	          new Meven.Components.FavoritePage();
	        }
	      });
	    }
	  }, {
	    key: "addCart",
	    value: function addCart(arr) {
	      $.ajax({
	        url: '/local/ajax/api/basket/addelems.php',
	        data: {
	          ids: arr
	        },
	        method: 'POST',
	        success: function success() {
	          $.fancybox.open('<div class="popup"><h3>Элементы добавлены в корзину</h3></div>');
	          BX.onCustomEvent(window, 'OnBasketChange');
	        }
	      });
	    }
	  }, {
	    key: "removeFavorite",
	    value: function removeFavorite(arr) {
	      $.ajax({
	        url: '/local/ajax/api/favorite/removearr.php',
	        data: {
	          ids: arr
	        },
	        method: 'POST',
	        success: function success(e) {
	          $('.icon-link--star .icon-link__count').html(e.count);
	          location.reload();
	        }
	      });
	    }
	  }, {
	    key: "setName",
	    value: function setName(name) {
	      if (main_core.Type.isString(name)) {
	        this.name = name;
	      }
	    }
	  }, {
	    key: "getName",
	    value: function getName() {
	      return this.name;
	    }
	  }]);
	  return FavoritePage;
	}();

	exports.FavoritePage = FavoritePage;

}((this.Meven.Components = this.Meven.Components || {}),BX));
//# sourceMappingURL=app.bundle.js.map

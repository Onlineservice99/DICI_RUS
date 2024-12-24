import {Type} from 'main.core';

export class FavoritePage
{
	constructor(options = {})
	{
		this.name = options.name;
		this.initEventListener()
	}

	initEventListener()
	{
		const instance = this
		$('input[name="favorites[]"]').on('change', function(e){
			instance.updateCount();
		})

		$('#favorite-page-add-cart').on('click', function(e){
			let arr = [];
			$('input[name="favorites[]"]:checked').each(function(){
				arr.push($(this).val())
			})
			instance.addCart(arr)
		})

		$('#favorite-page-remove').on('click', function(e){
			let arr = [];
			$('input[name="favorites[]"]:checked').each(function(){
				arr.push($(this).val())
			})
			instance.removeFavorite(arr)
		})

		let timeOut = null
		$('#favorite-page-filter').on('keyup', function(e){
			if (timeOut != null) {
				clearTimeout(timeOut)
			}
			let str = $(this).val()
			timeOut = setTimeout(function(e){
				instance.filterElements(str)
			}, 500);
		})
	}

	updateCount()
	{
		let count = $('input[name="favorites[]"]:checked').length
		$('.favorite-page-count').html(count);
	}

	filterElements(str)
	{
		$.ajax({
			url: '/personal/favorite/',
			data: {name: str},
			method: 'POST',
			success: function(e) {
				$('#favorite-inner-elements').html(e.CONTENT)
				$('#favorite-inner-pages').html(e.PAGINATION)

				console.log(e)

				new Meven.Components.FavoritePage()
			}
		})
	}

	addCart(arr)
	{
		$.ajax({
			url: '/local/ajax/api/basket/addelems.php',
			data: {ids: arr},
			method: 'POST',
			success: function() {
				$.fancybox.open('<div class="popup"><h3>Элементы добавлены в корзину</h3></div>');
				BX.onCustomEvent(window, 'OnBasketChange');
			}
		})
	}

	removeFavorite(arr)
	{
		$.ajax({
			url: '/local/ajax/api/favorite/removearr.php',
			data: {ids: arr},
			method: 'POST',
			success: function(e) {
				$('.icon-link--star .icon-link__count').html(e.count)
				location.reload()
			}
		})
	}

	setName(name)
	{
		if (Type.isString(name))
		{
			this.name = name;
		}
	}

	getName()
	{
		return this.name;
	}

}
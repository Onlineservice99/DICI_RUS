<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($templateData['SECTION']['ELEMENT_CNT_TITLE'] > 0) {
	?>
	<script>
		var h1 = document.querySelector('.row h1');
		var parent = h1.parentElement;
		var text = document.createElement('div');
		text.className = 'p-xs bg-white px-16 py-8 rounded-lg mb-12';
		text.innerHTML = "<?=$templateData['SECTION']['ELEMENT_CNT_TITLE']?>";
		parent.appendChild(text);
	</script>
	<?
}
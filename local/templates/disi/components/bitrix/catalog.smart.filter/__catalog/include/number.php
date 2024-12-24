<div class="filters__block">
    <a class="filters__title link" href="#ff1" data-toggle="collapse" aria-expanded="true"><?=$item['NAME']?>
        <svg class="icon icon-chevron-up">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
        </svg>
    </a>
    <div class="collapse show" id="ff1">
        <div class="form-block form-block--range">
            <input id="in-<?=$item['ID']?>_from" name="from">
            <input id="in-<?=$item['ID']?>_to" name="to">
        </div>
        <div class="range">
            <div id="range-<?=$item['ID']?>"></div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {

                    let range = document.getElementById('range-<?=$item['ID']?>');
                    let inputFrom = document.getElementById('in-<?=$item['ID']?>_from');
                    let inputTo = document.getElementById('in-<?=$item['ID']?>_to');

                    noUiSlider.create(range, {
                        start: [<?=$item['VALUES']['MIN']['VALUE']?>, <?=$item['VALUES']['MAX']['VALUE']?>],
                        connect: true,
                        step: 1,
                        tooltips: false,
                        range: {
                            'min': <?=$item['VALUES']['MIN']['VALUE']?>,
                            'max': <?=$item['VALUES']['MAX']['VALUE']?>
                        },
                        format: wNumb({
                            decimals: 0,
                            thousand: ' '
                        })
                    });

                    range.noUiSlider.on('update', function(values, handle) {

                        let value = values[handle];

                        if (handle) {
                            inputTo.value = value;
                        } else {
                            inputFrom.value = value;
                        }
                    });

                    inputFrom.addEventListener('change', function() {
                        range.noUiSlider.set([this.value, null]);
                    });

                    inputTo.addEventListener('change', function() {
                        range.noUiSlider.set([null, this.value]);
                    });

                });
            </script>
        </div>
        <div class="text-right pb-16">
            <a class="filters__clear" href="#">Очистить
                <svg class="icon icon-close icon-16 ml-4">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                </svg>
            </a>
        </div>
    </div>
</div>
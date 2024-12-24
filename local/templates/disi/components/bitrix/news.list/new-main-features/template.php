<section class="section mb-72">
    <div class="section__overlay">
        <div class="container mb-52">
            <h1 class="h2 text-center mb-32 mb-lg-44">Онлайн-гипермаркет электромонтажной продукции DИСИ</h1>
            <div class="threes">
                <div class="row">
                    <?php foreach ($arResult['ITEMS'] as $item): ?>
                        <?php $arFile = CFile::GetFileArray($item['PROPERTIES']['FILE']['VALUE']); ?>
                        <div class="col-xl-4 d-flex pb-16">
                            <div class="privilege">
                                <img class="privilege__ic"
                                     src="<?= $arFile['SRC'] ?>" alt="">
                                <div class="pl-16 pl-sm-24">
                                    <div class="mb-8 h4"><?= $item['NAME'] ?></div>
                                    <p class="p-md mb-0"><?= $item['PREVIEW_TEXT'] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
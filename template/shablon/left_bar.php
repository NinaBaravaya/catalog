<div id="sidebar">
    <h3>Категории</h3>
    <? if($brands) :?>
        <div id="list_brands">
            <? foreach($brands as $key=>$item_b) :?>
                <? if($item_b['next_lvl']) :?>
                    <p><a href="#"><?=$item_b[0];?></a></p>
                    <div>
                        <a href="<?=SITE_URL;?>catalog/parent/<?=$key;?>">Все типы</a><br />
                        <? foreach($item_b['next_lvl'] as $k=>$v) :?>
                            <a href="<?=SITE_URL;?>catalog/brand/<?=$k?>"><?=$v;?></a><br />
                        <? endforeach; ?>

                    </div>
                <? else : ?>
                    <a href="<?=SITE_URL;?>catalog/brand/<?=$key;?>"><?=$item_b['0']?></a><br />
                <? endif;?>
            <? endforeach; ?>
        </div>
    <? endif;?>

</div>



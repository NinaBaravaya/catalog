<div id="sidebar">

    <h3>Категории</h3>

    <? if($brands) :?>
        <div id="list_brands">
            <ul class="sidebar_menu">
                <? foreach($brands as $key=>$item_b) :?>
                    <? if($item_b['next_lvl']) :?>
                        <p><a href="#"><?=$item_b[0];?></a></p>
                        <div>
                            <li><a href="<?=SITE_URL;?>catalog/parent/<?=$key;?>">Все типы</a></li>
                            <? foreach($item_b['next_lvl'] as $k=>$v) :?>
                                <li><a href="<?=SITE_URL;?>catalog/brand/<?=$k?>"><?=$v;?></a></li>
                            <? endforeach; ?>

                        </div>
                    <? else : ?>
                        <li><a href="<?=SITE_URL;?>catalog/brand/<?=$key;?>"><?=$item_b['0']?></a></li>
                    <? endif;?>
                <? endforeach; ?>
            </ul>
        </div>
    <? endif;?>
</div> <!-- END of sidebar -->
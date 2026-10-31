
<div class="new-design">
    <div class="take-consult">
        <a href='https://t.me/+79229776867' target='_blank'> 
            <img src="<?= SITE_TEMPLATE_PATH; ?>/images/socials_tg.svg">
            <?= GetMessage('SBB_CONSULT');?>
        </a>                                 
    </div>
    <div class="basket-checkout-container delivery" data-entity="basket-checkout-aligner">
            <span><?=GetMessage('SBB_BASKET_DEVILERY_INFO') ?></span><?= classBuildSCG::buildSVG('info'); ?>
    </div>
    <div class="basket-checkout-container bonus" data-entity="basket-checkout-aligner">
            <span><?=GetMessage('SBB_BASKET_BONUS_INFO') ?></span><?= classBuildSCG::buildSVG('info'); ?>
    </div>
</div>

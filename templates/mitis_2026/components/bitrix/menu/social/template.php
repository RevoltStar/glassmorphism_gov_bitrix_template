<!-- Социальные сети -->
<div class="d-flex gap-2 mt-3">
	<?foreach($arResult as $arItem):?>
		<a href="<?=htmlspecialchars($arItem['LINK'])?>" class="text-decoration-none" target="_blank">
        	<div class="social-icon">
            	<img
					src="<?=htmlspecialchars($arItem['PARAMS']['IMAGE'])?>"
					alt="Логотип <?=htmlspecialchars($arItem['TEXT'])?>"
					title="Перейти в группу Министерства информационных технологий в <?=htmlspecialchars($arItem['TEXT'])?>"
				>
            </div>
        </a>
	<?endforeach?>
</div>
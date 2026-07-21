<?php
$gosuslugiOrgId = max(0, (int)get_info('gosuslugi_org_id', 0));

if ($gosuslugiOrgId === 0) {
    return;
}
?>
<div id="e329fb40-widget-pos" class="external-vote-widget" style="height: 100%; border: 1px solid gray;"></div>
<script id="e329fb40" src="https://pos.gosuslugi.ru/og/widget/js/main.js" data-src-host="https://pos.gosuslugi.ru/og" data-org-id="<?=$gosuslugiOrgId?>"></script>

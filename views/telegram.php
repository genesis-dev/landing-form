*Поступила новая заявка!*
<?php foreach ($this->fields as $key => $field): if ((isset($this->siteConfig["include"]) && in_array($key, $this->siteConfig["include"])) || !isset($this->siteConfig["include"])): ?>
_<?= $field['name'] ?>_: <?= $field['value'] ?>

<?php endif; endforeach; ?>
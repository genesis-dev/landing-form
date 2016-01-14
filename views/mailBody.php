<h2>Данные клиента</h2>
<table>
    <tbody>
    <?php foreach ($this->fields as $field): if((isset($this->siteConfig["include"]) && in_array($field["key"], $this->siteConfig["include"])) || !isset($this->siteConfig["include"])): ?>
        <tr>
            <th><?= $field['name'] ?></th>
            <td><?= $field['value'] ?></td>
        </tr>
    <?php endif; endforeach; ?>
    </tbody>
</table>
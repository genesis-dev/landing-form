<h2>Данные клиента</h2>
<table>
    <tbody>
    <?php foreach ($this->fields as $field): ?>
        <tr>
            <th><?= $field['name'] ?></th>
            <td><?= $field['value'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
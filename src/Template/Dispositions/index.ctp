<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Disposition'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Letters'), ['controller' => 'Letters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Letter'), ['controller' => 'Letters', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Evidences'), ['controller' => 'Evidences', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Evidence'), ['controller' => 'Evidences', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="dispositions index large-9 medium-8 columns content">
    <h3><?= __('Dispositions') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('parent_id') ?></th>
                <th><?= $this->Paginator->sort('letter_id') ?></th>
                <th><?= $this->Paginator->sort('user_id') ?></th>
                <th><?= $this->Paginator->sort('lft') ?></th>
                <th><?= $this->Paginator->sort('rght') ?></th>
                <th><?= $this->Paginator->sort('recipient') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispositions as $disposition): ?>
            <tr>
                <td><?= $this->Number->format($disposition->id) ?></td>
                <td><?= $disposition->has('parent_disposition') ? $this->Html->link($disposition->parent_disposition->id, ['controller' => 'Dispositions', 'action' => 'view', $disposition->parent_disposition->id]) : '' ?></td>
                <td><?= $disposition->has('letter') ? $this->Html->link($disposition->letter->id, ['controller' => 'Letters', 'action' => 'view', $disposition->letter->id]) : '' ?></td>
                <td><?= $disposition->has('user') ? $this->Html->link($disposition->user->id, ['controller' => 'Users', 'action' => 'view', $disposition->user->id]) : '' ?></td>
                <td><?= $this->Number->format($disposition->lft) ?></td>
                <td><?= $this->Number->format($disposition->rght) ?></td>
                <td><?= $this->Number->format($disposition->recipient) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $disposition->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $disposition->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $disposition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $disposition->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>

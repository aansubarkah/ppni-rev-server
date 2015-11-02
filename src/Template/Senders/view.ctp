<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sender'), ['action' => 'edit', $sender->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sender'), ['action' => 'delete', $sender->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sender->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Senders'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sender'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Letters'), ['controller' => 'Letters', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Letter'), ['controller' => 'Letters', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="senders view large-9 medium-8 columns content">
    <h3><?= h($sender->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($sender->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($sender->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Active') ?></th>
            <td><?= $sender->active ? __('Yes') : __('No'); ?></td>
         </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Letters') ?></h4>
        <?php if (!empty($sender->letters)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Sender Id') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Via Id') ?></th>
                <th><?= __('Number') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Content') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th><?= __('Isread') ?></th>
                <th><?= __('Active') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($sender->letters as $letters): ?>
            <tr>
                <td><?= h($letters->id) ?></td>
                <td><?= h($letters->sender_id) ?></td>
                <td><?= h($letters->user_id) ?></td>
                <td><?= h($letters->via_id) ?></td>
                <td><?= h($letters->number) ?></td>
                <td><?= h($letters->date) ?></td>
                <td><?= h($letters->content) ?></td>
                <td><?= h($letters->created) ?></td>
                <td><?= h($letters->modified) ?></td>
                <td><?= h($letters->isread) ?></td>
                <td><?= h($letters->active) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Letters', 'action' => 'view', $letters->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Letters', 'action' => 'edit', $letters->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Letters', 'action' => 'delete', $letters->id], ['confirm' => __('Are you sure you want to delete # {0}?', $letters->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>

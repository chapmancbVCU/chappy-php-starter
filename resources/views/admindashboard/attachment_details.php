<?php use Core\Lib\Utilities\DateTime; ?>
<?php
$e = fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$filters = $this->filter->activeFilters();

/** Sortable column header with a direction indicator. */
$sortHeader = function (string $column, string $label) use ($e): string {
    $arrow = '';
    if ($this->filter->sortColumn() === $column) {
        $arrow = $this->filter->sortDirection() === 'asc' ? ' &#9650;' : ' &#9660;';
    }
    return '<a href="' . $e($this->filter->sortUrl($column)) . '" class="text-reset text-decoration-none">'
        . $e($label) . $arrow . '</a>';
};
?>
<?php $this->setSiteTitle("Administration"); ?>
<?php $this->start('body'); ?>
<h1 class="text-center">Administration Dashboard</h1>

<form method="get" class="row g-2 mb-3 align-items-end">
    <div class="col-md-5">
        <label for="q" class="form-label">Search</label>
        <input type="search" id="q" name="q" class="form-control"
               placeholder="Username, email, or name" value="<?= $e($this->filter->term()) ?>">
    </div>
    <div class="col-md-2">
        <label for="acl" class="form-label">Access Level</label>
        <select id="acl" name="acl" class="form-select">
            <option value="">All</option>
            <?php foreach ($this->aclOptions as $value => $label): ?>
                <option value="<?= $e($value) ?>" <?= ($filters['acl'] ?? null) === (string)$value ? 'selected' : '' ?>>
                    <?= $e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <label for="inactive" class="form-label">Status</label>
        <select id="inactive" name="inactive" class="form-select">
            <option value="">All</option>
            <?php foreach ($this->statusOptions as $value => $label): ?>
                <option value="<?= $e($value) ?>" <?= ($filters['inactive'] ?? null) === (string)$value ? 'selected' : '' ?>>
                    <?= $e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php // Keep the current sort when a new search is submitted. ?>
    <?php if (isset($this->filter->query()['sort'])): ?>
        <input type="hidden" name="sort" value="<?= $e($this->filter->sortColumn()) ?>">
        <input type="hidden" name="dir" value="<?= $e($this->filter->sortDirection()) ?>">
    <?php endif; ?>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">
            <i class="fa fa-search"></i> Search
        </button>
        <?php if ($this->filter->isActive()): ?>
            <a href="?" class="btn btn-outline-secondary flex-fill">Clear</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($this->pagination->totalItems() > 0): ?>
    <p class="text-muted small mb-2">
        Showing <?= $this->pagination->firstItem() ?>–<?= $this->pagination->lastItem() ?>
        of <?= $this->pagination->totalItems() ?> users
    </p>
<?php endif; ?>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th><?= $sortHeader('username', 'Username') ?></th>
            <th><?= $sortHeader('acl', 'Access Level') ?></th>
            <th><?= $sortHeader('inactive', 'Status') ?></th>
            <th><?= $sortHeader('created_at', 'Created') ?></th>
            <th><?= $sortHeader('updated_at', 'Last Update') ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->users as $user): ?>
            <tr>
                <td><?= $e($user->username) ?></td>
                <td><?= $e($user->acl) ?></td>
                <td><?= ($user->inactive == 0) ? 'Active' : 'Inactive' ?></td>
                <td><?= DateTime::timeAgo($user->created_at) ?></td>
                <td><?= DateTime::timeAgo($user->updated_at) ?></td>
                <td class="text-center">
                    <a href="<?= route('admindashboard.details', [$user->id]) ?>" class="btn btn-info btn-sm w-100">
                        <i class="fa fa-user"></i> Details
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($this->users)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <?= $this->filter->isActive() ? 'No users match your search.' : 'No other users yet.' ?>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->pagination->render() ?>

<div class="widget-container">
    <?= $this->renderWidgets('dashboard.index', $this->widgets) ?>
</div>
<?php $this->end(); ?>
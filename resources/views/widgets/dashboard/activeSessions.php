<?php use App\Models\Users; ?>
<?php use Core\Lib\Utilities\DateTime; ?>

<h1 class="text-center mt-5">User Sessions</h1>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <th>User Name</th>
        <th>Created</th>
    </thead>
    <tbody>
        <?php foreach($data as $session): ?>
            <tr>
                <td><?= Users::findById($session->user_id)->username ?></td>
                <td><?= DateTime::timeAgo($session->created_at) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
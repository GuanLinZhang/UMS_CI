
<?php foreach ($users as $user): ?>
    <h3><?php echo $user['id']; ?></h3>
    <h3><?php echo $user['username']; ?></h3>
    <h3><?php echo $user['password']; ?></h3>
    <h3><?php echo $user['createdTime']; ?></h3>
    <h3><?php echo $user['createdUser']; ?></h3>
    <h3><?php echo $user['modifiedTime']; ?></h3>
    <h3><?php echo $user['modifiedUser']; ?></h3>
    <h3><?php echo $user['status']; ?></h3>
    <h3><?php echo $user['role']; ?></h3>

<?php endforeach; ?>
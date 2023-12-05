<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');
secure();

include('includes/header.php');

if (isset($_GET['delete'])){
    if ($stm = $connect->prepare('DELETE FROM users WHERE id = ?')) {
        $stm->bind_param('s', $_GET['delete']);
        $stm->execute();

        set_message("You have successfully deleted User: " . $_POST['username']);

        header('Location: users.php');
        $stm->close();
        die();
    }
}

if ($stm = $connect->prepare('SELECT * FROM users')){
    $stm->execute();
    $result = $stm->get_result();

    if($result->num_rows > 0){
echo <<< HTMLbegin
<div class="container mt-5">
<div class="row justify-content-center">
    <div class="col-md-6">
    <h1 class="display-3">Users management</h1>
    <table class="table table-striped table-hover">
    
    <th>Id</th>
    <th>Username</th>
    <th>Email</th>
    <th>Status</th>
    <th>Modify User</th>
    </tr>
HTMLbegin;

while ($record = mysqli_fetch_assoc($result)){
    echo <<< HTMLmiddle
    <tr>
    <td>$record[id]</td>
    <td>$record[username]</td>
    <td>$record[email]</td>
    <td>$record[active]</td>
    <td><a href="users_edit.php?id=$record[id]">Edit | </a>
    <a href="users.php?delete=$record[id]">Delete</a></td>
    </tr>
HTMLmiddle;

}

echo <<< HtmlRest


</table>
<a href="users_add.php">Add new User</a>
</div>
</div>
</div>
HtmlRest;

    } else {
        echo 'No users found';
    }
    $stm->close();

} else {
    echo 'Could not prepare statement!';
}

include('includes/footer.php');
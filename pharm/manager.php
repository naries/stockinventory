<?php
session_start();
include 'ManagerClass.php';
include 'messageClass.php';

use Pharm\Manager;


$manager = new Manager;
$man = $manager->fetch();

extract($_REQUEST);

if (isset($message)) {
    $type = (!isset($error)) ? 0 : 1;
    $msg = new \UserInterface\message;
    $msg->show($message, $type);
}
?>
<h1 style = "margin-bottom: 1em;">Existing Managers</h1>
    <table class = "manager-table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Username</th>
                <th>Password</th>
                <th>staff_id</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php
        for ($i = 0; $i < sizeof($man); $i++) {
            extract($man[$i], EXTR_PREFIX_ALL, 'f');
        ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $f_username ?></td>
                <td><?= $f_password ?></td>
                <td><?= $f_staff_id ?></td>
                <td><?= $f_phone ?></td>
                <td><?= $f_address ?></td>
                <td>
                    <a class = "edit" href="update_manager.php?id=<?= $f_id ?>&action=update&username=<?= $f_username ?>&password=<?=$f_password?>&staff_id=<?= $f_staff_id ?>&email=<?=$f_email?>&phone=<?= $f_phone ?>&address=<?= $f_address ?>">Edit</a>
                    <a  class = "delete" href="update_manager.php?id=<?= $f_id ?>&action=delete">Delete</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <form class = 'manager-class' action = "add_a_manager.php">
    <div class = "form-head"> Add a manager </div>
    <div class = "form-body">
    <label>Username</label>
    <input type="text" name="user" required><br>
    <label>Password</label>
    <input type="password" name="pass" required><br>
    <label> Phone </label>
    <input type="phone" name="phone" required><br>
    <label> E-mail </label>
    <input type="email" name="email" required><br>
    <label> Address </label>
    <textarea name="textarea" required></textarea><br>
    <input type="submit" name="submit" value="Add Manager">
    </div>
</form>
<script>
$('form.manager-class').on('submit', function (e) {
    e.preventDefault();
    k = $(this)
        .closest('form');
    url = k.attr('action');

    data = {
        'user': k.find('input[name=user]').val(),
        'pass': k.find('input[name=pass]').val(),
        'phone': k.find('input[name=phone]').val(),
        'email': k.find('input[name=email]').val(),
        'textarea': k.find('textarea[name=textarea]').val(),
    };

    $.ajax({
        url: url,
        data: data,
        beforeSend: function (){
            $('input[type=submit]')
            .attr({
                'value': 'Processing...', 
                'disabled': 'disabled'
            });
        },
        success: function (data) {
            $('.form-body')
                .prepend($('<div />')
                .addClass('message')
                .html(data));

            $('input[type=submit]')
                .attr({
                    'value': 'Add Manager', 
                    'enabled': 'enabled'
                });

            $('.settings-menu')
                .trigger('click');
        }
    });   
});
$('.manager-table a').on('click', function(e) {
    e.preventDefault();
    k = $(this);
    url = $(this)
        .attr('href');
    if(k.hasClass('delete')) {
        appender = 0;
    }
    $.ajax({
        url: url,
        beforeSend: function() {
            $('.manager-table a')
                .show();
            k.hide();
            $('.manager-edit-class')
                .remove();
        },
        success: function(data) {
            if(typeof appender !== "undefined"){
                $('.settings-menu')
                    .trigger('click');
            } else {
                $('.content')
                    .append(
                        $('<div/>')
                            .addClass('manager-edit-class')
                            .css('display', 'inline-block')
                            .html(data)
                    );
            }
            
        }
    })
});
</script>
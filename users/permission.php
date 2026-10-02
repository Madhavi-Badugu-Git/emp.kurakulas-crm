<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

if(isset($_GET['id'])){
    $user_id = $_GET['id'];

    // Fetch user details
    $query = mysqli_query($conn, "SELECT * FROM tbl_user WHERE id = '$user_id'");
    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_assoc($query);
        $manage_icons = $row['manage_icons'];

        // Ensure it's an array
        $manage_icons = json_decode($manage_icons, true);
        if (!is_array($manage_icons)) {
            $manage_icons = []; // Set to empty array if decoding fails
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr">

<?php include('../includes/header.php'); ?>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('../includes/sideMenu.php'); ?>
            <div class="layout-page">
                <?php include('../includes/navbar.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Manage Icon Permission</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Image</th>
                                                    <th>Permission</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_manage_icon ORDER BY icon_name ASC");
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $icon_id = $row['id'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['icon_name']; ?></td>
                                                    <td><?= $row['icon_description']; ?></td>
                                                    <td><img src="../uploads/manage-icons/<?= $row['icon_image'] ?>" alt="Image" style="height:50px;"></td>
                                                    <td>
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                class="form-check-input toggle-permission"
                                                                data-id="<?= $icon_id; ?>"
                                                                data-user-id="<?= $user_id; ?>"
                                                                <?= in_array($icon_id, $manage_icons) ? 'checked' : ''; ?>>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/script.php'); ?>

    <script>
    $(document).ready(function() {
        $('.toggle-permission').change(function() {
            let icon_id = $(this).data('id');
            let user_id = $(this).data('user-id');
            let checked = $(this).prop('checked') ? 1 : 0;

            $.ajax({
                url: 'update_permission.php',
                type: 'POST',
                data: { user_id: user_id, icon_id: icon_id, checked: checked },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
    </script>

</body>
</html>

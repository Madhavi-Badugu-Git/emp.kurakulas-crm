<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="list";</script>';
    exit();
}

$config_id = (int)$_GET['id'];

$query = "SELECT * FROM tbl_statutory_config WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $config_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Statutory Config not found!"); window.location.href="list";</script>';
    exit();
}

$config = $result->fetch_assoc();

if (isset($_POST['form_submit'])) {
    $config_name = mysqli_real_escape_string($conn, $_POST['config_name']);
    $applicable_on = mysqli_real_escape_string($conn, $_POST['applicable_on']);
    $employee_contribution_pct = $_POST['employee_contribution_pct'] !== '' ? mysqli_real_escape_string($conn, $_POST['employee_contribution_pct']) : 0;
    $employer_contribution_pct = $_POST['employer_contribution_pct'] !== '' ? mysqli_real_escape_string($conn, $_POST['employer_contribution_pct']) : 0;
    $fixed_amount = $_POST['fixed_amount'] !== '' ? mysqli_real_escape_string($conn, $_POST['fixed_amount']) : 0;
    $effective_from = mysqli_real_escape_string($conn, $_POST['effective_from']);

    $update = "UPDATE tbl_statutory_config SET
        config_name = ?, applicable_on = ?, employee_contribution_pct = ?, employer_contribution_pct = ?, fixed_amount = ?, effective_from = ?
        WHERE id = ?";
    $stmt2 = $conn->prepare($update);
    $stmt2->bind_param("ssdddsi", $config_name, $applicable_on, $employee_contribution_pct, $employer_contribution_pct, $fixed_amount, $effective_from, $config_id);

    if ($stmt2->execute()) {
        echo '<script>alert("Statutory Config updated!"); window.location.href="list";</script>';
        exit();
    } else {
        echo '<script>alert("Update failed. Please try again.");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

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
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Edit Statutory Config</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Config Name</label>
                                                    <input type="text" class="form-control" name="config_name"
                                                        value="<?= htmlspecialchars($config['config_name']); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Applicable On</label>
                                                    <select name="applicable_on" class="form-select">
                                                        <?php foreach (['gross_salary' => 'Gross Salary', 'basic_salary' => 'Basic Salary', 'fixed' => 'Fixed Amount'] as $val => $label): ?>
                                                        <option value="<?= $val ?>" <?= $config['applicable_on'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Employee Contribution (%)</label>
                                                    <input type="text" class="form-control" name="employee_contribution_pct"
                                                        value="<?= htmlspecialchars($config['employee_contribution_pct']); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Employer Contribution (%)</label>
                                                    <input type="text" class="form-control" name="employer_contribution_pct"
                                                        value="<?= htmlspecialchars($config['employer_contribution_pct']); ?>">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Fixed Amount</label>
                                                    <input type="text" class="form-control" name="fixed_amount"
                                                        value="<?= htmlspecialchars($config['fixed_amount']); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Effective From</label>
                                                    <input type="date" class="form-control" name="effective_from"
                                                        value="<?= htmlspecialchars($config['effective_from']); ?>">
                                                </div>
                                            </div>
                                            <button type="submit" name="form_submit" class="btn btn-primary mt-3">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include('../includes/footer.php'); ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <?php include('../includes/script.php'); ?>
</body>

</html>

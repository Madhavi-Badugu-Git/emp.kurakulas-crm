<?php

$sql = "
    SELECT 
        u.id,
        u.rank,
        u.department_id,
        u.designation_id,
        u.is_teamlead,
        dept.department_name,
        d.designation_name
    FROM tbl_user u
    LEFT JOIN tbl_department dept ON u.department_id = dept.id
    LEFT JOIN tbl_designation d ON u.designation_id = d.id
    WHERE u.username = '$loggedInUser'
    LIMIT 1
";



$res = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($res);

// $res = mysqli_query($conn, $sql);
// $user = mysqli_fetch_assoc($res);

// ============ DEBUG START ============
// echo "<pre style='background:#000;color:#0f0;padding:15px;font-size:13px;
//       position:fixed;top:0;left:0;width:100%;z-index:999999;
//       max-height:400px;overflow:auto;'>";
// echo "<b>=== USER ARRAY DEBUG ===</b><br>";
// echo "loggedInUser = " . ($loggedInUser ?? 'NOT SET') . "<br>";
// echo "loggedInUserRank = " . ($loggedInUserRank ?? 'NOT SET') . "<br>";
// echo "SQL error: " . (mysqli_error($conn) ?: 'none') . "<br>";
// echo "Number of rows: " . mysqli_num_rows($res) . "<br><br>";
// print_r($user);
// echo "</pre>";
// ============ DEBUG END ============

// ============================================================
// HELPER FUNCTIONS FOR MENU HIGHLIGHTING - ADDED
// ============================================================
function isActive($paths)
{
    if (!is_array($paths)) {
        $paths = [$paths];
    }
    foreach ($paths as $path) {
        if (strpos($_SERVER['REQUEST_URI'], $path) !== false) {
            return true;
        }
    }
    return false;
}

function isExactActive($paths)
{
    if (!is_array($paths)) {
        $paths = [$paths];
    }
    foreach ($paths as $path) {
        if (strpos($_SERVER['PHP_SELF'], $path) !== false) {
            return true;
        }
    }
    return false;
}

?>



<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <?php

    if ($loggedInUserRank == 'superAdmin') {

        ?>

        <ul class="menu-inner py-1">



            <div class="app-brand demo ">

                <a href="../dashboard/superAdmin" class="app-brand-link">

                    <span class="app-brand-logo demo">

                        <img src="<?php echo $comp_logo; ?>" alt="Logo" style="height:50px;">

                </a>

                </span>

                <!-- <span class="app-brand-text demo menu-text fw-bold ms-2">Kinfomedia</span> -->

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">

                    <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>

                </a>

            </div>



            <div class="menu-inner-shadow"></div>



            <!-- Dashboards -->

            <?php $isDashboard = isActive('dashboard/superAdmin'); ?>
            <li class="menu-item <?php echo $isDashboard ? 'active open' : ''; ?>">
                <a href="../dashboard/superAdmin" class="menu-link <?php echo $isDashboard ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-home-smile"></i>
                    <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>



            <!-- emp master -->

            <?php
            $isEmpMaster = isActive(['users', 'department', 'designation', 'family', 'ref-relation']);
            ?>
            <li class="menu-item <?php echo $isEmpMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isEmpMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-crown"></i>
                    <div class="text-truncate" data-i18n="Emp Master">Emp Master</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isExactActive('users/add') ? 'active' : ''; ?>">
                        <a href="../users/add" class="menu-link <?php echo isExactActive('users/add') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Add Users">Add Users</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isExactActive('users/list') ? 'active' : ''; ?>">
                        <a href="../users/list"
                            class="menu-link <?php echo isExactActive('users/list') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Active User List">Active User List</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isExactActive('users/inActive-list') ? 'active' : ''; ?>">
                        <a href="../users/inActive-list"
                            class="menu-link <?php echo isExactActive('users/inActive-list') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="InActive User List">InActive User List</div>
                        </a>
                    </li>



                    <!-- Department -->

                    <li class="menu-item <?php echo isActive('department') ? 'active' : ''; ?>">
                        <a href="../department/add" class="menu-link <?php echo isActive('department') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Emp Department">Emp Department</div>
                        </a>
                    </li>



                    <!-- designation -->

                    <li class="menu-item <?php echo isActive('designation') ? 'active' : ''; ?>">
                        <a href="../designation/add"
                            class="menu-link <?php echo isActive('designation') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Emp Designation">Emp Designation</div>
                        </a>
                    </li>



                    <!-- relation -->

                    <li class="menu-item <?php echo isActive('family/relation') ? 'active' : ''; ?>">
                        <a href="../family/relation"
                            class="menu-link <?php echo isActive('family/relation') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-group"></i> -->
                            <div class="text-truncate" data-i18n="Family Relation"> Family Relation</div>
                        </a>
                    </li>



                    <!--ref relation -->

                    <li class="menu-item <?php echo isActive('ref-relation') ? 'active' : ''; ?>">
                        <a href="../ref-relation/add"
                            class="menu-link <?php echo isActive('ref-relation') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-group"></i> -->
                            <div class="text-truncate" data-i18n="Ref Relation"> Ref Relation</div>
                        </a>
                    </li>



                </ul>

            </li>



            <!-- PAYROLL MASTER - CORRECTED SECTION -->
            <?php
            $isPayrollMaster = isActive(['attendance', 'payroll', 'Statutory_Config']);
            ?>
            <li class="menu-item <?php echo $isPayrollMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isPayrollMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                    <div class="text-truncate" data-i18n="Payroll Master">Payroll Master</div>
                </a>

                <ul class="menu-sub">

                    <!-- Attendance -->
                    <li class="menu-item <?php echo isActive('attendance') ? 'active' : ''; ?>">
                        <a href="../attendance/add.php"
                            class="menu-link <?php echo isActive('attendance') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Attendance"> Attendance</div>
                        </a>
                    </li>
                    <!-- Payroll -->
                    <li class="menu-item <?php echo isActive('payroll') ? 'active' : ''; ?>">
                        <a href="../payroll/add.php" class="menu-link <?php echo isActive('payroll') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Payroll">Payroll</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('salary') ? 'active' : ''; ?>">
                        <a href="../attendance/employee-salary-list.php"
                            class="menu-link <?php echo isActive('salary') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Salary">Salary</div>
                        </a>
                    </li>

                    <!-- Statutory -->
                    <li class="menu-item <?php echo isActive('Statutory_Config') ? 'active' : ''; ?>">
                        <a href="../Statutory_Config/add.php"
                            class="menu-link <?php echo isActive('Statutory_Config') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Statutory">Statutory</div>
                        </a>
                    </li>
                </ul>
            </li>


            <!-- Leave Management -->
            <li
                class="menu-item <?php echo (isExactActive('leave/apply.php') || isExactActive('leave/my_leaves.php')) ? 'active open' : ''; ?>">
                <a href="javascript:void(0);"
                    class="menu-link menu-toggle <?php echo (isExactActive('leave/apply.php') || isExactActive('leave/my_leaves.php')) ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                    <div class="text-truncate" data-i18n="Leave Management">Leave Management</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?php echo isExactActive('leave/apply.php') ? 'active' : ''; ?>">
                        <a href="../leave/apply.php"
                            class="menu-link <?php echo isExactActive('leave/apply.php') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Apply Leave">Apply Leave</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo isExactActive('leave/approve.php') ? 'active' : ''; ?>">
                        <a href="../leave/approve.php"
                            class="menu-link <?php echo isExactActive('leave/approve.php') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Leave Request">Leave Approvals Request </div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- ===== NOTIFICATION MENU - superAdmin ===== -->
            <?php
            $isNotification = isActive(['notification/add', 'notification/list', 'notification/edit']);
            ?>
            <li class="menu-item <?php echo $isNotification ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isNotification ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-bell"></i>
                    <div class="text-truncate" data-i18n="Notifications">Notifications</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?php echo isExactActive('notification/add') ? 'active' : ''; ?>">
                        <a href="../notification/add.php"
                            class="menu-link <?php echo isExactActive('notification/add') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Add Notification">Add Notification</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo isExactActive('notification/list') ? 'active' : ''; ?>">
                        <a href="../notification/list.php"
                            class="menu-link <?php echo isExactActive('notification/list') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Notification List">Notification List</div>
                        </a>
                    </li>
                </ul>
            </li>


            <!-- accounts / finance master -->

            <?php
            $isAccounts = isActive(['account_bank', 'account_dsa', 'bank_statement', 'invoice', 'invoice_payment']);
            ?>
            <li class="menu-item <?php echo $isAccounts ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isAccounts ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                    <div class="text-truncate" data-i18n="Accounts / Finance">Accounts / Finance</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('account_bank') ? 'active' : ''; ?>">
                        <a href="../account_bank/add"
                            class="menu-link <?php echo isActive('account_bank') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Account Bank">Account Bank</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('account_dsa') ? 'active' : ''; ?>">
                        <a href="../account_dsa/add"
                            class="menu-link <?php echo isActive('account_dsa') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Account DSA">Account DSA</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('bank_statement') ? 'active' : ''; ?>">
                        <a href="../bank_statement/add"
                            class="menu-link <?php echo isActive('bank_statement') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Bank Adjustment">Bank Adjustment</div>
                        </a>
                    </li>



                    <li class="menu-item <?php echo isActive('invoice/add') ? 'active' : ''; ?>">
                        <a href="../invoice/add" class="menu-link <?php echo isActive('invoice/add') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Invoice">Invoice</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('invoice_payment') ? 'active' : ''; ?>">
                        <a href="../invoice_payment/add"
                            class="menu-link <?php echo isActive('invoice_payment') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Invoice">Invoice Payment Status</div>
                        </a>
                    </li>


                </ul>

            </li>



            <!-- database  -->

            <?php $isDatabase = isActive('database/database'); ?>
            <li class="menu-item <?php echo $isDatabase ? 'active' : ''; ?>">
                <a href="../database/database" class="menu-link <?php echo $isDatabase ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-data"></i>
                    <div class="text-truncate" data-i18n=" DataBase">DataBase</div>
                </a>
            </li>



            <?php $isMedia = isActive('gallery'); ?>
            <li class="menu-item <?php echo $isMedia ? 'active' : ''; ?>">
                <a href="../gallery/uploadgallery.php" class="menu-link <?php echo $isMedia ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div class="text-truncate" data-i18n="Training">Media Section</div>
                </a>
            </li>



            <!-- appointment -->

            <?php $isAppointment = isActive('appointment/appointment'); ?>
            <li class="menu-item <?php echo $isAppointment ? 'active' : ''; ?>">
                <a href="../appointment/appointment" class="menu-link <?php echo $isAppointment ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                    <div class="text-truncate" data-i18n="Appointment">Appointment</div>
                </a>
            </li>



            <!-- partner master -->

            <?php
            $isPartnerMaster = isActive(['partner_type', 'partner_master']);
            ?>
            <li class="menu-item <?php echo $isPartnerMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isPartnerMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-group"></i>
                    <div class="text-truncate" data-i18n="Partner Master">Partner Master</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('partner_type') ? 'active' : ''; ?>">
                        <a href="../partner_type/add"
                            class="menu-link <?php echo isActive('partner_type') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Type Of Partner">Type Of Partner</div>
                        </a>
                    </li>

                    <!-- <li class="menu-item">

                        <a href="../partner/partner" class="menu-link">

                            <div class="text-truncate" data-i18n="Partner"> Partner</div>

                        </a>

                    </li> -->

                    <li class="menu-item <?php echo isActive('partner_master/status') ? 'active' : ''; ?>">
                        <a href="../partner_master/status"
                            class="menu-link <?php echo isActive('partner_master/status') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Status"> Status</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('partner_master/sub_status') ? 'active' : ''; ?>">
                        <a href="../partner_master/sub_status"
                            class="menu-link <?php echo isActive('partner_master/sub_status') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Sub Status"> Sub Status</div>
                        </a>
                    </li>

                </ul>

            </li>

            <!-- partner master -->



            <!-- partner list -->

            <?php $isPartner = isActive('partner/partner'); ?>
            <li class="menu-item <?php echo $isPartner ? 'active' : ''; ?>">
                <a href="../partner/partner" class="menu-link <?php echo $isPartner ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-link-alt"></i>
                    <div class="text-truncate" data-i18n="Partner">Partner</div>
                </a>
            </li>

    
            <!-- connectors list-->

            <?php $isConnectors = isActive('connectors/connectors'); ?>
            <li class="menu-item <?php echo $isConnectors ? 'active' : ''; ?>">
                <a href="../connectors/connectors" class="menu-link <?php echo $isConnectors ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-network-chart"></i>
                    <div class="text-truncate" data-i18n="Connectors">Connectors </div>
                </a>
            </li>

          



            <!-- agent list-->

            <?php $isAgent = isActive('agent-data/agent'); ?>
            <li class="menu-item <?php echo $isAgent ? 'active' : ''; ?>">
                <a href="../agent-data/agent" class="menu-link <?php echo $isAgent ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div class="text-truncate" data-i18n="Agent">Agent</div>
                </a>
            </li>




            <!--dsa code-->

            <?php $isDsa = isActive('dsa_code'); ?>
            <li class="menu-item <?php echo $isDsa ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isDsa ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-code-alt"></i>
                    <div class="text-truncate" data-i18n="Partners">DSA Code</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('dsa_code/add') ? 'active' : ''; ?>">
                        <a href="../dsa_code/add" class="menu-link <?php echo isActive('dsa_code/add') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="List"> Add</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('dsa_code/list') ? 'active' : ''; ?>">
                        <a href="../dsa_code/list"
                            class="menu-link <?php echo isActive('dsa_code/list') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="List">List</div>
                        </a>
                    </li>



                </ul>

            </li>



            <!--bankers-->

            <?php $isBankers = isActive('bankers'); ?>
            <li class="menu-item <?php echo $isBankers ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isBankers ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                    <div class="text-truncate" data-i18n="Partners">Bankers</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('bankers/add') ? 'active' : ''; ?>">
                        <a href="../bankers/add" class="menu-link <?php echo isActive('bankers/add') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Add"> Add</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('bankers/list') ? 'active' : ''; ?>">
                        <a href="../bankers/list" class="menu-link <?php echo isActive('bankers/list') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="List">List</div>
                        </a>
                    </li>



                </ul>

            </li>



            <!--upload files-->

            <?php $isUpload = isActive('file-upload'); ?>
            <li class="menu-item <?php echo $isUpload ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isUpload ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div class="text-truncate" data-i18n="Upload Files">Upload Files</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('file-upload/addDocument') ? 'active' : ''; ?>">
                        <a href="../file-upload/addDocument"
                            class="menu-link <?php echo isActive('file-upload/addDocument') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Document Upload"> Document Upload</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('file-upload/addImage') ? 'active' : ''; ?>">
                        <a href="../file-upload/addImage"
                            class="menu-link <?php echo isActive('file-upload/addImage') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Image Upload">Image Upload</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('file-upload/addData') ? 'active' : ''; ?>">
                        <a href="../file-upload/addData"
                            class="menu-link <?php echo isActive('file-upload/addData') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Data Upload">Data Upload</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!--Vehicals-->

            <?php $isVehical = isActive('vehical'); ?>
            <li class="menu-item <?php echo $isVehical ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isVehical ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-bus"></i>
                    <div class="text-truncate" data-i18n="Vehicals">Vehicals</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('vehical/addVehicalMake') ? 'active' : ''; ?>">
                        <a href="../vehical/addVehicalMake"
                            class="menu-link <?php echo isActive('vehical/addVehicalMake') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Vehical Make"> Vehical Make</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('vehical/modal') ? 'active' : ''; ?>">
                        <a href="../vehical/modal"
                            class="menu-link <?php echo isActive('vehical/modal') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Vehical Modal"> Vehical Modal</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('vehical/ManufactureYear') ? 'active' : ''; ?>">
                        <a href="../vehical/ManufactureYear"
                            class="menu-link <?php echo isActive('vehical/ManufactureYear') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Image Upload">Manufacture Year</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('vehical/companyName') ? 'active' : ''; ?>">
                        <a href="../vehical/companyName"
                            class="menu-link <?php echo isActive('vehical/companyName') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Data Upload">INS Company Name</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!--Emp Links-->

            <?php
            $isEmpLinks = isActive(['manage-icon', 'data-icon', 'work-icon']);
            ?>
            <li class="menu-item <?php echo $isEmpLinks ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isEmpLinks ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-link"></i>
                    <div class="text-truncate" data-i18n="Emp Links">Emp Links</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('manage-icon') ? 'active' : ''; ?>">
                        <a href="../manage-icon/icons"
                            class="menu-link <?php echo isActive('manage-icon') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Emp Manage Icons"> Emp Manage Icons</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('data-icon') ? 'active' : ''; ?>">
                        <a href="../data-icon/icons" class="menu-link <?php echo isActive('data-icon') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Image Upload">Emp Data Icons</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('work-icon') ? 'active' : ''; ?>">
                        <a href="../work-icon/icons" class="menu-link <?php echo isActive('work-icon') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Emp Work Icons">Emp Work Icons</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!--Payout-->

            <?php
            $isPayout = isActive(['payout/category', 'payout/type', 'payout/add', 'payout/bank']);
            ?>
            <li class="menu-item <?php echo $isPayout ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isPayout ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-money "></i>
                    <div class="text-truncate" data-i18n="Payout">Payout</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('payout/category') ? 'active' : ''; ?>">
                        <a href="../payout/category"
                            class="menu-link <?php echo isActive('payout/category') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Add Category"> Add Category</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('payout/type') ? 'active' : ''; ?>">
                        <a href="../payout/type" class="menu-link <?php echo isActive('payout/type') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Payout Type"> Payout Type</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('payout/add') ? 'active' : ''; ?>">
                        <a href="../payout/add" class="menu-link <?php echo isActive('payout/add') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Add Payout"> Add Payout</div>
                        </a>
                    </li>

                    <!-- <li class="menu-item">

                        <a href="../payout/full" class="menu-link">

                            <div class="text-truncate" data-i18n="Full Payout"> Full Payout</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../payout/service" class="menu-link">

                            <div class="text-truncate" data-i18n=">Service Payout">Service Payout</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../payout/leadBase" class="menu-link">

                            <div class="text-truncate" data-i18n="Lead Base Payout">Lead Base Payout</div>

                        </a>

                    </li> -->

                    <li class="menu-item <?php echo isActive('payout/bank') ? 'active' : ''; ?>">
                        <a href="../payout/bank" class="menu-link <?php echo isActive('payout/bank') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Bank">Bank</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!--Lead Master-->

            <?php
            $isLeadMaster = isActive(['salaried', 'senp', 'sep', 'nri', 'educational', 'database/account_type', 'database/property_type', 'calling', 'source', 'trade-ref', 'app', 'company_type', 'customer_type', 'industry', 'business']);
            ?>
            <li class="menu-item <?php echo $isLeadMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isLeadMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-user-pin"></i>
                    <div class="text-truncate" data-i18n="Lead Master">Lead Master</div>
                </a>



                <ul class="menu-sub">

                    <!--SAL Masters-->

                    <?php $isSal = isActive('salaried'); ?>
                    <li class="menu-item <?php echo $isSal ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isSal ? 'active' : ''; ?>">



                            <div class="text-truncate" data-i18n="SAL Masters">SAL Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item <?php echo isActive('salaried/grossSalary') ? 'active' : ''; ?>">
                                <a href="../salaried/grossSalary"
                                    class="menu-link <?php echo isActive('salaried/grossSalary') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Gross Salary"> Gross Salary</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('salaried/netSalary') ? 'active' : ''; ?>">
                                <a href="../salaried/netSalary"
                                    class="menu-link <?php echo isActive('salaried/netSalary') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Net Salary"> Net Salary</div>
                                </a>
                            </li>



                            <!--Salaried Designation -->

                            <li class="menu-item <?php echo isActive('salaried-designation') ? 'active' : ''; ?>">
                                <a href="../salaried-designation/add"
                                    class="menu-link <?php echo isActive('salaried-designation') ? 'active' : ''; ?>">



                                    <div class="text-truncate" data-i18n="Ref Relation"> Salaried Designation</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('salaried/salary_payment_type') ? 'active' : ''; ?>">
                                <a href="../salaried/salary_payment_type"
                                    class="menu-link <?php echo isActive('salaried/salary_payment_type') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Salary Payment Type"> Salary Payment Type</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('salaried/present_exp') ? 'active' : ''; ?>">
                                <a href="../salaried/present_exp"
                                    class="menu-link <?php echo isActive('salaried/present_exp') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Present Experiance"> Present Experiance</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('salaried/total_exp') ? 'active' : ''; ?>">
                                <a href="../salaried/total_exp"
                                    class="menu-link <?php echo isActive('salaried/total_exp') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Total Experiance"> Total Experiance</div>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!--SENP Masters-->

                    <?php $isSenp = isActive('senp'); ?>
                    <li class="menu-item <?php echo $isSenp ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isSenp ? 'active' : ''; ?>">



                            <div class="text-truncate" data-i18n="SENP Masters">SENP Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item <?php echo isActive('senp/type_industry') ? 'active' : ''; ?>">
                                <a href="../senp/type_industry"
                                    class="menu-link <?php echo isActive('senp/type_industry') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Type of Industry"> Type of Industry</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('senp/type_business') ? 'active' : ''; ?>">
                                <a href="../senp/type_business"
                                    class="menu-link <?php echo isActive('senp/type_business') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Type of Business"> Type of Business</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('senp/vintage_years') ? 'active' : ''; ?>">
                                <a href="../senp/vintage_years"
                                    class="menu-link <?php echo isActive('senp/vintage_years') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Vintage In Years"> Vintage In Years</div>
                                </a>
                            </li>

                            <!--Financial Year -->

                            <li class="menu-item <?php echo isActive('year/addFinancial') ? 'active' : ''; ?>">
                                <a href="../year/addFinancial"
                                    class="menu-link <?php echo isActive('year/addFinancial') ? 'active' : ''; ?>">
                                    <!-- <i class="menu-icon tf-icons bx bx-calendar"></i> -->
                                    <div class="text-truncate" data-i18n="Ref Relation">Financial Year</div>
                                </a>
                            </li>



                            <!--Assessment Year -->

                            <li class="menu-item <?php echo isActive('year/addAssessment') ? 'active' : ''; ?>">
                                <a href="../year/addAssessment"
                                    class="menu-link <?php echo isActive('year/addAssessment') ? 'active' : ''; ?>">
                                    <!-- <i class="menu-icon tf-icons bx bx-calendar-edit"></i> -->
                                    <div class="text-truncate" data-i18n="Ref Relation"> Assessment Year</div>
                                </a>
                            </li>

                            <!--type of rating -->

                            <li class="menu-item <?php echo isActive('rating/addType') ? 'active' : ''; ?>">
                                <a href="../rating/addType"
                                    class="menu-link <?php echo isActive('rating/addType') ? 'active' : ''; ?>">
                                    <!-- <i class="menu-icon tf-icons bx bx-star"></i> -->
                                    <div class="text-truncate" data-i18n="Type of Rating"> Type of Rating
                                    </div>
                                </a>
                            </li>



                            <!--rating-->

                            <li class="menu-item <?php echo isActive('rating/add') ? 'active' : ''; ?>">
                                <a href="../rating/add"
                                    class="menu-link <?php echo isActive('rating/add') ? 'active' : ''; ?>">
                                    <!-- <i class="menu-icon tf-icons bx bxs-star"></i> -->
                                    <div class="text-truncate" data-i18n="Rating">Rating</div>
                                </a>
                            </li>



                            <li class="menu-item <?php echo isActive('senp/branches') ? 'active' : ''; ?>">
                                <a href="../senp/branches"
                                    class="menu-link <?php echo isActive('senp/branches') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Number Of Branches"> Number Of Branches</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('senp/employees') ? 'active' : ''; ?>">
                                <a href="../senp/employees"
                                    class="menu-link <?php echo isActive('senp/employees') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Number Of Employees"> Number Of Employees</div>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!-- SEP Master -->

                    <?php $isSep = isActive('sep'); ?>
                    <li class="menu-item <?php echo $isSep ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isSep ? 'active' : ''; ?>">

                            <div class="text-truncate" data-i18n="SEP Masters">SEP Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item <?php echo isActive('sep/dr_qualification') ? 'active' : ''; ?>">
                                <a href="../sep/dr_qualification"
                                    class="menu-link <?php echo isActive('sep/dr_qualification') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Doctor Qualification"> Doctor Qualification</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('sep/dr_yearOfPass') ? 'active' : ''; ?>">
                                <a href="../sep/dr_yearOfPass"
                                    class="menu-link <?php echo isActive('sep/dr_yearOfPass') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Doctor Year Of Pass"> Doctor Year Of Pass</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('sep/dr_specialisation') ? 'active' : ''; ?>">
                                <a href="../sep/dr_specialisation"
                                    class="menu-link <?php echo isActive('sep/dr_specialisation') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Doctor Specialisation"> Doctor Specialisation
                                    </div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('sep/dr_university') ? 'active' : ''; ?>">
                                <a href="../sep/dr_university"
                                    class="menu-link <?php echo isActive('sep/dr_university') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Doctor University"> Doctor University</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('sep/ca_yearOfPass') ? 'active' : ''; ?>">
                                <a href="../sep/ca_yearOfPass"
                                    class="menu-link <?php echo isActive('sep/ca_yearOfPass') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="CA Year Of Pass"> CA Year Of Pass</div>
                                </a>
                            </li>

                        </ul>

                    </li>

                    <!--NRI Masters-->

                    <?php $isNri = isActive('nri'); ?>
                    <li class="menu-item <?php echo $isNri ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isNri ? 'active' : ''; ?>">

                            <div class="text-truncate" data-i18n="NRI Masters">NRI Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item <?php echo isActive('nri/country') ? 'active' : ''; ?>">
                                <a href="../nri/country"
                                    class="menu-link <?php echo isActive('nri/country') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Country"> Country</div>
                                </a>
                            </li>



                        </ul>

                    </li>

                    <!--Educational Masters-->

                    <?php $isEducational = isActive('educational'); ?>
                    <li class="menu-item <?php echo $isEducational ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);"
                            class="menu-link menu-toggle <?php echo $isEducational ? 'active' : ''; ?>">

                            <div class="text-truncate" data-i18n="Educational Masters">Educational Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item <?php echo isActive('educational/institute') ? 'active' : ''; ?>">
                                <a href="../educational/institute"
                                    class="menu-link <?php echo isActive('educational/institute') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Type Of Institution"> Type Of Institution</div>
                                </a>
                            </li>

                            <li class="menu-item <?php echo isActive('educational/no_students') ? 'active' : ''; ?>">
                                <a href="../educational/no_students"
                                    class="menu-link <?php echo isActive('educational/no_students') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Number Of Students"> Number Of Students</div>
                                </a>
                            </li>

                        </ul>

                    </li>



                    <li class="menu-item <?php echo isActive('database/account_type') ? 'active' : ''; ?>">
                        <a href="../database/account_type"
                            class="menu-link <?php echo isActive('database/account_type') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Account Type"> Account Type</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('database/property_type') ? 'active' : ''; ?>">
                        <a href="../database/property_type"
                            class="menu-link <?php echo isActive('database/property_type') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Account Type"> Property Type</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('calling/addStatus') ? 'active' : ''; ?>">
                        <a href="../calling/addStatus"
                            class="menu-link <?php echo isActive('calling/addStatus') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Calling Status"> Calling Status</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('calling/addSubStatus') ? 'active' : ''; ?>">
                        <a href="../calling/addSubStatus"
                            class="menu-link <?php echo isActive('calling/addSubStatus') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n=">Calling Sub Status">Calling Sub Status</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('calling/addBank') ? 'active' : ''; ?>">
                        <a href="../calling/addBank"
                            class="menu-link <?php echo isActive('calling/addBank') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Calling Bank">Calling Bank</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('calling/type_loan') ? 'active' : ''; ?>">
                        <a href="../calling/type_loan"
                            class="menu-link <?php echo isActive('calling/type_loan') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Calling Type Of Loan">Calling Type Of Loan</div>
                        </a>
                    </li>

                    <!--source-->

                    <li class="menu-item <?php echo isActive('source/add') ? 'active' : ''; ?>">
                        <a href="../source/add" class="menu-link <?php echo isActive('source/add') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-code"></i> -->
                            <div class="text-truncate" data-i18n="Source"> Source</div>
                        </a>
                    </li>

                    <!--trade ref -->

                    <li class="menu-item <?php echo isActive('trade-ref') ? 'active' : ''; ?>">
                        <a href="../trade-ref/add" class="menu-link <?php echo isActive('trade-ref') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-transfer"></i> -->
                            <div class="text-truncate" data-i18n="Ref Relation"> Trade Ref</div>
                        </a>
                    </li>



                    <!--App Status -->

                    <li class="menu-item <?php echo isActive('app/addStatus') ? 'active' : ''; ?>">
                        <a href="../app/addStatus"
                            class="menu-link <?php echo isActive('app/addStatus') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-info-circle"></i> -->
                            <div class="text-truncate" data-i18n="Ref Relation"> App Status
                            </div>
                        </a>
                    </li>



                    <!--App Sub-Status-->

                    <li class="menu-item <?php echo isActive('app/addSubStatus') ? 'active' : ''; ?>">
                        <a href="../app/addSubStatus"
                            class="menu-link <?php echo isActive('app/addSubStatus') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-time-five"></i> -->
                            <div class="text-truncate" data-i18n="Ref Relation"> App Sub-Status</div>
                        </a>
                    </li>



                    <!-- type of company -->

                    <li class="menu-item <?php echo isActive('company_type') ? 'active' : ''; ?>">
                        <a href="../company_type/add"
                            class="menu-link <?php echo isActive('company_type') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-building"></i> -->
                            <div class="text-truncate" data-i18n="Type Of Company">Type Of Company</div>
                        </a>
                    </li>



                    <!-- type of customer -->

                    <li class="menu-item <?php echo isActive('customer_type') ? 'active' : ''; ?>">
                        <a href="../customer_type/add"
                            class="menu-link <?php echo isActive('customer_type') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-user"></i> -->
                            <div class="text-truncate" data-i18n="Type Of Customer">Type Of Customer</div>
                        </a>
                    </li>



                    <!-- type of industry -->

                    <li class="menu-item <?php echo isActive('industry') ? 'active' : ''; ?>">
                        <a href="../industry/add" class="menu-link <?php echo isActive('industry') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-buildings"></i> -->
                            <div class="text-truncate" data-i18n="Type of Industry">Type of Industry</div>
                        </a>
                    </li>



                    <!-- type of business -->

                    <li class="menu-item <?php echo isActive('business') ? 'active' : ''; ?>">
                        <a href="../business/add" class="menu-link <?php echo isActive('business') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Type of Industry">Type of Business</div>
                        </a>
                    </li>



                </ul>

            </li>



            <!-- appointment Master -->

            <?php $isApptMaster = isActive('appt_master'); ?>
            <li class="menu-item <?php echo $isApptMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isApptMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-time"></i>



                    <div class="text-truncate" data-i18n="Appt Master">Appt Master</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('appt_master/bank') ? 'active' : ''; ?>">
                        <a href="../appt_master/bank"
                            class="menu-link <?php echo isActive('appt_master/bank') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Appt Bank">Appt Bank</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('appt_master/product') ? 'active' : ''; ?>">
                        <a href="../appt_master/product"
                            class="menu-link <?php echo isActive('appt_master/product') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Appt Product"> Appt Product</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('appt_master/status') ? 'active' : ''; ?>">
                        <a href="../appt_master/status"
                            class="menu-link <?php echo isActive('appt_master/status') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Appt Status"> Appt Status</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('appt_master/sub_status') ? 'active' : ''; ?>">
                        <a href="../appt_master/sub_status"
                            class="menu-link <?php echo isActive('appt_master/sub_status') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Appt Sub Status"> Appt Sub Status</div>
                        </a>
                    </li>

                </ul>

            </li>

            <!-- appointment Master -->



            <!-- file Master -->

            <?php $isFileMaster = isActive('file_master'); ?>
            <li class="menu-item <?php echo $isFileMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isFileMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div class="text-truncate" data-i18n="File Master">File Master</div>
                </a>



                <ul class="menu-sub">

                    <li class="menu-item <?php echo isActive('file_master/bank') ? 'active' : ''; ?>">
                        <a href="../file_master/bank"
                            class="menu-link <?php echo isActive('file_master/bank') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="File Bank">File Bank</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('file_master/product') ? 'active' : ''; ?>">
                        <a href="../file_master/product"
                            class="menu-link <?php echo isActive('file_master/product') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="File Product"> File Product</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('file_master/addStatus') ? 'active' : ''; ?>">
                        <a href="../file_master/addStatus"
                            class="menu-link <?php echo isActive('file_master/addStatus') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="File Status"> File Status</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('file_master/addSubStatus') ? 'active' : ''; ?>">
                        <a href="../file_master/addSubStatus"
                            class="menu-link <?php echo isActive('file_master/addSubStatus') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="File Sub Status"> File Sub Status</div>
                        </a>
                    </li>

                </ul>

            </li>

            <!-- file Master -->



            <!--Bank Master-->

            <?php
            $isBankMaster = isActive(['bank', 'vendor-bank', 'portfolio/addBank', 'bankers/addDesignation', 'credit_card']);
            ?>
            <li class="menu-item <?php echo $isBankMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isBankMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-building"></i>
                    <div class="text-truncate" data-i18n="Bank Master">Bank Master</div>
                </a>



                <ul class="menu-sub">

                    <!-- bank -->

                    <li class="menu-item <?php echo isActive('bank/add') ? 'active' : ''; ?>">
                        <a href="../bank/add" class="menu-link <?php echo isActive('bank/add') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Banks"> Banks</div>
                        </a>
                    </li>

                    <!-- Vendor Bank -->

                    <li class="menu-item <?php echo isActive('vendor-bank') ? 'active' : ''; ?>">
                        <a href="../vendor-bank/add"
                            class="menu-link <?php echo isActive('vendor-bank') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-buildings"></i> -->
                            <div class="text-truncate" data-i18n="Vendor Bank"> Vendor Bank</div>
                        </a>
                    </li>



                    <!--Portfolio bank -->

                    <li class="menu-item <?php echo isActive('portfolio/addBank') ? 'active' : ''; ?>">
                        <a href="../portfolio/addBank"
                            class="menu-link <?php echo isActive('portfolio/addBank') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-buildings"></i> -->
                            <div class="text-truncate" data-i18n="Portfolio Banks"> Portfolio Banks</div>
                        </a>
                    </li>

                    <!-- bankers designation -->

                    <li class="menu-item <?php echo isActive('bankers/addDesignation') ? 'active' : ''; ?>">
                        <a href="../bankers/addDesignation"
                            class="menu-link <?php echo isActive('bankers/addDesignation') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-id-card"></i> -->
                            <div class="text-truncate" data-i18n="Bankers Designation">Bankers Designation</div>
                        </a>
                    </li>

                    <!-- credit card bank name -->

                    <li class="menu-item <?php echo isActive('credit_card') ? 'active' : ''; ?>">
                        <a href="../credit_card/add"
                            class="menu-link <?php echo isActive('credit_card') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Type of Industry">Credit Card Bank Name</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!--Company Master-->

            <?php
            $isCompanyMaster = isActive(['company-document', 'dsa_name']);
            ?>
            <li class="menu-item <?php echo $isCompanyMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isCompanyMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-buildings"></i>
                    <div class="text-truncate" data-i18n="Company Master">Company Master</div>
                </a>



                <ul class="menu-sub">

                    <!--Company Document-->

                    <li class="menu-item <?php echo isActive('company-document/addCompany') ? 'active' : ''; ?>">
                        <a href="../company-document/addCompany"
                            class="menu-link <?php echo isActive('company-document/addCompany') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-file"></i> -->
                            <div class="text-truncate" data-i18n="Company Name"> Company Name</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo isActive('company-document/add') ? 'active' : ''; ?>">
                        <a href="../company-document/add"
                            class="menu-link <?php echo isActive('company-document/add') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-file"></i> -->
                            <div class="text-truncate" data-i18n="Company Document"> Company Document</div>
                        </a>
                    </li>



                    <!-- DSC Name -->

                    <li class="menu-item <?php echo isActive('dsa_name') ? 'active' : ''; ?>">
                        <a href="../dsa_name/add" class="menu-link <?php echo isActive('dsa_name') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-id-card"></i> -->
                            <div class="text-truncate" data-i18n="DSA Name">DSA Name</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!--Portfolio Master-->

            <?php
            $isPortfolioMaster = isActive(['portfolio/list', 'portfolio/team', 'tenure', 'roi']);
            ?>
            <li class="menu-item <?php echo $isPortfolioMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);"
                    class="menu-link menu-toggle <?php echo $isPortfolioMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div class="text-truncate" data-i18n="Portfolio Master">Portfolio Master</div>
                </a>



                <ul class="menu-sub">

                    <!-- portfolio list -->

                    <li class="menu-item <?php echo isActive('portfolio/list') ? 'active' : ''; ?>">
                        <a href="../portfolio/list"
                            class="menu-link <?php echo isActive('portfolio/list') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-folder"></i> -->
                            <div class="text-truncate" data-i18n="Portfolio List"> Portfolio List</div>
                        </a>
                    </li>

                    <!-- portfolio team -->

                    <li class="menu-item <?php echo isActive('portfolio/team') ? 'active' : ''; ?>">
                        <a href="../portfolio/team"
                            class="menu-link <?php echo isActive('portfolio/team') ? 'active' : ''; ?>">
                            <div class="text-truncate" data-i18n="Portfolio List"> Portfolio Team</div>
                        </a>
                    </li>

                    <!-- tenure -->

                    <li class="menu-item <?php echo isActive('tenure') ? 'active' : ''; ?>">
                        <a href="../tenure/add" class="menu-link <?php echo isActive('tenure') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-time"></i> -->
                            <div class="text-truncate" data-i18n="Tenure">Tenure</div>
                        </a>
                    </li>



                    <!-- tenure -->

                    <li class="menu-item <?php echo isActive('roi') ? 'active' : ''; ?>">
                        <a href="../roi/add" class="menu-link <?php echo isActive('roi') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-line-chart"></i> -->
                            <div class="text-truncate" data-i18n="Tenure">ROI</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!--Location Master-->

            <?php
            $isLocationMaster = isActive(['state', 'location', 'sub-location', 'pincode', 'branch']);
            ?>
            <li class="menu-item <?php echo $isLocationMaster ? 'active open' : ''; ?>">
                <a href="javascript:void(0);"
                    class="menu-link menu-toggle <?php echo $isLocationMaster ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-map"></i>
                    <div class="text-truncate" data-i18n="Location Master">Location Master</div>
                </a>



                <ul class="menu-sub">

                    <!-- state -->

                    <li class="menu-item <?php echo isActive('state/add') ? 'active' : ''; ?>">
                        <a href="../state/add" class="menu-link <?php echo isActive('state/add') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-map-alt"></i> -->
                            <div class="text-truncate" data-i18n="State"> State</div>
                        </a>
                    </li>



                    <!-- location -->

                    <li class="menu-item <?php echo isActive('location/add') ? 'active' : ''; ?>">
                        <a href="../location/add" class="menu-link <?php echo isActive('location/add') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-map"></i> -->
                            <div class="text-truncate" data-i18n="location"> location</div>
                        </a>
                    </li>



                    <!--sub location -->

                    <li class="menu-item <?php echo isActive('sub-location') ? 'active' : ''; ?>">
                        <a href="../sub-location/add"
                            class="menu-link <?php echo isActive('sub-location') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-map-pin"></i> -->
                            <div class="text-truncate" data-i18n="Sub location">Sub location</div>
                        </a>
                    </li>



                    <!--pin code -->

                    <li class="menu-item <?php echo isActive('pincode') ? 'active' : ''; ?>">
                        <a href="../pincode/add" class="menu-link <?php echo isActive('pincode') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-pin"></i> -->
                            <div class="text-truncate" data-i18n="Sub location">Pincode</div>
                        </a>
                    </li>



                    <!-- state -->

                    <li class="menu-item <?php echo isActive('branch/addState') ? 'active' : ''; ?>">
                        <a href="../branch/addState"
                            class="menu-link <?php echo isActive('branch/addState') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-map-alt"></i> -->
                            <div class="text-truncate" data-i18n="Branch State"> Branch State</div>
                        </a>
                    </li>



                    <!-- location -->

                    <li class="menu-item <?php echo isActive('branch/addLocation') ? 'active' : ''; ?>">
                        <a href="../branch/addLocation"
                            class="menu-link <?php echo isActive('branch/addLocation') ? 'active' : ''; ?>">
                            <!-- <i class="menu-icon tf-icons bx bx-map"></i> -->
                            <div class="text-truncate" data-i18n="Branch location">Branch location</div>
                        </a>
                    </li>

                </ul>

            </li>



            <!-- insurance list -->

            <?php $isInsurance = isActive('insurance/list'); ?>
            <li class="menu-item <?php echo $isInsurance ? 'active' : ''; ?>">
                <a href="../insurance/list" class="menu-link <?php echo $isInsurance ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-shield"></i>
                    <div class="text-truncate" data-i18n="Portfolio List"> Insurance List</div>
                </a>
            </li>



            <!-- type of loan -->

            <?php $isLoanType = isActive('loan_type'); ?>
            <li class="menu-item <?php echo $isLoanType ? 'active' : ''; ?>">
                <a href="../loan_type/add" class="menu-link <?php echo $isLoanType ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div class="text-truncate" data-i18n="Type Of Loan">Type Of Loan</div>
                </a>
            </li>



            <!-- policy -->

            <?php $isPolicy = isActive('policy'); ?>
            <li class="menu-item <?php echo $isPolicy ? 'active' : ''; ?>">
                <a href="../policy/add" class="menu-link <?php echo $isPolicy ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-task"></i>
                    <div class="text-truncate" data-i18n="Policy">Policy</div>
                </a>
            </li>

            <?php $isWebsite = isActive('website/kurakulas'); ?>
            <li class="menu-item <?php echo $isWebsite ? 'active' : ''; ?>">
                <a href="../website/kurakulas" class="menu-link <?php echo $isWebsite ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-globe"></i>
                    <div class="text-truncate" data-i18n="Kurakula's Website">Kurakula's Website</div>
                </a>
            </li>

            <?php $isDigitalLogin = isActive('website/DigitalLogin'); ?>
            <li class="menu-item <?php echo $isDigitalLogin ? 'active' : ''; ?>">
                <a href="../website/DigitalLogin" class="menu-link <?php echo $isDigitalLogin ? 'active' : ''; ?>">
                    <i class="menu-icon tf-icons bx bx-log-in"></i>
                    <div class="text-truncate" data-i18n="Digital Login">Digital Login</div>
                </a>
            </li>

        </ul>

        <?php



    } else if ($loggedInUserRank == 'Admin') {

        ?>

            <ul class="menu-inner py-1">

                <div class="app-brand demo ">

                    <a href="../dashboard/admin" class="app-brand-link">

                        <span class="app-brand-logo demo">

                            <img src="<?php echo $comp_logo; ?>" alt="Logo" style="height:50px;">

                    </a>

                    </span>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">

                        <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>

                    </a>

                </div>



                <div class="menu-inner-shadow"></div>



                <!-- Dashboards -->

            <?php $isDashboard = isActive('dashboard/admin'); ?>
                <li class="menu-item <?php echo $isDashboard ? 'active open' : ''; ?>">
                    <a href="../dashboard/admin" class="menu-link <?php echo $isDashboard ? 'active' : ''; ?>">
                        <i class="menu-icon tf-icons bx bx-home-smile"></i>
                        <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
                    </a>
                </li>



                <!-- Users form -->

            <?php $isUsers = isActive('users'); ?>
                <li class="menu-item <?php echo $isUsers ? 'active open' : ''; ?>">
                    <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isUsers ? 'active' : ''; ?>">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div class="text-truncate" data-i18n="Layouts">Users</div>
                    </a>



                    <ul class="menu-sub">

                        <li class="menu-item <?php echo isExactActive('users/add') ? 'active' : ''; ?>">
                            <a href="../users/add" class="menu-link <?php echo isExactActive('users/add') ? 'active' : ''; ?>">
                                <div class="text-truncate" data-i18n="Add">Add</div>
                            </a>
                        </li>

                        <li class="menu-item <?php echo isExactActive('users/list') ? 'active' : ''; ?>">
                            <a href="../users/list"
                                class="menu-link <?php echo isExactActive('users/list') ? 'active' : ''; ?>">
                                <div class="text-truncate" data-i18n="List">Active User List</div>
                            </a>
                        </li>

                        <li class="menu-item <?php echo isExactActive('users/inActive-list') ? 'active' : ''; ?>">
                            <a href="../users/inActive-list"
                                class="menu-link <?php echo isExactActive('users/inActive-list') ? 'active' : ''; ?>">
                                <div class="text-truncate" data-i18n="List">InActive User List</div>
                            </a>
                        </li>

                    </ul>

                </li>



                <!-- links -->

            <?php $isLinks = isActive('links/links'); ?>
                <li class="menu-item <?php echo $isLinks ? 'active' : ''; ?>">
                    <a href="../links/links" class="menu-link <?php echo $isLinks ? 'active' : ''; ?>">
                        <i class="menu-icon tf-icons bx bx-link"></i>
                        <div class="text-truncate" data-i18n="Emp Links">Emp Links</div>
                    </a>
                </li>

                <!-- links -->

            <?php $isWorkLinks = isActive('work-links/links'); ?>
                <li class="menu-item <?php echo $isWorkLinks ? 'active' : ''; ?>">
                    <a href="../work-links/links" class="menu-link <?php echo $isWorkLinks ? 'active' : ''; ?>">
                        <i class="menu-icon tf-icons bx bx-link"></i>
                        <div class="text-truncate" data-i18n="Work Links">Work Links</div>
                    </a>
                </li>



                <!--emp Images-->

            <?php $isEmpImages = isActive('file-upload/emp_image'); ?>
                <li class="menu-item <?php echo $isEmpImages ? 'active' : ''; ?>">
                    <a href="../file-upload/emp_image" class="menu-link <?php echo $isEmpImages ? 'active' : ''; ?>">
                        <i class="menu-icon tf-icons bx bx-image"></i>
                        <div class="text-truncate" data-i18n="Emp Images">Emp Images</div>
                    </a>
                </li>



            <?php $isDigitalLogin = isActive('website/DigitalLogin'); ?>
                <li class="menu-item <?php echo $isDigitalLogin ? 'active' : ''; ?>">
                    <a href="../website/DigitalLogin" class="menu-link <?php echo $isDigitalLogin ? 'active' : ''; ?>">
                        <i class="menu-icon tf-icons bx bx-log-in"></i>
                        <div class="text-truncate" data-i18n="Digital Login">Digital Login</div>
                    </a>
                </li>



            </ul>

        <?php

    } else if ($loggedInUserRank == 'User') {

        ?>

                <ul class="menu-inner py-1">

                    <div class="app-brand demo ">

                        <a href="../dashboard/user" class="app-brand-link">

                            <span class="app-brand-logo demo">

                                <img src="<?php echo $comp_logo; ?>" alt="Logo" style="height:50px;">

                            </span>

                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">

                            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>

                        </a>

                    </div>

                    <div class="menu-inner-shadow"></div>



                    <!-- Dashboards -->

            <?php $isDashboard = isActive('dashboard/user'); ?>
                    <li class="menu-item <?php echo $isDashboard ? 'active open' : ''; ?>">
                        <a href="../dashboard/user" class="menu-link <?php echo $isDashboard ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-home-smile"></i>
                            <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
                        </a>
                    </li>




                    <!-- Attendance -->
                    <li class="menu-item <?php echo isActive('attendance') ? 'active' : ''; ?>">
                        <a href="../attendance/employee-attendance.php"
                            class="menu-link <?php echo isActive('attendance') ? 'active' : ''; ?>">
                            <i class="bx bx-calendar-check me-2"></i>
                            <div class="text-truncate" data-i18n="Attendance">Attendance</div>
                        </a>
                    </li>

                    <!-- My Payslip -->
                    <li class="menu-item <?php echo isActive('payroll/employee-payslip') ? 'active' : ''; ?>">
                        <a href="../payroll/employee-payslip.php"
                            class="menu-link <?php echo isActive('payroll/employee-payslip') ? 'active' : ''; ?>">
                            <i class="bx bx-receipt me-2"></i>
                            <div class="text-truncate" data-i18n="My Payslip">My Payslip</div>
                        </a>
                    </li>

                    <!-- Leave Management -->
                    <li
                        class="menu-item <?php echo (isExactActive('leave/apply.php') || isExactActive('leave/my_leaves.php')) ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);"
                            class="menu-link menu-toggle <?php echo (isExactActive('leave/apply.php') || isExactActive('leave/my_leaves.php')) ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                            <div class="text-truncate" data-i18n="Leave Management">Leave Management</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?php echo isExactActive('leave/apply.php') ? 'active' : ''; ?>">
                                <a href="../leave/apply.php"
                                    class="menu-link <?php echo isExactActive('leave/apply.php') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="Apply Leave">Apply Leave</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo isExactActive('leave/my_leaves.php') ? 'active' : ''; ?>">
                                <a href="../leave/my_leaves.php"
                                    class="menu-link <?php echo isExactActive('leave/my_leaves.php') ? 'active' : ''; ?>">
                                    <div class="text-truncate" data-i18n="My Leaves">My Leaves</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Accounts Department - Payroll Master Access -->
                <?php
                if (isset($user['department_name']) && $user['department_name'] === 'accounts') { ?>
                        <!-- PAYROLL MASTER - ACCOUNTS SECTION -->
                        <!-- PAYROLL MASTER - CORRECTED SECTION -->
                    <?php
                    $isPayrollMaster = isActive(['attendance', 'payroll', 'Statutory_Config']);
                    ?>
                        <li class="menu-item <?php echo $isPayrollMaster ? 'active open' : ''; ?>">
                            <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isPayrollMaster ? 'active' : ''; ?>">
                                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                                <div class="text-truncate" data-i18n="Payroll Master">Payroll Master</div>
                            </a>

                            <ul class="menu-sub">

                                <!-- Attendance -->
                                <li class="menu-item <?php echo isActive('attendance') ? 'active' : ''; ?>">
                                    <a href="../attendance/add.php"
                                        class="menu-link <?php echo isActive('attendance') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Attendance"> Attendance</div>
                                    </a>
                                </li>
                                <!-- Payroll -->
                                <li class="menu-item <?php echo isActive('payroll') ? 'active' : ''; ?>">
                                    <a href="../payroll/add.php" class="menu-link <?php echo isActive('payroll') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Payroll">Payroll</div>
                                    </a>
                                </li>

                                <!-- Statutory -->
                                <li class="menu-item <?php echo isActive('Statutory_Config') ? 'active' : ''; ?>">
                                    <a href="../Statutory_Config/add.php"
                                        class="menu-link <?php echo isActive('Statutory_Config') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Statutory">Statutory</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
            <?php } ?>

<?php $isTeamLead = isset($user['is_teamlead']) && (int) $user['is_teamlead'] === 1; ?>

<?php if ($isTeamLead) { ?>
    <!-- ===== TL ONLY: Leave Approvals Request ===== -->
    <li class="menu-item <?php echo isExactActive('leave/approve.php') ? 'active' : ''; ?>">
        <a href="../leave/approve.php"
            class="menu-link <?php echo isExactActive('leave/approve.php') ? 'active' : ''; ?>">
            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
            <div class="text-truncate" data-i18n="Leave Request">Leave Approvals Request</div>
        </a>
    </li>
<?php } ?>
                <?php
                if (
                    $user['department_name'] === 'Digital Marketing' &&
                    $user['designation_name'] === 'Manager'
                ) { ?>

                <?php $isMedia = isActive('gallery'); ?>
                        <li class="menu-item <?php echo $isMedia ? 'active' : ''; ?>">
                            <a href="../gallery/uploadgallery.php" class="menu-link <?php echo $isMedia ? 'active' : ''; ?>">
                                <i class="menu-icon tf-icons bx bx-home"></i>
                                <div class="text-truncate">Media section</div>
                            </a>
                        </li>

            <?php } ?>



                    <!-- emp info -->

            <?php $isEmpInfo = isActive('users/list'); ?>
                    <li class="menu-item <?php echo $isEmpInfo ? 'active' : ''; ?>">
                        <a href="../users/list" class="menu-link <?php echo $isEmpInfo ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div class="text-truncate" data-i18n="Emp Info">Emp Info</div>
                        </a>
                    </li>



                    <!-- links -->

            <?php $isLinks = isActive('links/links') && !isActive('work-links') && !isActive('data-links'); ?>
                    <li class="menu-item <?php echo $isLinks ? 'active' : ''; ?>">
                        <a href="../links/links" class="menu-link <?php echo $isLinks ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-link"></i>
                            <div class="text-truncate" data-i18n="Emp Links">Emp Links</div>
                        </a>
                    </li>



                    <!-- links -->

            <?php $isDataLinks = isActive('data-links/links'); ?>
                    <li class="menu-item <?php echo $isDataLinks ? 'active' : ''; ?>">
                        <a href="../data-links/links" class="menu-link <?php echo $isDataLinks ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-link"></i>
                            <div class="text-truncate" data-i18n="Data Links">Data Links</div>
                        </a>
                    </li>



                    <!-- links -->

            <?php $isWorkLinks = isActive('work-links/links'); ?>
                    <li class="menu-item <?php echo $isWorkLinks ? 'active' : ''; ?>">
                        <a href="../work-links/links" class="menu-link <?php echo $isWorkLinks ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-link"></i>
                            <div class="text-truncate" data-i18n="Work Links">Work Links</div>
                        </a>
                    </li>



                <?php

                if (

                    ($user['department_name'] === 'Management' &&

                        in_array($user['designation_name'], ['Managing Director', 'Director']))

                    ||

                    ($user['department_name'] === 'Accounts' &&

                        in_array($user['designation_name'], ['Accounts Manager', 'Accountant']))

                ) { ?>

                        <!-- links -->

                <?php $isAccountLinks = isActive('account-icon/myLinks'); ?>
                        <li class="menu-item <?php echo $isAccountLinks ? 'active' : ''; ?>">
                            <a href="../account-icon/myLinks" class="menu-link <?php echo $isAccountLinks ? 'active' : ''; ?>">
                                <i class="menu-icon tf-icons bx bx-link"></i>
                                <div class="text-truncate" data-i18n="Account Links">Account Links</div>
                            </a>
                        </li>

            <?php } ?>



                    <!-- payout -->

                <?php

                if (

                    ($user['department_name'] === 'Management' &&

                        in_array($user['designation_name'], ['Managing Director', 'Director']))

                    ||

                    ($user['department_name'] === 'Marketing' &&

                        in_array($user['designation_name'], ['Regional Business Head', 'Business Head', 'Manager']))

                    ||

                    ($user['department_name'] === 'Accounts' &&

                        in_array($user['designation_name'], ['Accounts Manager', 'Accountant']))

                ) {

                    ?>

                <?php $isPayout = isActive('payout/add'); ?>
                        <li class="menu-item <?php echo $isPayout ? 'active' : ''; ?>">
                            <a href="../payout/add" class="menu-link <?php echo $isPayout ? 'active' : ''; ?>">
                                <i class="menu-icon tf-icons bx bx-money"></i>
                                <div class="text-truncate" data-i18n="Payout">Payout</div>
                            </a>
                        </li>

                <?php

                }

                ?>


                    <!-- payout -->

                    <!-- database  -->

            <?php $isDatabase = isActive('database/database'); ?>
                    <li class="menu-item <?php echo $isDatabase ? 'active' : ''; ?>">
                        <a href="../database/database" class="menu-link <?php echo $isDatabase ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-data"></i>
                            <div class="text-truncate" data-i18n="DataBase">DataBase</div>
                        </a>
                    </li>


                    </ <!-- appointment -->

            <?php $isAppointment = isActive('appointment/appointment'); ?>
                    <li class="menu-item <?php echo $isAppointment ? 'active' : ''; ?>">
                        <a href="../appointment/appointment" class="menu-link <?php echo $isAppointment ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                            <div class="text-truncate" data-i18n="Appointment">Appointment</div>
                        </a>
                    </li>



                    <!-- Partner -->

            <?php $isPartner = isActive('partner/partner'); ?>
                    <li class="menu-item <?php echo $isPartner ? 'active' : ''; ?>">
                        <a href="../partner/partner" class="menu-link <?php echo $isPartner ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-group"></i>
                            <div class="text-truncate" data-i18n="Layouts">Partner</div>
                        </a>
                    </li>



                    <!-- connectors -->

            <?php $isConnectors = isActive('connectors/connectors'); ?>
                    <li class="menu-item <?php echo $isConnectors ? 'active' : ''; ?>">
                        <a href="../connectors/connectors" class="menu-link <?php echo $isConnectors ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-network-chart"></i>
                            <div class="text-truncate" data-i18n="Connectors">Connectors</div>
                        </a>
                    </li>



                    <!-- agent -->

            <?php $isAgent = isActive('agent-data/agent'); ?>
                    <li class="menu-item <?php echo $isAgent ? 'active' : ''; ?>">
                        <a href="../agent-data/agent" class="menu-link <?php echo $isAgent ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div class="text-truncate" data-i18n="Connectors">Agent</div>
                        </a>
                    </li>



                    <!-- portfolio -->

            <?php $isPortfolio = isActive('portfolio/portfolio'); ?>
                    <li class="menu-item <?php echo $isPortfolio ? 'active' : ''; ?>">
                        <a href="../portfolio/portfolio" class="menu-link <?php echo $isPortfolio ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-folder"></i>
                            <div class="text-truncate" data-i18n="Portfolio">Portfolio</div>
                        </a>
                    </li>

                    <!--dsa code & bankers -->

                <?php

                if (
                    $user['department_name'] === 'Accounts' &&

                    in_array($user['designation_name'], ['Accounts Manager', 'Accountant'])
                ) {



                    ?>

                        <!--dsa code-->

                <?php $isDsa = isActive('dsa_code'); ?>
                        <li class="menu-item <?php echo $isDsa ? 'active open' : ''; ?>">
                            <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isDsa ? 'active' : ''; ?>">
                                <i class="menu-icon tf-icons bx bx-code-alt"></i>
                                <div class="text-truncate" data-i18n="Partners">DSA Code</div>
                            </a>



                            <ul class="menu-sub">

                                <li class="menu-item <?php echo isActive('dsa_code/add') ? 'active' : ''; ?>">
                                    <a href="../dsa_code/add" class="menu-link <?php echo isActive('dsa_code/add') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="List"> Add</div>
                                    </a>
                                </li>

                                <li class="menu-item <?php echo isActive('dsa_code/list') ? 'active' : ''; ?>">
                                    <a href="../dsa_code/list"
                                        class="menu-link <?php echo isActive('dsa_code/list') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="List">List</div>
                                    </a>
                                </li>

                            </ul>

                        </li>



                        <!--bankers-->

                <?php $isBankers = isActive('bankers'); ?>
                        <li class="menu-item <?php echo $isBankers ? 'active open' : ''; ?>">
                            <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isBankers ? 'active' : ''; ?>">
                                <i class="menu-icon tf-icons bx bx-wallet"></i>
                                <div class="text-truncate" data-i18n="Partners">Bankers</div>
                            </a>



                            <ul class="menu-sub">

                                <li class="menu-item <?php echo isActive('bankers/add') ? 'active' : ''; ?>">
                                    <a href="../bankers/add" class="menu-link <?php echo isActive('bankers/add') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Add"> Add</div>
                                    </a>
                                </li>

                                <li class="menu-item <?php echo isActive('bankers/list') ? 'active' : ''; ?>">
                                    <a href="../bankers/list" class="menu-link <?php echo isActive('bankers/list') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="List">List</div>
                                    </a>
                                </li>



                            </ul>

                        </li>

                <?php

                } else if (
                    ($user['department_name'] === 'Management' &&

                        in_array($user['designation_name'], ['Managing Director', 'Director']))

                    ||

                    ($user['department_name'] === 'Marketing' &&

                        in_array($user['designation_name'], ['Regional Business Head', 'Business Head']))

                ) {

                    ?>

                <?php $isBankers = isActive('bankers'); ?>
                            <li class="menu-item <?php echo $isBankers ? 'active open' : ''; ?>">
                                <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isBankers ? 'active' : ''; ?>">
                                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                                    <div class="text-truncate" data-i18n="Partners">Bankers</div>
                                </a>



                                <ul class="menu-sub">

                                    <li class="menu-item <?php echo isActive('bankers/add') ? 'active' : ''; ?>">
                                        <a href="../bankers/add" class="menu-link <?php echo isActive('bankers/add') ? 'active' : ''; ?>">
                                            <div class="text-truncate" data-i18n="Add"> Add</div>
                                        </a>
                                    </li>

                                    <li class="menu-item <?php echo isActive('bankers/list') ? 'active' : ''; ?>">
                                        <a href="../bankers/list" class="menu-link <?php echo isActive('bankers/list') ? 'active' : ''; ?>">
                                            <div class="text-truncate" data-i18n="List">List</div>
                                        </a>
                                    </li>



                                </ul>

                            </li>

                <?php

                } else {

                    ?>

                            <!--dsa code-->

                <?php $isDsa = isActive('dsa_code/list'); ?>
                            <li class="menu-item <?php echo $isDsa ? 'active' : ''; ?>">
                                <a href="../dsa_code/list" class="menu-link <?php echo $isDsa ? 'active' : ''; ?>">
                                    <i class="menu-icon tf-icons bx bx-code-alt"></i>
                                    <div class="text-truncate" data-i18n="DSA Code">DSA Code</div>
                                </a>
                            </li>

            <?php } ?>

                <?php

                if (
                    ($user['department_name'] === 'Management' &&

                        in_array($user['designation_name'], ['Managing Director', 'Director']))

                    ||

                    ($user['department_name'] === 'Accounts' &&

                        in_array($user['designation_name'], ['Accounts Manager', 'Accountant']))

                ) {

                    ?>

                        <!-- accounts / finance -->

                    <?php
                    $isAccounts = isActive(['bank_statement', 'invoice', 'payout/bank', 'company-document', 'bank_account']);
                    ?>
                        <li class="menu-item <?php echo $isAccounts ? 'active open' : ''; ?>">
                            <a href="javascript:void(0);" class="menu-link menu-toggle <?php echo $isAccounts ? 'active' : ''; ?>">
                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                <div class="text-truncate" data-i18n="Accounts / Finance">Accounts / Finance</div>
                            </a>



                            <ul class="menu-sub">

                                <li class="menu-item <?php echo isActive('bank_statement') ? 'active' : ''; ?>">
                                    <a href="../bank_statement/add"
                                        class="menu-link <?php echo isActive('bank_statement') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Bank Statements">Bank Statements</div>
                                    </a>
                                </li>



                                <li class="menu-item <?php echo isActive('invoice') ? 'active' : ''; ?>">
                                    <a href="../invoice/add" class="menu-link <?php echo isActive('invoice') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Invoice">Invoice</div>
                                    </a>
                                </li>



                                <!--Bank Payout-->

                                <li class="menu-item <?php echo isActive('payout/bank') ? 'active' : ''; ?>">
                                    <a href="../payout/bank" class="menu-link <?php echo isActive('payout/bank') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Bank Payout"> Bank Payout</div>
                                    </a>
                                </li>



                                <!--Company Document-->

                                <li class="menu-item <?php echo isActive('company-document/add') ? 'active' : ''; ?>">
                                    <a href="../company-document/add"
                                        class="menu-link <?php echo isActive('company-document/add') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Company Document"> Company Document</div>
                                    </a>
                                </li>

                                <li class="menu-item <?php echo isActive('bank_account') ? 'active' : ''; ?>">
                                    <a href="../bank_account/add"
                                        class="menu-link <?php echo isActive('bank_account') ? 'active' : ''; ?>">
                                        <div class="text-truncate" data-i18n="Invoice">Bank Account Details</div>
                                    </a>
                                </li>

                            </ul>

                        </li>

                <?php

                }

                ?>



                    <!--emp documents-->

            <?php $isEmpDocuments = isActive('file-upload/emp_documents'); ?>
                    <li class="menu-item <?php echo $isEmpDocuments ? 'active' : ''; ?>">
                        <a href="../file-upload/emp_documents" class="menu-link <?php echo $isEmpDocuments ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div class="text-truncate" data-i18n="Emp Documents">Emp Documents</div>
                        </a>
                    </li>



                    <!--emp Images-->

            <?php $isEmpImages = isActive('file-upload/emp_image'); ?>
                    <li class="menu-item <?php echo $isEmpImages ? 'active' : ''; ?>">
                        <a href="../file-upload/emp_image" class="menu-link <?php echo $isEmpImages ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-image"></i>
                            <div class="text-truncate" data-i18n="Emp Images">Emp Images</div>
                        </a>
                    </li>



                    <!--emp data-->

            <?php $isEmpData = isActive('file-upload/emp_data'); ?>
                    <li class="menu-item <?php echo $isEmpData ? 'active' : ''; ?>">
                        <a href="../file-upload/emp_data" class="menu-link <?php echo $isEmpData ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-data"></i>
                            <div class="text-truncate" data-i18n="Emp Data">Emp Data</div>
                        </a>
                    </li>



                    <!--Insurance-->

            <?php $isInsurance = isActive('insurance/insurance'); ?>
                    <li class="menu-item <?php echo $isInsurance ? 'active' : ''; ?>">
                        <a href="../insurance/insurance" class="menu-link <?php echo $isInsurance ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-shield"></i>
                            <div class="text-truncate" data-i18n="Insurance"> Vehical Insurance</div>
                        </a>
                    </li>



                    <!-- policy -->

            <?php $isPolicy = isActive('policy/add'); ?>
                    <li class="menu-item <?php echo $isPolicy ? 'active' : ''; ?>">
                        <a href="../policy/add" class="menu-link <?php echo $isPolicy ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-task"></i>
                            <div class="text-truncate" data-i18n="Policy">Policy</div>
                        </a>
                    </li>

            <?php $isDigitalLogin = isActive('website/DigitalLogin'); ?>
                    <li class="menu-item <?php echo $isDigitalLogin ? 'active' : ''; ?>">
                        <a href="../website/DigitalLogin" class="menu-link <?php echo $isDigitalLogin ? 'active' : ''; ?>">
                            <i class="menu-icon tf-icons bx bx-log-in"></i>
                            <div class="text-truncate" data-i18n="Digital Login">Digital Login</div>
                        </a>
                    </li>

                </ul>

        <?php

    }

    ?>



</aside>

<!-- / Menu -->
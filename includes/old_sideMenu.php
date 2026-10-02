<?php

$sql = "

    SELECT 

        u.id,

        u.rank,

        u.department_id,

        u.designation_id,

        dept.department_name,

        d.designation_name

    FROM tbl_user u

    JOIN tbl_department dept ON u.department_id = dept.id

    JOIN tbl_designation d ON u.designation_id = d.id

    WHERE u.username = '$loggedInUser'

    LIMIT 1

";



$res = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($res);



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

            <li class="menu-item active open">

                <a href="../dashboard/superAdmin" class="menu-link ">

                    <i class="menu-icon tf-icons bx bx-home-smile"></i>

                    <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>

                </a>

            </li>



            <!-- emp master -->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-crown"></i>

                    <div class="text-truncate" data-i18n="Emp Master">Emp Master</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../users/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Add Users">Add Users</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../users/list" class="menu-link">

                            <div class="text-truncate" data-i18n="Active User List">Active User List</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../users/inActive-list" class="menu-link">

                            <div class="text-truncate" data-i18n="InActive User List">InActive User List</div>

                        </a>

                    </li>



                    <!-- Department -->

                    <li class="menu-item">

                        <a href="../department/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Emp Department">Emp Department</div>

                        </a>

                    </li>



                    <!-- designation -->

                    <li class="menu-item">

                        <a href="../designation/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Emp Designation">Emp Designation</div>

                        </a>

                    </li>



                    <!-- relation -->

                    <li class="menu-item">

                        <a href="../family/relation" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-group"></i> -->

                            <div class="text-truncate" data-i18n="Family Relation"> Family Relation</div>

                        </a>

                    </li>



                    <!--ref relation -->

                    <li class="menu-item">

                        <a href="../ref-relation/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-group"></i> -->

                            <div class="text-truncate" data-i18n="Ref Relation"> Ref Relation</div>

                        </a>

                    </li>



                </ul>

            </li>

            <!-- payrole master-->

            <!-- PAYROLL MASTER - CORRECTED SECTION -->
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-crown"></i>
                    <div class="text-truncate" data-i18n="Payroll Master">Payroll Master</div>
                </a>

                <ul class="menu-sub">
                    <!-- Employees -->
                    <li class="menu-item">
                        <a href="../users/list.php" class="menu-link">
                            <div class="text-truncate" data-i18n="Employees">Employees</div>
                        </a>
                    </li>
                    <!-- Attendance -->
                    <li class="menu-item">
                        <a href="../attendance/add.php" class="menu-link">
                            <div class="text-truncate" data-i18n="Attendance"> Attendance</div>
                        </a>
                    </li>
                    <!-- Payroll -->
                    <li class="menu-item">
                        <a href="../payroll/add.php" class="menu-link">
                            <div class="text-truncate" data-i18n="Payroll">Payroll</div>
                        </a>
                    </li>
                    
                    <!-- Statutory -->
                    <li class="menu-item">
                        <a href="../Statutory_Config/add.php" class="menu-link">
                            <div class="text-truncate" data-i18n="Statutory">Statutory</div>
                        </a>
                    </li>
                </ul>
            </li>


            <!-- accounts / finance master -->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>

                    <div class="text-truncate" data-i18n="Accounts / Finance">Accounts / Finance</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../account_bank/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Account Bank">Account Bank</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../account_dsa/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Account DSA">Account DSA</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../bank_statement/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Bank Adjustment">Bank Adjustment</div>

                        </a>

                    </li>



                    <li class="menu-item">

                        <a href="../invoice/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Invoice">Invoice</div>

                        </a>

                    </li>

                    <li class="menu-item">
                        <a href="../invoice_payment/add" class="menu-link">
                            <div class="text-truncate" data-i18n="Invoice">Invoice Payment Status</div>
                        </a>
                    </li>


                </ul>

            </li>



            <!-- database  -->

            <li class="menu-item">

                <a href="../database/database" class="menu-link">

                    <i class="menu-icon tf-icons bx bx-data"></i>

                    <div class="text-truncate" data-i18n=" DataBase">DataBase</div>

                </a>

            </li>



            <li class="menu-item">
                <a href="../gallery/uploadgallery.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div class="text-truncate" data-i18n="Training">Media Section</div>
                </a>
            </li>



            <!-- appointment -->

            <li class="menu-item">

                <a href="../appointment/appointment" class="menu-link">

                    <i class="menu-icon tf-icons bx bx-calendar-check"></i>

                    <div class="text-truncate" data-i18n="Appointment">Appointment</div>

                </a>

            </li>



            <!-- partner master -->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-group"></i>

                    <div class="text-truncate" data-i18n="Partner Master">Partner Master</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../partner_type/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Type Of Partner">Type Of Partner</div>

                        </a>

                    </li>

                    <!-- <li class="menu-item">

                        <a href="../partner/partner" class="menu-link">

                            <div class="text-truncate" data-i18n="Partner"> Partner</div>

                        </a>

                    </li> -->

                    <li class="menu-item">

                        <a href="../partner_master/status" class="menu-link">

                            <div class="text-truncate" data-i18n="Status"> Status</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../partner_master/sub_status" class="menu-link">

                            <div class="text-truncate" data-i18n="Sub Status"> Sub Status</div>

                        </a>

                    </li>

                </ul>

            </li>

            <!-- partner master -->



            <!-- partner list -->

            <li class="menu-item ">

                <a href="../partner/partner" class="menu-link ">

                    <i class="menu-icon tf-icons bx bx-link-alt"></i>

                    <div class="text-truncate" data-i18n="Partner">Partner</div>

                </a>

            </li>

            <!-- partner list -->



            <!-- connectors list-->

            <li class="menu-item ">

                <a href="../connectors/connectors" class="menu-link ">

                    <i class="menu-icon tf-icons bx bx-network-chart"></i>

                    <div class="text-truncate" data-i18n="Connectors">Connectors </div>

                </a>

            </li>

            <!-- connectors list-->



            <!-- agent list-->

            <li class="menu-item ">

                <a href="../agent-data/agent" class="menu-link ">

                    <i class="menu-icon tf-icons bx bx-user"></i>

                    <div class="text-truncate" data-i18n="Agent">Agent</div>

                </a>

            </li>

            <!-- agent list-->



            <!--dsa code-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-code-alt"></i>

                    <div class="text-truncate" data-i18n="Partners">DSA Code</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../dsa_code/add" class="menu-link">

                            <div class="text-truncate" data-i18n="List"> Add</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../dsa_code/list" class="menu-link">

                            <div class="text-truncate" data-i18n="List">List</div>

                        </a>

                    </li>



                </ul>

            </li>



            <!--bankers-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-wallet"></i>

                    <div class="text-truncate" data-i18n="Partners">Bankers</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../bankers/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Add"> Add</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../bankers/list" class="menu-link">

                            <div class="text-truncate" data-i18n="List">List</div>

                        </a>

                    </li>



                </ul>

            </li>



            <!--upload files-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-file"></i>

                    <div class="text-truncate" data-i18n="Upload Files">Upload Files</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../file-upload/addDocument" class="menu-link">

                            <div class="text-truncate" data-i18n="Document Upload"> Document Upload</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../file-upload/addImage" class="menu-link">

                            <div class="text-truncate" data-i18n="Image Upload">Image Upload</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../file-upload/addData" class="menu-link">

                            <div class="text-truncate" data-i18n="Data Upload">Data Upload</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!--Vehicals-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-bus"></i>

                    <div class="text-truncate" data-i18n="Vehicals">Vehicals</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../vehical/addVehicalMake" class="menu-link">

                            <div class="text-truncate" data-i18n="Vehical Make"> Vehical Make</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../vehical/modal" class="menu-link">

                            <div class="text-truncate" data-i18n="Vehical Modal"> Vehical Modal</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../vehical/ManufactureYear" class="menu-link">

                            <div class="text-truncate" data-i18n="Image Upload">Manufacture Year</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../vehical/companyName" class="menu-link">

                            <div class="text-truncate" data-i18n="Data Upload">INS Company Name</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!--Emp Links-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-link"></i>

                    <div class="text-truncate" data-i18n="Emp Links">Emp Links</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../manage-icon/icons" class="menu-link">

                            <div class="text-truncate" data-i18n="Emp Manage Icons"> Emp Manage Icons</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../data-icon/icons" class="menu-link">

                            <div class="text-truncate" data-i18n="Image Upload">Emp Data Icons</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../work-icon/icons" class="menu-link">

                            <div class="text-truncate" data-i18n="Emp Work Icons">Emp Work Icons</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!--Payout-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-money "></i>

                    <div class="text-truncate" data-i18n="Payout">Payout</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../payout/category" class="menu-link">

                            <div class="text-truncate" data-i18n="Add Category"> Add Category</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../payout/type" class="menu-link">

                            <div class="text-truncate" data-i18n="Payout Type"> Payout Type</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../payout/add" class="menu-link">

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

                    <li class="menu-item">

                        <a href="../payout/bank" class="menu-link">

                            <div class="text-truncate" data-i18n="Bank">Bank</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!--Lead Master-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-user-pin"></i>

                    <div class="text-truncate" data-i18n="Lead Master">Lead Master</div>

                </a>



                <ul class="menu-sub">

                    <!--SAL Masters-->

                    <li class="menu-item">

                        <a href="javascript:void(0);" class="menu-link menu-toggle">



                            <div class="text-truncate" data-i18n="SAL Masters">SAL Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item">

                                <a href="../salaried/grossSalary" class="menu-link">

                                    <div class="text-truncate" data-i18n="Gross Salary"> Gross Salary</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../salaried/netSalary" class="menu-link">

                                    <div class="text-truncate" data-i18n="Net Salary"> Net Salary</div>

                                </a>

                            </li>



                            <!--Salaried Designation -->

                            <li class="menu-item">

                                <a href="../salaried-designation/add" class="menu-link">



                                    <div class="text-truncate" data-i18n="Ref Relation"> Salaried Designation</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../salaried/salary_payment_type" class="menu-link">

                                    <div class="text-truncate" data-i18n="Salary Payment Type"> Salary Payment Type</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../salaried/present_exp" class="menu-link">

                                    <div class="text-truncate" data-i18n="Present Experiance"> Present Experiance</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../salaried/total_exp" class="menu-link">

                                    <div class="text-truncate" data-i18n="Total Experiance"> Total Experiance</div>

                                </a>

                            </li>

                        </ul>

                    </li>

                    <!--SENP Masters-->

                    <li class="menu-item">

                        <a href="javascript:void(0);" class="menu-link menu-toggle">



                            <div class="text-truncate" data-i18n="SENP Masters">SENP Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item">

                                <a href="../senp/type_industry" class="menu-link">

                                    <div class="text-truncate" data-i18n="Type of Industry"> Type of Industry</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../senp/type_business" class="menu-link">

                                    <div class="text-truncate" data-i18n="Type of Business"> Type of Business</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../senp/vintage_years" class="menu-link">

                                    <div class="text-truncate" data-i18n="Vintage In Years"> Vintage In Years</div>

                                </a>

                            </li>

                            <!--Financial Year -->

                            <li class="menu-item">

                                <a href="../year/addFinancial" class="menu-link">

                                    <!-- <i class="menu-icon tf-icons bx bx-calendar"></i> -->

                                    <div class="text-truncate" data-i18n="Ref Relation">Financial Year</div>

                                </a>

                            </li>



                            <!--Assessment Year -->

                            <li class="menu-item">

                                <a href="../year/addAssessment" class="menu-link">

                                    <!-- <i class="menu-icon tf-icons bx bx-calendar-edit"></i> -->

                                    <div class="text-truncate" data-i18n="Ref Relation"> Assessment Year</div>

                                </a>

                            </li>

                            <!--type of rating -->

                            <li class="menu-item">

                                <a href="../rating/addType" class="menu-link">

                                    <!-- <i class="menu-icon tf-icons bx bx-star"></i> -->

                                    <div class="text-truncate" data-i18n="Type of Rating"> Type of Rating



                                    </div>

                                </a>

                            </li>



                            <!--rating-->

                            <li class="menu-item">

                                <a href="../rating/add" class="menu-link">

                                    <!-- <i class="menu-icon tf-icons bx bxs-star"></i> -->

                                    <div class="text-truncate" data-i18n="Rating">Rating</div>

                                </a>

                            </li>



                            <li class="menu-item">

                                <a href="../senp/branches" class="menu-link">

                                    <div class="text-truncate" data-i18n="Number Of Branches"> Number Of Branches</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../senp/employees" class="menu-link">

                                    <div class="text-truncate" data-i18n="Number Of Employees"> Number Of Employees</div>

                                </a>

                            </li>

                        </ul>

                    </li>

                    <!-- SEP Master -->

                    <li class="menu-item">

                        <a href="javascript:void(0);" class="menu-link menu-toggle">

                            <div class="text-truncate" data-i18n="SEP Masters">SEP Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item">

                                <a href="../sep/dr_qualification" class="menu-link">

                                    <div class="text-truncate" data-i18n="Doctor Qualification"> Doctor Qualification</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../sep/dr_yearOfPass" class="menu-link">

                                    <div class="text-truncate" data-i18n="Doctor Year Of Pass"> Doctor Year Of Pass</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../sep/dr_specialisation" class="menu-link">

                                    <div class="text-truncate" data-i18n="Doctor Specialisation"> Doctor Specialisation

                                    </div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../sep/dr_university" class="menu-link">

                                    <div class="text-truncate" data-i18n="Doctor University"> Doctor University</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../sep/ca_yearOfPass" class="menu-link">

                                    <div class="text-truncate" data-i18n="CA Year Of Pass"> CA Year Of Pass</div>

                                </a>

                            </li>

                        </ul>

                    </li>

                    <!--NRI Masters-->

                    <li class="menu-item">

                        <a href="javascript:void(0);" class="menu-link menu-toggle">

                            <div class="text-truncate" data-i18n="NRI Masters">NRI Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item">

                                <a href="../nri/country" class="menu-link">

                                    <div class="text-truncate" data-i18n="Country"> Country</div>

                                </a>

                            </li>



                        </ul>

                    </li>

                    <!--Educational Masters-->

                    <li class="menu-item">

                        <a href="javascript:void(0);" class="menu-link menu-toggle">

                            <div class="text-truncate" data-i18n="Educational Masters">Educational Masters</div>

                        </a>



                        <ul class="menu-sub">

                            <li class="menu-item">

                                <a href="../educational/institute" class="menu-link">

                                    <div class="text-truncate" data-i18n="Type Of Institution"> Type Of Institution</div>

                                </a>

                            </li>

                            <li class="menu-item">

                                <a href="../educational/no_students" class="menu-link">

                                    <div class="text-truncate" data-i18n="Number Of Students"> Number Of Students</div>

                                </a>

                            </li>

                        </ul>

                    </li>



                    <li class="menu-item">

                        <a href="../database/account_type" class="menu-link">

                            <div class="text-truncate" data-i18n="Account Type"> Account Type</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../database/property_type" class="menu-link">

                            <div class="text-truncate" data-i18n="Account Type"> Property Type</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../calling/addStatus" class="menu-link">

                            <div class="text-truncate" data-i18n="Calling Status"> Calling Status</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../calling/addSubStatus" class="menu-link">

                            <div class="text-truncate" data-i18n=">Calling Sub Status">Calling Sub Status</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../calling/addBank" class="menu-link">

                            <div class="text-truncate" data-i18n="Calling Bank">Calling Bank</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../calling/type_loan" class="menu-link">

                            <div class="text-truncate" data-i18n="Calling Type Of Loan">Calling Type Of Loan</div>

                        </a>

                    </li>

                    <!--source-->

                    <li class="menu-item">

                        <a href="../source/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-code"></i> -->

                            <div class="text-truncate" data-i18n="Source"> Source</div>

                        </a>

                    </li>

                    <!--trade ref -->

                    <li class="menu-item">

                        <a href="../trade-ref/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-transfer"></i> -->

                            <div class="text-truncate" data-i18n="Ref Relation"> Trade Ref</div>

                        </a>

                    </li>



                    <!--App Status -->

                    <li class="menu-item">

                        <a href="../app/addStatus" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-info-circle"></i> -->

                            <div class="text-truncate" data-i18n="Ref Relation"> App Status

                            </div>

                        </a>

                    </li>



                    <!--App Sub-Status-->

                    <li class="menu-item">

                        <a href="../app/addSubStatus" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-time-five"></i> -->

                            <div class="text-truncate" data-i18n="Ref Relation"> App Sub-Status</div>

                        </a>

                    </li>



                    <!-- type of company -->

                    <li class="menu-item">

                        <a href="../company_type/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-building"></i> -->

                            <div class="text-truncate" data-i18n="Type Of Company">Type Of Company</div>

                        </a>

                    </li>



                    <!-- type of customer -->

                    <li class="menu-item">

                        <a href="../customer_type/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-user"></i> -->

                            <div class="text-truncate" data-i18n="Type Of Customer">Type Of Customer</div>

                        </a>

                    </li>



                    <!-- type of industry -->

                    <li class="menu-item">

                        <a href="../industry/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-buildings"></i> -->

                            <div class="text-truncate" data-i18n="Type of Industry">Type of Industry</div>

                        </a>

                    </li>



                    <!-- type of business -->

                    <li class="menu-item">

                        <a href="../business/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Type of Industry">Type of Business</div>

                        </a>

                    </li>





                </ul>

            </li>



            <!-- appointment Master -->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-time"></i>



                    <div class="text-truncate" data-i18n="Appt Master">Appt Master</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../appt_master/bank" class="menu-link">

                            <div class="text-truncate" data-i18n="Appt Bank">Appt Bank</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../appt_master/product" class="menu-link">

                            <div class="text-truncate" data-i18n="Appt Product"> Appt Product</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../appt_master/status" class="menu-link">

                            <div class="text-truncate" data-i18n="Appt Status"> Appt Status</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../appt_master/sub_status" class="menu-link">

                            <div class="text-truncate" data-i18n="Appt Sub Status"> Appt Sub Status</div>

                        </a>

                    </li>

                </ul>

            </li>

            <!-- appointment Master -->



            <!-- file Master -->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-file"></i>

                    <div class="text-truncate" data-i18n="File Master">File Master</div>

                </a>



                <ul class="menu-sub">

                    <li class="menu-item">

                        <a href="../file_master/bank" class="menu-link">

                            <div class="text-truncate" data-i18n="File Bank">File Bank</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../file_master/product" class="menu-link">

                            <div class="text-truncate" data-i18n="File Product"> File Product</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../file_master/addStatus" class="menu-link">

                            <div class="text-truncate" data-i18n="File Status"> File Status</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../file_master/addSubStatus" class="menu-link">

                            <div class="text-truncate" data-i18n="File Sub Status"> File Sub Status</div>

                        </a>

                    </li>

                </ul>

            </li>

            <!-- file Master -->



            <!--Bank Master-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-building"></i>

                    <div class="text-truncate" data-i18n="Bank Master">Bank Master</div>

                </a>



                <ul class="menu-sub">

                    <!-- bank -->

                    <li class="menu-item">

                        <a href="../bank/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Banks"> Banks</div>

                        </a>

                    </li>

                    <!-- Vendor Bank -->

                    <li class="menu-item">

                        <a href="../vendor-bank/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-buildings"></i> -->

                            <div class="text-truncate" data-i18n="Vendor Bank"> Vendor Bank</div>

                        </a>

                    </li>



                    <!--Portfolio bank -->

                    <li class="menu-item">

                        <a href="../portfolio/addBank" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-buildings"></i> -->

                            <div class="text-truncate" data-i18n="Portfolio Banks"> Portfolio Banks</div>

                        </a>

                    </li>

                    <!-- bankers designation -->

                    <li class="menu-item">

                        <a href="../bankers/addDesignation" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-id-card"></i> -->

                            <div class="text-truncate" data-i18n="Bankers Designation">Bankers Designation</div>

                        </a>

                    </li>

                    <!-- credit card bank name -->

                    <li class="menu-item">

                        <a href="../credit_card/add" class="menu-link">

                            <div class="text-truncate" data-i18n="Type of Industry">Credit Card Bank Name</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!--Company Master-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-buildings"></i>

                    <div class="text-truncate" data-i18n="Company Master">Company Master</div>

                </a>



                <ul class="menu-sub">

                    <!--Company Document-->

                    <li class="menu-item">

                        <a href="../company-document/addCompany" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-file"></i> -->

                            <div class="text-truncate" data-i18n="Company Name"> Company Name</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../company-document/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-file"></i> -->

                            <div class="text-truncate" data-i18n="Company Document"> Company Document</div>

                        </a>

                    </li>



                    <!-- DSC Name -->

                    <li class="menu-item">

                        <a href="../dsa_name/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-id-card"></i> -->

                            <div class="text-truncate" data-i18n="DSA Name">DSA Name</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!--Portfolio Master-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-folder"></i>

                    <div class="text-truncate" data-i18n="Portfolio Master">Portfolio Master</div>

                </a>



                <ul class="menu-sub">

                    <!-- portfolio list -->

                    <li class="menu-item">

                        <a href="../portfolio/list" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-folder"></i> -->

                            <div class="text-truncate" data-i18n="Portfolio List"> Portfolio List</div>

                        </a>

                    </li>

                    <!-- portfolio team -->

                    <li class="menu-item">

                        <a href="../portfolio/team" class="menu-link">

                            <div class="text-truncate" data-i18n="Portfolio List"> Portfolio Team</div>

                        </a>

                    </li>

                    <!-- tenure -->

                    <li class="menu-item">

                        <a href="../tenure/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-time"></i> -->

                            <div class="text-truncate" data-i18n="Tenure">Tenure</div>

                        </a>

                    </li>



                    <!-- tenure -->

                    <li class="menu-item">

                        <a href="../roi/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-line-chart"></i> -->

                            <div class="text-truncate" data-i18n="Tenure">ROI</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!--Location Master-->

            <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">

                    <i class="menu-icon tf-icons bx bx-map"></i>

                    <div class="text-truncate" data-i18n="Location Master">Location Master</div>

                </a>



                <ul class="menu-sub">

                    <!-- state -->

                    <li class="menu-item">

                        <a href="../state/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-map-alt"></i> -->

                            <div class="text-truncate" data-i18n="State"> State</div>

                        </a>

                    </li>



                    <!-- location -->

                    <li class="menu-item">

                        <a href="../location/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-map"></i> -->

                            <div class="text-truncate" data-i18n="location"> location</div>

                        </a>

                    </li>



                    <!--sub location -->

                    <li class="menu-item">

                        <a href="../sub-location/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-map-pin"></i> -->

                            <div class="text-truncate" data-i18n="Sub location">Sub location</div>

                        </a>

                    </li>



                    <!--pin code -->

                    <li class="menu-item">

                        <a href="../pincode/add" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-pin"></i> -->

                            <div class="text-truncate" data-i18n="Sub location">Pincode</div>

                        </a>

                    </li>



                    <!-- state -->

                    <li class="menu-item">

                        <a href="../branch/addState" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-map-alt"></i> -->

                            <div class="text-truncate" data-i18n="Branch State"> Branch State</div>

                        </a>

                    </li>



                    <!-- location -->

                    <li class="menu-item">

                        <a href="../branch/addLocation" class="menu-link">

                            <!-- <i class="menu-icon tf-icons bx bx-map"></i> -->

                            <div class="text-truncate" data-i18n="Branch location">Branch location</div>

                        </a>

                    </li>

                </ul>

            </li>



            <!-- insurance list -->

            <li class="menu-item">

                <a href="../insurance/list" class="menu-link">

                    <i class="menu-icon tf-icons bx bx-shield"></i>

                    <div class="text-truncate" data-i18n="Portfolio List"> Insurance List</div>

                </a>

            </li>



            <!-- type of loan -->

            <li class="menu-item">

                <a href="../loan_type/add" class="menu-link">

                    <i class="menu-icon tf-icons bx bx-file"></i>

                    <div class="text-truncate" data-i18n="Type Of Loan">Type Of Loan</div>

                </a>

            </li>



            <!-- policy -->

            <li class="menu-item">

                <a href="../policy/add" class="menu-link">

                    <i class="menu-icon tf-icons bx bx-task"></i>

                    <div class="text-truncate" data-i18n="Policy">Policy</div>

                </a>

            </li>

            <li class="menu-item">

                <a href="../website/kurakulas" class="menu-link">

                    <i class="menu-icon tf-icons bx bx-globe"></i>

                    <div class="text-truncate" data-i18n="Kurakula's Website">Kurakula's Website</div>

                </a>

            </li>

            <li class="menu-item">

                <a href="../website/DigitalLogin" class="menu-link">

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

                <li class="menu-item active open">

                    <a href="../dashboard/admin" class="menu-link ">

                        <i class="menu-icon tf-icons bx bx-home-smile"></i>

                        <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>

                    </a>

                </li>



                <!-- Users form -->

                <li class="menu-item">

                    <a href="javascript:void(0);" class="menu-link menu-toggle">

                        <i class="menu-icon tf-icons bx bx-layout"></i>

                        <div class="text-truncate" data-i18n="Layouts">Users</div>

                    </a>



                    <ul class="menu-sub">

                        <li class="menu-item">

                            <a href="../users/add" class="menu-link">

                                <div class="text-truncate" data-i18n="Add">Add</div>

                            </a>

                        </li>

                        <li class="menu-item">

                            <a href="../users/list" class="menu-link">

                                <div class="text-truncate" data-i18n="List">Active User List</div>

                            </a>

                        </li>

                        <li class="menu-item">

                            <a href="../users/inActive-list" class="menu-link">

                                <div class="text-truncate" data-i18n="List">InActive User List</div>

                            </a>

                        </li>

                    </ul>

                </li>



                <!-- links -->

                <li class="menu-item">

                    <a href="../links/links" class="menu-link">

                        <i class="menu-icon tf-icons bx bx-link"></i>

                        <div class="text-truncate" data-i18n="Emp Links">Emp Links</div>

                    </a>

                </li>

                <!-- links -->

                <li class="menu-item">

                    <a href="../work-links/links" class="menu-link">

                        <i class="menu-icon tf-icons bx bx-link"></i>

                        <div class="text-truncate" data-i18n="Work Links">Work Links</div>

                    </a>

                </li>



                <!--emp Images-->

                <li class="menu-item">

                    <a href="../file-upload/emp_image" class="menu-link">

                        <i class="menu-icon tf-icons bx bx-image"></i>

                        <div class="text-truncate" data-i18n="Emp Images">Emp Images</div>

                    </a>

                </li>



                <li class="menu-item">

                    <a href="../website/DigitalLogin" class="menu-link">

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

                    <li class="menu-item active open">

                        <a href="../dashboard/user" class="menu-link ">

                            <i class="menu-icon tf-icons bx bx-home-smile"></i>

                            <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>

                        </a>

                    </li>


                    <!-- Digital Marketing Manager access to media section -->
                <?php
                if (
                    $user['department_name'] === 'Digital Marketing' &&
                    $user['designation_name'] === 'Manager'
                ) { ?>
                        <!-- Dashboard -->
                        <li class="menu-item">
                            <a href="../gallery/uploadgallery.php" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home"></i>
                                <div class="text-truncate">Media section</div>
                            </a>
                        </li>
            <?php } ?>



                    <!-- emp info -->

                    <li class="menu-item">

                        <a href="../users/list" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-user"></i>

                            <div class="text-truncate" data-i18n="Emp Info">Emp Info</div>

                        </a>

                    </li>



                    <!-- links -->

                    <li class="menu-item">

                        <a href="../links/links" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-link"></i>

                            <div class="text-truncate" data-i18n="Emp Links">Emp Links</div>

                        </a>

                    </li>



                    <!-- links -->

                    <li class="menu-item">

                        <a href="../data-links/links" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-link"></i>

                            <div class="text-truncate" data-i18n="Data Links">Data Links</div>

                        </a>

                    </li>



                    <!-- links -->

                    <li class="menu-item">

                        <a href="../work-links/links" class="menu-link">

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

                        <li class="menu-item">

                            <a href="../account-icon/myLinks" class="menu-link">

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



                        <li class="menu-item">

                            <a href="../payout/add" class="menu-link">

                                <i class="menu-icon tf-icons bx bx-money"></i>

                                <div class="text-truncate" data-i18n="Payout">Payout</div>

                            </a>

                        </li>

                <?php

                }

                ?>

                    <!-- payout -->

                    <!-- database  -->

                    <li class="menu-item">

                        <a href="../database/database" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-data"></i>

                            <div class="text-truncate" data-i18n=" DataBase">DataBase</div>

                        </a>

                    </li>



                    <!-- appointment -->

                    <li class="menu-item">

                        <a href="../appointment/appointment" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-calendar-check"></i>

                            <div class="text-truncate" data-i18n="Appointment">Appointment</div>

                        </a>

                    </li>



                    <!-- Partner -->

                    <li class="menu-item">

                        <a href="../partner/partner" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-group"></i>

                            <div class="text-truncate" data-i18n="Layouts">Partner</div>

                        </a>

                    </li>



                    <!-- connectors -->

                    <li class="menu-item ">

                        <a href="../connectors/connectors" class="menu-link ">

                            <i class="menu-icon tf-icons bx bx-network-chart"></i>

                            <div class="text-truncate" data-i18n="Connectors">Connectors</div>

                        </a>

                    </li>



                    <!-- agent -->

                    <li class="menu-item ">

                        <a href="../agent-data/agent" class="menu-link ">

                            <i class="menu-icon tf-icons bx bx-user"></i>

                            <div class="text-truncate" data-i18n="Connectors">Agent</div>

                        </a>

                    </li>



                    <!-- portfolio -->

                    <li class="menu-item">

                        <a href="../portfolio/portfolio" class="menu-link">

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

                        <li class="menu-item">

                            <a href="javascript:void(0);" class="menu-link menu-toggle">

                                <i class="menu-icon tf-icons bx bx-code-alt"></i>

                                <div class="text-truncate" data-i18n="Partners">DSA Code</div>

                            </a>



                            <ul class="menu-sub">

                                <li class="menu-item">

                                    <a href="../dsa_code/add" class="menu-link">

                                        <div class="text-truncate" data-i18n="List"> Add</div>

                                    </a>

                                </li>

                                <li class="menu-item">

                                    <a href="../dsa_code/list" class="menu-link">

                                        <div class="text-truncate" data-i18n="List">List</div>

                                    </a>

                                </li>

                            </ul>

                        </li>



                        <!--bankers-->

                        <li class="menu-item">

                            <a href="javascript:void(0);" class="menu-link menu-toggle">

                                <i class="menu-icon tf-icons bx bx-wallet"></i>

                                <div class="text-truncate" data-i18n="Partners">Bankers</div>

                            </a>



                            <ul class="menu-sub">

                                <li class="menu-item">

                                    <a href="../bankers/add" class="menu-link">

                                        <div class="text-truncate" data-i18n="Add"> Add</div>

                                    </a>

                                </li>

                                <li class="menu-item">

                                    <a href="../bankers/list" class="menu-link">

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

                            <li class="menu-item">

                                <a href="javascript:void(0);" class="menu-link menu-toggle">

                                    <i class="menu-icon tf-icons bx bx-wallet"></i>

                                    <div class="text-truncate" data-i18n="Partners">Bankers</div>

                                </a>



                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="../bankers/add" class="menu-link">

                                            <div class="text-truncate" data-i18n="Add"> Add</div>

                                        </a>

                                    </li>

                                    <li class="menu-item">

                                        <a href="../bankers/list" class="menu-link">

                                            <div class="text-truncate" data-i18n="List">List</div>

                                        </a>

                                    </li>



                                </ul>

                            </li>

                <?php

                } else {

                    ?>

                            <!--dsa code-->

                            <li class="menu-item">

                                <a href="../dsa_code/list" class="menu-link">

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

                        <li class="menu-item">

                            <a href="javascript:void(0);" class="menu-link menu-toggle">

                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>

                                <div class="text-truncate" data-i18n="Accounts / Finance">Accounts / Finance</div>

                            </a>



                            <ul class="menu-sub">

                                <li class="menu-item">

                                    <a href="../bank_statement/add" class="menu-link">

                                        <div class="text-truncate" data-i18n="Bank Statements">Bank Statements</div>

                                    </a>

                                </li>



                                <li class="menu-item">

                                    <a href="../invoice/add" class="menu-link">

                                        <div class="text-truncate" data-i18n="Invoice">Invoice</div>

                                    </a>

                                </li>



                                <!--Bank Payout-->

                                <li class="menu-item">

                                    <a href="../payout/bank" class="menu-link">

                                        <div class="text-truncate" data-i18n="Bank Payout"> Bank Payout</div>

                                    </a>

                                </li>



                                <!--Company Document-->

                                <li class="menu-item">

                                    <a href="../company-document/add" class="menu-link">

                                        <div class="text-truncate" data-i18n="Company Document"> Company Document</div>

                                    </a>

                                </li>

                                <li class="menu-item">
                                    <a href="../bank_account/add" class="menu-link">
                                        <div class="text-truncate" data-i18n="Invoice">Bank Account Details</div>
                                    </a>
                                </li>

                            </ul>

                        </li>

                <?php

                }

                ?>



                    <!--emp documents-->

                    <li class="menu-item">

                        <a href="../file-upload/emp_documents" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-file"></i>

                            <div class="text-truncate" data-i18n="Emp Documents">Emp Documents</div>

                        </a>

                    </li>



                    <!--emp Images-->

                    <li class="menu-item">

                        <a href="../file-upload/emp_image" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-image"></i>

                            <div class="text-truncate" data-i18n="Emp Images">Emp Images</div>

                        </a>

                    </li>



                    <!--emp data-->

                    <li class="menu-item">

                        <a href="../file-upload/emp_data" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-data"></i>

                            <div class="text-truncate" data-i18n="Emp Data">Emp Data</div>

                        </a>

                    </li>



                    <!--Insurance-->

                    <li class="menu-item">

                        <a href="../insurance/insurance" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-shield"></i>

                            <div class="text-truncate" data-i18n="Insurance"> Vehical Insurance</div>

                        </a>

                    </li>



                    <!-- policy -->

                    <li class="menu-item">

                        <a href="../policy/add" class="menu-link">

                            <i class="menu-icon tf-icons bx bx-task"></i>

                            <div class="text-truncate" data-i18n="Policy">Policy</div>

                        </a>

                    </li>

                    <li class="menu-item">

                        <a href="../website/DigitalLogin" class="menu-link">

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
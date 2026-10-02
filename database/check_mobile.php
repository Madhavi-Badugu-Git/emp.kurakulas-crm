<?php
include('../includes/dbConfig.php');

$mobile = $_POST['mobile'] ?? '';

if (!empty($mobile)) {
    $query = "SELECT d.id, d.mobile_number, d.lead_name, d.email_id, 
                     d.company_name, d.alternative_mobile, 
                     s.state_name AS state, l.location,
                     s_l.sub_location, p.pincode AS pin_code, 
                     s_r.source, d.visiting_card, c_t.customer_type AS customer_type_name, d.customer_type,
                     d.user_qualification, d.database_notes, d.residental_address, 
                     n.country AS nri_country, d.nri_gross_salary, e_i.institute AS educational_institute, e_s.no_students AS educational_students, d.office_address, d.branch_address, e_c.company_type AS edu_company_type, d.educational_strength, sal_c.company_type AS sal_company_type, d.sal_birth_date, sal_gr.grossSalary AS sal_grossSalary, d.gross_sal_amount, sal_nt.netSalary AS sal_netSalary, d.net_sal_amount, sal_desig.designation_name AS sal_designation_name, d.sal_official_email, sal_pay.salary_payment_type AS sal_salary_payment_type, sal_pre.present_experience AS sal_present_experience, sal_tot.total_experience AS sal_total_experience, senp_in.industry_name AS senp_industry_name, senp_bu.business_name AS senp_business_name, senp_co.company_type AS senp_company_type, d.senp_nature_business, d.senp_incorporaton_date, senp_vi.vintage_year AS senp_vintage_year, d.senp_factory_address, d.senp_factory_pincode, d.senp_gst_number, d.senp_company_pan_number, d.senp_website, senp_rt.rating_type AS senp_rating_type, senp_rn.rating_name AS senp_rating_name, senp_br.branches AS senp_branches, senp_em.employees AS senp_employees
              FROM tbl_database d
              LEFT JOIN tbl_state s ON d.state = s.id
              LEFT JOIN tbl_location l ON d.location = l.id
              LEFT JOIN tbl_sub_location s_l ON d.sub_location = s_l.id
              LEFT JOIN tbl_pincode p ON d.pin_code = p.id
              LEFT JOIN tbl_data_source s_r ON d.source = s_r.id
              LEFT JOIN tbl_customer_type c_t ON d.customer_type = c_t.id
              LEFT JOIN tbl_nri_country n ON d.nri_country = n.id
              LEFT JOIN tbl_educational_institute e_i ON d.educational_institute = e_i.id
              LEFT JOIN tbl_educational_no_students e_s ON d.educational_students = e_s.id
              LEFT JOIN tbl_company_type e_c ON d.edu_company_type = e_c.id
              LEFT JOIN tbl_company_type sal_c ON d.sal_company_type = sal_c.id
              LEFT JOIN tbl_senp_grosssalary sal_gr ON d.sal_grossSalary = sal_gr.id
              LEFT JOIN tbl_senp_netsalary sal_nt ON d.sal_netSalary = sal_nt.id
              LEFT JOIN tbl_salaried_designation sal_desig ON d.sal_designation_name = sal_desig.id
              LEFT JOIN tbl_salary_payment_type sal_pay ON d.sal_salary_payment_type = sal_pay.id
              LEFT JOIN tbl_present_experience sal_pre ON d.sal_present_experience = sal_pre.id
              LEFT JOIN tbl_total_experience sal_tot ON d.sal_total_experience = sal_tot.id
              LEFT JOIN tbl_senp_industry_type senp_in ON d.senp_industry_name = senp_in.id
              LEFT JOIN tbl_senp_business_type senp_bu ON d.senp_business_name = senp_bu.id
              LEFT JOIN tbl_company_type senp_co ON d.senp_company_type = senp_co.id
              LEFT JOIN tbl_vintage_year senp_vi ON d.senp_vintage_year = senp_vi.id
              LEFT JOIN tbl_type_rating senp_rt ON d.senp_rating_type = senp_rt.id
              LEFT JOIN tbl_rating senp_rn ON d.senp_rating_name = senp_rn.id
              LEFT JOIN tbl_senp_branches senp_br ON d.senp_branches = senp_br.id
              LEFT JOIN tbl_senp_employees senp_em ON d.senp_employees = senp_em.id


              WHERE d.mobile_number = ? AND d.status = '1'";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = [];
    while ($row = $result->fetch_assoc()) {
        $output[] = $row;
    }

    echo json_encode($output);
}
?>

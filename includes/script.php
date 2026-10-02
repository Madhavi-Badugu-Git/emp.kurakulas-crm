 <!-- Core JS -->
 <!-- build:js assets/vendor/js/core.js -->

 <script src="../assets/vendor/libs/jquery/jquery.js"></script>
 <script src="../assets/vendor/libs/popper/popper.js"></script>
 <script src="../assets/vendor/js/bootstrap.js"></script>
 <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
 <script src="../assets/vendor/js/menu.js"></script>

 <!-- endbuild -->

 <!-- Vendors JS -->
 <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

 <!-- Main JS -->
 <script src="../assets/js/main.js"></script>


 <!-- Page JS -->
 <script src="../assets/js/dashboards-analytics.js"></script>

 <!-- Place this tag before closing body tag for github widget button. -->
 <script async defer src="../../../buttons.github.io/buttons.js"></script>

 <!-- Include jQuery (Required) -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include iziToast CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
 <?php
  if(isset($_GET['error'])){
      ?>
      <script>
      $("document").ready(function () {
      iziToast.warning({
      title: "warning",
      message: "<?= $_GET['error'];?>",
      position: "topRight",
      });
      });
      </script>
      <?php
  }
  else if(isset($_GET['success'])){
      ?>
      <script>
      $("document").ready(function () {
      iziToast.success({
      title: "Success",
      message: "<?= $_GET['success'];?>",
      position: "topRight",
      });
      });
      </script>
      <?php
  }
 ?>
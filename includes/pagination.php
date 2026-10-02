 <?php if ($totalPages > 1): ?>
 <nav aria-label="Page navigation">
     <ul class="pagination justify-content-center mt-4">
         <!-- Prev Button -->
         <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
             <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">Prev</a>
         </li>

         <?php
            $start = max(1, $page - 4);
            $end = min($totalPages, $page + 5);

            if ($start > 1) {
                echo '<li class="page-item"><a class="page-link" href="?'. http_build_query(array_merge($_GET, ['page' => 1])) .'">1</a></li>';
                if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }

            for ($i = $start; $i <= $end; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo '<li class="page-item ' . $active . '">
                        <a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $i])) . '">' . $i . '</a>
                    </li>';
            }

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                echo '<li class="page-item"><a class="page-link" href="?'. http_build_query(array_merge($_GET, ['page' => $totalPages])) .'">' . $totalPages . '</a></li>';
            }
        ?>

         <!-- Next Button -->
         <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
             <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next</a>
         </li>
     </ul>
 </nav>
 <?php endif; ?>
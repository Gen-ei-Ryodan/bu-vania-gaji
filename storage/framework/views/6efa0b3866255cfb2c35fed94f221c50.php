<?php if($paginator->hasPages()): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination pagination-sm justify-content-center">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="bi bi-chevron-left"></i> Sebelumnya
                    </span>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">
                        <i class="bi bi-chevron-left"></i> Sebelumnya
                    </a>
                </li>
            <?php endif; ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">
                        Selanjutnya <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        Selanjutnya <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/vendor/pagination/simple-bootstrap-5.blade.php ENDPATH**/ ?>
<!-- Breadcrumb NavXT -->
<?php if (function_exists('bcn_display_list')): ?>
    <nav typeof="BreadcrumbList" vocab="https://schema.org/" aria-label="breadcrumb">
        <ol class="breadcrumbs">
            <?php bcn_display_list(); ?>
        </ol>
    </nav>
<?php endif; ?>
<!-- /Breadcrumb NavXT -->
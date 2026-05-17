

<?php $__env->startSection('content'); ?>


    <article class="bg-white border rounded-xl p-8">
        <h1 class="text-4xl font-bold mb-3">
            <?php echo e($blog->title); ?>

        </h1>

        <p class="text-gray-500 mb-8">
            Published <?php echo e($blog->published_at->format('M d, Y')); ?>

        </p>

        <?php if($blog->excerpt): ?>
            <p class="text-xl text-gray-700 mb-8">
                <?php echo e($blog->excerpt); ?>

            </p>
        <?php endif; ?>

        <div class="prose max-w-none whitespace-pre-line">
            <?php echo e($blog->content); ?>

        </div>
    </article>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/blogs/show.blade.php ENDPATH**/ ?>
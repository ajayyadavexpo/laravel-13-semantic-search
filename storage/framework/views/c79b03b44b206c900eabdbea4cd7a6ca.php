<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($title ?? 'AI Blog'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">

    <nav class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between">
            <a href="<?php echo e(route('blogs.index')); ?>" class="font-bold text-xl">
                AI Blog
            </a>

            <a href="<?php echo e(route('blogs.create')); ?>"
               class="bg-black text-white px-4 py-2 rounded-lg">
                Write Blog
            </a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">

        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>

    </main>

</body>
</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>
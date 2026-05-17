

<?php $__env->startSection('content'); ?>


    <h1 class="text-3xl font-bold mb-6">Write Blog Post</h1>

    <form method="POST" action="<?php echo e(route('blogs.store')); ?>" class="bg-white border rounded-xl p-6 space-y-5">
        <?php echo csrf_field(); ?>

        <div>
            <label class="font-medium">Title</label>
            <input
                name="title"
                value="<?php echo e(old('title')); ?>"
                class="w-full border rounded-lg px-4 py-3 mt-1"
                required
            >
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="font-medium">Excerpt</label>
            <textarea
                name="excerpt"
                rows="3"
                class="w-full border rounded-lg px-4 py-3 mt-1"
            ><?php echo e(old('excerpt')); ?></textarea>
            <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="font-medium">Content</label>
            <textarea
                name="content"
                rows="12"
                class="w-full border rounded-lg px-4 py-3 mt-1"
                required
            ><?php echo e(old('content')); ?></textarea>
            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <button class="bg-black text-white px-6 py-3 rounded-lg">
            Publish with Embedding
        </button>
    </form>

    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/blogs/create.blade.php ENDPATH**/ ?>
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // المستخدم اللي قدّم البلاغ
            $table->foreignId('reporter_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Polymorphic (شو تم الإبلاغ عنه)
            $table->morphs('reportable');
            // => reportable_id + reportable_type

            // تفاصيل البلاغ
            $table->string('reason');          // spam, abuse, fake, etc
            $table->text('description')->nullable();

            // حالة البلاغ
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])
                ->default('pending');

            // أدمن
            $table->foreignId('reviewed_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            // منع التكرار
            $table->unique(
                ['reporter_id', 'reportable_id', 'reportable_type'],
                'unique_report_per_target'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

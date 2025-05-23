<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('activity_document_path')->nullable()->after('description');
            $table->string('reviewer_attachment_path')->nullable()->after('activity_document_path');
            $table->string('google_docs_url')->nullable()->after('reviewer_attachment_path');
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'activity_document_path',
                'reviewer_attachment_path',
                'google_docs_url'
            ]);
        });
    }
}; 
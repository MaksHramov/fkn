<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('users',fn(Blueprint $t)=>$t->string('login')->nullable()->unique());
  Schema::table('orders',function(Blueprint $t){$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->string('city')->nullable();});
 }
 public function down(): void {
  Schema::table('orders',fn(Blueprint $t)=>$t->dropConstrainedForeignId('user_id'));
  Schema::table('orders',fn(Blueprint $t)=>$t->dropColumn('city'));
  Schema::table('users',fn(Blueprint $t)=>$t->dropColumn('login'));
 }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
 Schema::create('products',function(Blueprint $t){$t->id();$t->string('name');$t->string('category');$t->text('description');$t->integer('price');$t->string('image');$t->timestamps();});
 Schema::create('orders',function(Blueprint $t){$t->id();$t->uuid('reference')->unique();$t->string('name');$t->string('email');$t->text('address');$t->integer('total');$t->json('items');$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('orders');Schema::dropIfExists('products');}
};

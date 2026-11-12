<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder {
 public function run(): void {
 $items=[['Вазы «Ветка»','Керамика',2400,'vases.webp','Пара светлых ваз для цветущих веток и небольших букетов.'],['Стул «Линия»','Мебель',18900,'chair.webp','Лаконичный стул для рабочего стола или обеденной зоны.'],['Светильник «Тихий вечер»','Свет',7900,'lamp.webp','Мягкий свет для чтения и неспешных вечеров дома.'],['Кресло «Пауза»','Мебель',32900,'armchair.webp','Уютное место для любимой книги и чашки чая.'],['Диван «Форма»','Мебель',36900,'sofa.webp','Компактный диван в тёплом оттенке для гостиной.'],['Часы «Круг»','Декор',4500,'clock.webp','Настенные часы с деревянным циферблатом.']];
 foreach($items as $i=>$p) DB::table('products')->updateOrInsert(['id'=>$i+1],['name'=>$p[0],'category'=>$p[1],'price'=>$p[2],'image'=>$p[3],'description'=>$p[4],'created_at'=>now(),'updated_at'=>now()]);
 }
}

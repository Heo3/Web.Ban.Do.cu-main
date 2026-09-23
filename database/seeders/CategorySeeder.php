<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        Category::insert([
            [
                'name' => 'Điện thoại',
                'slug' => 'dien-thoai',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/5010.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tủ lạnh',
                'slug' => 'tu-lanh',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/9030.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'NOXH',
                'slug' => 'noxh',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/1001.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bất động sản',
                'slug' => 'bat-dong-san',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/1000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Đồ dùng văn phòng',
                'slug' => 'do-dung-van-phong',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/8000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Phương tiện',
                'slug' => 'phuong-tien',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/2000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mẹ và bé',
                'slug' => 'me-va-be',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/11000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thực phẩm',
                'slug' => 'thuc-pham',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/7000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thú cưng',
                'slug' => 'thu-cung',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/12000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Điện tử',
                'slug' => 'dien-tu',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/5000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thời trang',
                'slug' => 'thoi-trang',
                'image' => 'https://static.chotot.com/storage/chapy-pro/newcats/v12/3000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Nội thất',
                'slug' => 'noi-that',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/14000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Giải trí',
                'slug' => 'giai-tri',
                'image' => 'https://static.chotot.com/storage/chapy-pro/newcats/v12/3000.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cho tặng',
                'slug' => 'cho-tang',
                'image' => 'https://static.chotot.com/storage/chotot-img/personalized-categories/v1/giveaway.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
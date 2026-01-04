<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role (Spatie)
        $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        $roleManager = Role::create(['name' => 'manager']);
        $roleCashier = Role::create(['name' => 'cashier']);
        $roleCustomer = Role::create(['name' => 'customer']);

        // 2. Buat Akun User untuk Login Nanti (Password: password)
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@resto.com',
            'password' => Hash::make('password'),
            'phone_number' => '081234567890',
            'address' => 'Jl. Admin Pusat'
        ]);
        $admin->assignRole($roleSuperAdmin);

        $manager = User::create([
            'name' => 'Pak Manager',
            'email' => 'manager@resto.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole($roleManager);

        $kasir = User::create([
            'name' => 'Si Kasir',
            'email' => 'kasir@resto.com',
            'password' => Hash::make('password'),
        ]);
        $kasir->assignRole($roleCashier);

        // Buat 5 Customer Dummy
        User::factory(5)->create()->each(function ($user) use ($roleCustomer) {
            $user->assignRole($roleCustomer);
        });

        // 3. Buat Kategori Makanan
        $categories = [
            ['name' => 'Makanan Berat', 'slug' => 'makanan-berat'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
            ['name' => 'Cemilan', 'slug' => 'cemilan'],
            ['name' => 'Dessert', 'slug' => 'dessert'],
            ['name' => 'Paket Hemat', 'slug' => 'paket-hemat'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // 4. Buat Produk (Total 20 Produk)
        // Kita pakai Factory manual loop biar terkontrol
        $kategori_ids = Category::all()->pluck('id');

        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'category_id' => $kategori_ids->random(),
                'name' => 'Menu Lezat ' . $i,
                'description' => 'Deskripsi makanan enak nomor ' . $i,
                'price' => rand(15000, 100000), // Harga acak 15rb - 100rb
                'stock' => rand(10, 50),
                'image' => null,
                'is_favorite' => rand(0, 1)
            ]);
        }

        // 5. Buat Meja (10 Meja)
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'table_number' => 'M-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => rand(2, 6),
                'location' => $i <= 5 ? 'indoor' : 'outdoor',
                'status' => 'available'
            ]);
        }
        
        // Output info di terminal
        $this->command->info('Data Dummy Berhasil Dibuat! Login: admin@resto.com / password');
    }
}
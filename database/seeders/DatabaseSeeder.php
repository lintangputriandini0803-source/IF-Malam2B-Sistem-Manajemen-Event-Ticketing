<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EventCategory;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name'     => 'Admin SIMETIX',
            'nim'      => '000000001',
            'email'    => 'admin@simetix.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'approved',
        ]);

        // Panitia contoh
        $panitia = User::create([
            'name'         => 'Panitia Teknovasi',
            'nim'          => '4342301001',
            'email'        => 'panitia@simetix.com',
            'password'     => Hash::make('password'),
            'role'         => 'panitia',
            'status'       => 'approved',
            'organization' => 'BEM Politeknik Negeri Batam',
            'reason'       => 'Ingin mengadakan Pagelaran Teknovasi',
        ]);

        // Kategori
        $kategori = EventCategory::create([
            'name' => 'Festival',
            'slug' => 'festival',
            'description' => 'Event festival dan pameran',
        ]);

        EventCategory::create(['name' => 'Seminar', 'slug' => 'seminar']);
        EventCategory::create(['name' => 'Musik', 'slug' => 'musik']);
        EventCategory::create(['name' => 'Olahraga', 'slug' => 'olahraga']);

        // Event contoh
        $event = Event::create([
            'user_id'     => $panitia->id,
            'category_id' => $kategori->id,
            'title'       => 'Pagelaran Teknovasi',
            'slug'        => 'pagelaran-teknovasi',
            'description' => "Ajang kolaboratif terbesar tahun ini!\nDari kompetisi internasional hingga bazaar kreatif.\n\n- SEASIC 2025\n- Roboboat Competition\n- PBL Expo\n- Job Fair",
            'event_date'  => 'Batam, 19-21 Agustus 2025',
            'event_time'  => '09:00',
            'location'    => 'Halaman Parkir Politeknik Negeri Batam',
            'poster'      => 'image.png',
            'status'      => 'published',
        ]);

        // Tiket
        TicketType::create(['event_id' => $event->id, 'name' => 'Early Bird', 'price' => 20000, 'quota' => 100, 'sold' => 0]);
        TicketType::create(['event_id' => $event->id, 'name' => 'Normal',     'price' => 50000, 'quota' => 200, 'sold' => 0]);
        TicketType::create(['event_id' => $event->id, 'name' => 'VIP',        'price' => 70000, 'quota' => 50,  'sold' => 0]);
    }
}

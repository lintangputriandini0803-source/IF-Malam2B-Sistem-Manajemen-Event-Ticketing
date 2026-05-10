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
        // ─── USERS ───────────────────────────────────────────────────────────

        $admin = User::create([
            'name'     => 'Admin SIMETIX',
            'nim'      => '000000001',
            'email'    => 'admin@simetix.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'approved',
        ]);

        $panitia1 = User::create([
            'name'         => 'BEM Polibatam',
            'nim'          => '4342301001',
            'email'        => 'bem@simetix.com',
            'password'     => Hash::make('password'),
            'role'         => 'panitia',
            'status'       => 'approved',
            'organization' => 'BEM Politeknik Negeri Batam',
            'reason'       => 'Mengadakan event kampus',
        ]);

        $panitia2 = User::create([
            'name'         => 'HMTI Polibatam',
            'nim'          => '4342301002',
            'email'        => 'hmti@simetix.com',
            'password'     => Hash::make('password'),
            'role'         => 'panitia',
            'status'       => 'approved',
            'organization' => 'Himpunan Mahasiswa Teknik Informatika',
            'reason'       => 'Mengadakan seminar dan kompetisi IT',
        ]);

        $panitia3 = User::create([
            'name'         => 'UKM Musik Polibatam',
            'nim'          => '4342301003',
            'email'        => 'ukm.musik@simetix.com',
            'password'     => Hash::make('password'),
            'role'         => 'panitia',
            'status'       => 'approved',
            'organization' => 'UKM Musik',
            'reason'       => 'Mengadakan konser tahunan',
        ]);

        $panitia4 = User::create([
            'name'         => 'UKM Olahraga',
            'nim'          => '4342301004',
            'email'        => 'ukm.olahraga@simetix.com',
            'password'     => Hash::make('password'),
            'role'         => 'panitia',
            'status'       => 'approved',
            'organization' => 'UKM Olahraga Polibatam',
            'reason'       => 'Tournament olahraga antar kampus',
        ]);

        User::create([
            'name'         => 'Komunitas Fotografi',
            'nim'          => '4342301005',
            'email'        => 'foto@simetix.com',
            'password'     => Hash::make('password'),
            'role'         => 'panitia',
            'status'       => 'pending',
            'organization' => 'Komunitas Fotografi Batam',
            'reason'       => 'Pameran foto mahasiswa',
        ]);

        // ─── KATEGORI ────────────────────────────────────────────────────────

        $katFestival  = EventCategory::create(['name' => 'Festival',   'slug' => 'festival',   'description' => 'Event festival dan pameran']);
        $katSeminar   = EventCategory::create(['name' => 'Seminar',    'slug' => 'seminar',    'description' => 'Seminar dan workshop']);
        $katMusik     = EventCategory::create(['name' => 'Musik',      'slug' => 'musik',      'description' => 'Konser dan pertunjukan musik']);
        $katOlahraga  = EventCategory::create(['name' => 'Olahraga',   'slug' => 'olahraga',   'description' => 'Tournament dan event olahraga']);
        $katTeknologi = EventCategory::create(['name' => 'Teknologi',  'slug' => 'teknologi',  'description' => 'Event teknologi dan IT']);

        // ─── EVENTS ──────────────────────────────────────────────────────────

        $events = [
            [
                'user_id'     => $panitia1->id,
                'category_id' => $katFestival->id,
                'title'       => 'Pagelaran Teknovasi 2025',
                'slug'        => 'pagelaran-teknovasi-2025',
                'description' => "Ajang kolaboratif terbesar tahun ini!\nDari kompetisi internasional hingga bazaar kreatif.\n\n- SEASIC 2025\n- Roboboat Competition\n- CDIO Meeting\n- PBL Expo\n- Job Fair\n- Polibatam Fair & Bazaar",
                'event_date'  => 'Batam, 19-21 Agustus 2025',
                'event_time'  => '09:00',
                'location'    => 'Halaman Parkir Politeknik Negeri Batam',
                'poster'      => 'image.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Early Bird',
                        'description' => 'Harga spesial untuk pembelian awal! Dapatkan akses penuh ke semua area festival selama 3 hari. Kuota terbatas, buruan!',
                        'price'       => 20000,
                        'quota'       => 100,
                    ],
                    [
                        'name'        => 'Normal',
                        'description' => 'Tiket reguler akses penuh 3 hari festival. Nikmati semua rangkaian acara: kompetisi, pameran, job fair, dan bazaar.',
                        'price'       => 50000,
                        'quota'       => 200,
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Akses eksklusif area VIP, tempat duduk priority, welcome drink, dan goodie bag spesial Teknovasi 2025.',
                        'price'       => 70000,
                        'quota'       => 50,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia2->id,
                'category_id' => $katSeminar->id,
                'title'       => 'Seminar Nasional Kecerdasan Buatan 2025',
                'slug'        => 'seminar-nasional-ai-2025',
                'description' => "Seminar nasional membahas perkembangan AI dan Machine Learning terkini.\n\nTopik:\n- Large Language Models\n- Computer Vision\n- AI dalam Industri\n- Etika AI",
                'event_date'  => '15 September 2025',
                'event_time'  => '08:00',
                'location'    => 'Aula Utama Politeknik Negeri Batam',
                'poster'      => 'image1.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Mahasiswa',
                        'description' => 'Gratis untuk seluruh mahasiswa aktif! Wajib bawa KTM saat registrasi. Termasuk snack, materi seminar, dan e-sertifikat.',
                        'price'       => 0,
                        'quota'       => 300,
                    ],
                    [
                        'name'        => 'Umum',
                        'description' => 'Terbuka untuk masyarakat umum. Termasuk snack, materi seminar, dan e-sertifikat kehadiran.',
                        'price'       => 50000,
                        'quota'       => 100,
                    ],
                    [
                        'name'        => 'Professional',
                        'description' => 'Paket lengkap untuk profesional: makan siang, networking session eksklusif, sertifikat tercetak, dan akses workshop lanjutan.',
                        'price'       => 150000,
                        'quota'       => 50,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia3->id,
                'category_id' => $katMusik->id,
                'title'       => 'Konser Musik Tahunan SIMETIX FEST',
                'slug'        => 'konser-simetix-fest-2025',
                'description' => "Konser musik tahunan terbesar mahasiswa Polibatam!\n\nFeaturing:\n- Band-band lokal Batam\n- Penampilan UKM Musik\n- Guest star dari Jakarta\n- Bazaar Food & Drink",
                'event_date'  => '5 Oktober 2025',
                'event_time'  => '18:00',
                'location'    => 'Lapangan Olahraga Politeknik Negeri Batam',
                'poster'      => 'image3.jpeg',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Festival',
                        'description' => 'Akses masuk area festival standing. Nikmati penampilan seluruh artis dari depan panggung bersama ribuan penonton lainnya.',
                        'price'       => 35000,
                        'quota'       => 500,
                    ],
                    [
                        'name'        => 'Tribun',
                        'description' => 'Kursi tribun dengan pandangan terbaik ke panggung. Lebih nyaman, lebih eksklusif. Termasuk 1 free minuman.',
                        'price'       => 75000,
                        'quota'       => 200,
                    ],
                    [
                        'name'        => 'VVIP',
                        'description' => 'Pengalaman konser terbaik: area VVIP di depan panggung, sofa eksklusif, 2 free minuman, foto bersama artis, dan merchandise resmi.',
                        'price'       => 150000,
                        'quota'       => 50,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia4->id,
                'category_id' => $katOlahraga->id,
                'title'       => 'POLIBATAM CUP - Tournament Futsal Antar Kampus',
                'slug'        => 'polibatam-cup-futsal-2025',
                'description' => "Tournament futsal bergengsi antar mahasiswa se-Kepulauan Riau!\n\nFormat:\n- 32 tim peserta\n- Sistem gugur\n- Total hadiah Rp 10.000.000\n- Piala bergilir Rektor",
                'event_date'  => '12-14 Oktober 2025',
                'event_time'  => '08:00',
                'location'    => 'GOR Temenggung Abdul Jamal, Batam',
                'poster'      => 'image4.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Penonton Reguler',
                        'description' => 'Akses menonton seluruh pertandingan selama 3 hari di area tribune reguler. Berlaku untuk satu orang per hari.',
                        'price'       => 10000,
                        'quota'       => 1000,
                    ],
                    [
                        'name'        => 'Penonton VIP',
                        'description' => 'Kursi VIP terbaik di tribun utama dengan akses 3 hari penuh. Termasuk snack dan minuman gratis setiap hari.',
                        'price'       => 30000,
                        'quota'       => 200,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia2->id,
                'category_id' => $katTeknologi->id,
                'title'       => 'Hackathon Polibatam 24 Jam',
                'slug'        => 'hackathon-polibatam-2025',
                'description' => "Hackathon 24 jam non-stop!\n\nTheme: Smart City & IoT\n\nHadiah:\n- Juara 1: Rp 5.000.000\n- Juara 2: Rp 3.000.000\n- Juara 3: Rp 1.500.000",
                'event_date'  => '1-2 November 2025',
                'event_time'  => '08:00',
                'location'    => 'Lab Komputer Gedung B Politeknik Negeri Batam',
                'poster'      => 'image5.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Peserta (per tim)',
                        'description' => 'Registrasi tim (maks. 3 orang). Termasuk akses lab 24 jam, makan malam + sarapan, kaos peserta, sertifikat, dan kesempatan memenangkan total hadiah Rp 9.500.000.',
                        'price'       => 100000,
                        'quota'       => 50,
                    ],
                    [
                        'name'        => 'Penonton',
                        'description' => 'Akses gratis untuk menonton jalannya hackathon dan menghadiri sesi presentasi final. Hadir dan saksikan inovasi terbaik mahasiswa!',
                        'price'       => 0,
                        'quota'       => 200,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia1->id,
                'category_id' => $katSeminar->id,
                'title'       => 'Workshop UI/UX Design for Beginners',
                'slug'        => 'workshop-uiux-design-2025',
                'description' => "Workshop intensif 2 hari belajar UI/UX Design dari nol!\n\nMateri:\n- Prinsip Dasar Desain\n- Figma dari Nol\n- User Research & Wireframing\n- Prototyping\n\nFasilitas: Sertifikat, Modul, Makan Siang",
                'event_date'  => '8-9 November 2025',
                'event_time'  => '09:00',
                'location'    => 'Ruang Seminar Lt.3 Gedung A Polibatam',
                'poster'      => 'image6.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Early Bird',
                        'description' => 'Daftar lebih awal, hemat lebih banyak! Fasilitas sama dengan Regular: 2 hari workshop, modul cetak, makan siang, dan sertifikat. Kuota sangat terbatas!',
                        'price'       => 75000,
                        'quota'       => 30,
                    ],
                    [
                        'name'        => 'Regular',
                        'description' => 'Paket lengkap 2 hari workshop intensif UI/UX. Termasuk modul cetak, akses materi digital, makan siang 2x, snack, dan sertifikat resmi.',
                        'price'       => 100000,
                        'quota'       => 50,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia1->id,
                'category_id' => $katFestival->id,
                'title'       => 'Polibatam Year End Festival 2025',
                'slug'        => 'polibatam-year-end-festival-2025',
                'description' => "Rayakan akhir tahun bersama ribuan mahasiswa!\n\nAcara:\n- Pameran Karya Mahasiswa\n- Pentas Seni\n- Bazaar UMKM\n- Konser Penutup\n- Countdown 2026",
                'event_date'  => '28 Desember 2025',
                'event_time'  => '16:00',
                'location'    => 'Kampus Politeknik Negeri Batam',
                'poster'      => 'image7.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Umum',
                        'description' => 'Akses penuh ke seluruh area festival: pameran, pentas seni, bazaar UMKM, dan konser penutup. Rayakan pergantian tahun bersama!',
                        'price'       => 25000,
                        'quota'       => 1000,
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Area VIP eksklusif dengan tempat duduk terbaik di depan panggung konser. Termasuk goodie bag, free drink, dan akses area khusus countdown 2026.',
                        'price'       => 75000,
                        'quota'       => 100,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia3->id,
                'category_id' => $katMusik->id,
                'title'       => 'Acoustic Night Polibatam',
                'slug'        => 'acoustic-night-polibatam-2025',
                'description' => "Malam akustik yang memukau bersama musisi-musisi berbakat Polibatam.\n\n- Open mic mahasiswa\n- Penampilan band akustik\n- Suasana outdoor yang cozy",
                'event_date'  => '22 November 2025',
                'event_time'  => '19:00',
                'location'    => 'Taman Kampus Polibatam',
                'poster'      => 'image.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Reguler',
                        'description' => 'Nikmati malam akustik yang syahdu di taman kampus. Akses ke seluruh area outdoor dengan suasana cozy dan penampilan musisi berbakat.',
                        'price'       => 15000,
                        'quota'       => 300,
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Area VIP duduk lesehan eksklusif paling dekat dengan panggung. Termasuk 1 free minuman pilihan dan snack ringan sepanjang malam.',
                        'price'       => 40000,
                        'quota'       => 50,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia4->id,
                'category_id' => $katOlahraga->id,
                'title'       => 'Badminton Open Tournament Polibatam 2025',
                'slug'        => 'badminton-open-polibatam-2025',
                'description' => "Tournament badminton terbuka untuk mahasiswa dan umum.\n\nKategori:\n- Tunggal Putra\n- Tunggal Putri\n- Ganda Campuran\n\nTotal Hadiah: Rp 8.000.000",
                'event_date'  => '18-20 Oktober 2025',
                'event_time'  => '08:00',
                'location'    => 'GOR Bulutangkis Polibatam',
                'poster'      => 'image5.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Peserta',
                        'description' => 'Tiket pendaftaran untuk 1 kategori pertandingan. Termasuk shuttlecock resmi, nomor punggung, dan sertifikat peserta. Bisa daftar lebih dari 1 kategori.',
                        'price'       => 50000,
                        'quota'       => 128,
                    ],
                    [
                        'name'        => 'Penonton',
                        'description' => 'Akses menonton seluruh pertandingan selama 3 hari. Saksikan atlet-atlet terbaik se-Batam bersaing memperebutkan total hadiah Rp 8.000.000.',
                        'price'       => 5000,
                        'quota'       => 500,
                    ],
                ],
            ],
            [
                'user_id'     => $panitia2->id,
                'category_id' => $katTeknologi->id,
                'title'       => 'Seminar Cloud Computing 2026',
                'slug'        => 'seminar-cloud-computing-2026',
                'description' => 'Seminar tentang cloud computing dan DevOps.',
                'event_date'  => 'Januari 2026',
                'event_time'  => '09:00',
                'location'    => 'Aula Polibatam',
                'poster'      => 'image1.png',
                'status'      => 'draft',
                'tickets'     => [
                    [
                        'name'        => 'Umum',
                        'description' => 'Akses gratis untuk semua peserta. Termasuk e-sertifikat dan materi seminar digital.',
                        'price'       => 0,
                        'quota'       => 200,
                    ],
                ],
            ],
        ];

        foreach ($events as $data) {
            $tickets = $data['tickets'];
            unset($data['tickets']);

            $event = Event::create($data);

            foreach ($tickets as $ticket) {
                TicketType::create([
                    'event_id'    => $event->id,
                    'name'        => $ticket['name'],
                    'description' => $ticket['description'],
                    'price'       => $ticket['price'],
                    'quota'       => $ticket['quota'],
                    'sold'        => 0,
                ]);
            }
        }
    }
}

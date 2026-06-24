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
        //
        // Disesuaikan dengan kondisi Juni 2026:
        //   • Event lama (2025) → status 'published', end_date sudah lewat (expired/arsip)
        //   • Event aktif       → berlangsung Juli–September 2026
        //   • Event mendatang   → Oktober–Desember 2026, beberapa masih 'draft'

        $events = [

            // ── SUDAH LEWAT (arsip 2025) ─────────────────────────────────────

            [
                'user_id'     => $panitia1->id,
                'category_id' => $katFestival->id,
                'title'       => 'Pagelaran Teknovasi 2025',
                'slug'        => 'pagelaran-teknovasi-2025',
                'description' => "Ajang kolaboratif terbesar tahun ini!\nDari kompetisi internasional hingga bazaar kreatif.\n\n- SEASIC 2025\n- Roboboat Competition\n- CDIO Meeting\n- PBL Expo\n- Job Fair\n- Polibatam Fair & Bazaar",
                'event_date'  => '19 Agustus 2025',
                'end_date'    => '21 Agustus 2025',
                'event_time'  => '09:00',
                'location'    => 'Halaman Parkir Politeknik Negeri Batam',
                'poster'      => 'image.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Early Bird',
                        'description' => 'Harga spesial untuk pembelian awal. Akses penuh 3 hari festival. Kuota terbatas.',
                        'price'       => 20000,
                        'quota'       => 100,
                        'sold'        => 100,
                        'status'      => 'inactive',
                    ],
                    [
                        'name'        => 'Normal',
                        'description' => 'Tiket reguler akses penuh 3 hari festival.',
                        'price'       => 50000,
                        'quota'       => 200,
                        'sold'        => 187,
                        'status'      => 'inactive',
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Akses eksklusif area VIP, tempat duduk priority, welcome drink, dan goodie bag spesial.',
                        'price'       => 70000,
                        'quota'       => 50,
                        'sold'        => 48,
                        'status'      => 'inactive',
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
                'end_date'    => null,
                'event_time'  => '08:00',
                'location'    => 'Aula Utama Politeknik Negeri Batam',
                'poster'      => 'image1.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Mahasiswa',
                        'description' => 'Gratis untuk mahasiswa aktif. Wajib bawa KTM. Termasuk snack, materi, dan e-sertifikat.',
                        'price'       => 0,
                        'quota'       => 300,
                        'sold'        => 298,
                        'status'      => 'inactive',
                    ],
                    [
                        'name'        => 'Umum',
                        'description' => 'Terbuka untuk masyarakat umum. Termasuk snack, materi, dan e-sertifikat.',
                        'price'       => 50000,
                        'quota'       => 100,
                        'sold'        => 91,
                        'status'      => 'inactive',
                    ],
                    [
                        'name'        => 'Professional',
                        'description' => 'Paket lengkap: makan siang, networking session, sertifikat cetak, dan akses workshop lanjutan.',
                        'price'       => 150000,
                        'quota'       => 50,
                        'sold'        => 44,
                        'status'      => 'inactive',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia2->id,
                'category_id' => $katTeknologi->id,
                'title'       => 'Hackathon Polibatam 24 Jam 2025',
                'slug'        => 'hackathon-polibatam-2025',
                'description' => "Hackathon 24 jam non-stop!\n\nTheme: Smart City & IoT\n\nHadiah:\n- Juara 1: Rp 5.000.000\n- Juara 2: Rp 3.000.000\n- Juara 3: Rp 1.500.000",
                'event_date'  => '1 November 2025',
                'end_date'    => '2 November 2025',
                'event_time'  => '08:00',
                'location'    => 'Lab Komputer Gedung B Politeknik Negeri Batam',
                'poster'      => 'image5.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Peserta (per tim)',
                        'description' => 'Registrasi tim maks. 3 orang. Termasuk akses lab 24 jam, konsumsi, kaos, dan sertifikat.',
                        'price'       => 100000,
                        'quota'       => 50,
                        'sold'        => 47,
                        'status'      => 'inactive',
                    ],
                    [
                        'name'        => 'Penonton',
                        'description' => 'Akses gratis untuk menonton dan sesi presentasi final.',
                        'price'       => 0,
                        'quota'       => 200,
                        'sold'        => 156,
                        'status'      => 'inactive',
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
                'end_date'    => null,
                'event_time'  => '16:00',
                'location'    => 'Kampus Politeknik Negeri Batam',
                'poster'      => 'image7.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Umum',
                        'description' => 'Akses penuh ke seluruh area festival: pameran, pentas seni, bazaar UMKM, dan konser penutup.',
                        'price'       => 25000,
                        'quota'       => 1000,
                        'sold'        => 934,
                        'status'      => 'inactive',
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Area VIP eksklusif, tempat duduk terbaik, goodie bag, free drink, dan akses area khusus countdown.',
                        'price'       => 75000,
                        'quota'       => 100,
                        'sold'        => 100,
                        'status'      => 'inactive',
                    ],
                ],
            ],

            // ── SEDANG BERLANGSUNG / AKAN DATANG DEKAT (Juli–Agustus 2026) ──

            [
                'user_id'     => $panitia1->id,
                'category_id' => $katFestival->id,
                'title'       => 'Pagelaran Teknovasi 2026',
                'slug'        => 'pagelaran-teknovasi-2026',
                'description' => "Kembali hadir lebih meriah!\nAjang kolaboratif terbesar Polibatam 2026.\n\n- SEASIC 2026\n- Roboboat Competition\n- PBL Expo & Demo Day\n- Job Fair 2026\n- Polibatam Fair & Bazaar UMKM\n- Pameran Inovasi Mahasiswa",
                'event_date'  => '18 Juli 2026',
                'end_date'    => '20 Juli 2026',
                'event_time'  => '09:00',
                'location'    => 'Halaman Parkir Politeknik Negeri Batam',
                'poster'      => 'image.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Early Bird',
                        'description' => 'Harga spesial pembelian awal! Akses penuh 3 hari festival. Kuota sangat terbatas, buruan!',
                        'price'       => 20000,
                        'quota'       => 150,
                        'sold'        => 143,
                        'status'      => 'active',
                        'closes_at'   => '2026-07-05 23:59:00',
                    ],
                    [
                        'name'        => 'Normal',
                        'description' => 'Tiket reguler akses penuh 3 hari festival. Nikmati semua rangkaian acara: kompetisi, pameran, job fair, dan bazaar.',
                        'price'       => 50000,
                        'quota'       => 300,
                        'sold'        => 87,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Akses eksklusif area VIP, kursi priority, welcome drink, goodie bag spesial Teknovasi 2026, dan sesi networking dengan pembicara.',
                        'price'       => 100000,
                        'quota'       => 75,
                        'sold'        => 22,
                        'status'      => 'active',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia3->id,
                'category_id' => $katMusik->id,
                'title'       => 'Konser Musik Tahunan SIMETIX FEST 2026',
                'slug'        => 'konser-simetix-fest-2026',
                'description' => "Konser musik tahunan terbesar mahasiswa Polibatam kembali hadir!\n\nFeaturing:\n- Band-band terbaik Batam\n- Penampilan UKM Musik Polibatam\n- Guest star dari Jakarta\n- DJ Set malam hari\n- Bazaar Food & Craft",
                'event_date'  => '2 Agustus 2026',
                'end_date'    => null,
                'event_time'  => '18:00',
                'location'    => 'Lapangan Olahraga Politeknik Negeri Batam',
                'poster'      => 'image3.jpeg',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Festival',
                        'description' => 'Akses masuk area festival standing. Nikmati penampilan seluruh artis bersama ribuan penonton.',
                        'price'       => 45000,
                        'quota'       => 600,
                        'sold'        => 312,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'Tribun',
                        'description' => 'Kursi tribun dengan pandangan terbaik ke panggung. Lebih nyaman dan eksklusif. Termasuk 1 free minuman.',
                        'price'       => 85000,
                        'quota'       => 200,
                        'sold'        => 78,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'VVIP',
                        'description' => 'Pengalaman terbaik: area VVIP di depan panggung, sofa eksklusif, 2 free minuman, foto bersama artis, dan merchandise resmi.',
                        'price'       => 175000,
                        'quota'       => 60,
                        'sold'        => 18,
                        'status'      => 'active',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia2->id,
                'category_id' => $katSeminar->id,
                'title'       => 'Workshop UI/UX Design Intermediate 2026',
                'slug'        => 'workshop-uiux-intermediate-2026',
                'description' => "Workshop intensif 2 hari UI/UX Design level menengah!\n\nMateri:\n- Advanced Figma & Prototyping\n- Design System\n- User Testing & Iterasi\n- Portfolio Building\n- Studi Kasus Industri\n\nFasilitas: Sertifikat, Modul Digital, Makan Siang 2x",
                'event_date'  => '15 Agustus 2026',
                'end_date'    => '16 Agustus 2026',
                'event_time'  => '09:00',
                'location'    => 'Ruang Seminar Lt.3 Gedung A Polibatam',
                'poster'      => 'image6.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Early Bird',
                        'description' => 'Daftar lebih awal, hemat lebih banyak! Fasilitas lengkap sama dengan Regular. Kuota sangat terbatas!',
                        'price'       => 85000,
                        'quota'       => 25,
                        'sold'        => 25,
                        'status'      => 'inactive', // sudah habis
                        'closes_at'   => '2026-08-01 23:59:00',
                    ],
                    [
                        'name'        => 'Regular',
                        'description' => 'Paket lengkap 2 hari workshop: modul digital, makan siang 2x, snack, dan sertifikat resmi.',
                        'price'       => 120000,
                        'quota'       => 50,
                        'sold'        => 31,
                        'status'      => 'active',
                    ],
                ],
            ],

            // ── MENDATANG (September–Oktober 2026) ───────────────────────────

            [
                'user_id'     => $panitia2->id,
                'category_id' => $katSeminar->id,
                'title'       => 'Seminar Nasional Kecerdasan Buatan 2026',
                'slug'        => 'seminar-nasional-ai-2026',
                'description' => "Seminar nasional membahas tren AI terkini di tahun 2026.\n\nTopik:\n- Generative AI & LLM Terbaru\n- AI Agent & Autonomous Systems\n- AI dalam Pendidikan & Industri\n- Regulasi & Etika AI di Indonesia",
                'event_date'  => '12 September 2026',
                'end_date'    => null,
                'event_time'  => '08:00',
                'location'    => 'Aula Utama Politeknik Negeri Batam',
                'poster'      => 'image1.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Mahasiswa',
                        'description' => 'Gratis untuk mahasiswa aktif. Wajib bawa KTM saat registrasi. Termasuk snack, materi seminar, dan e-sertifikat.',
                        'price'       => 0,
                        'quota'       => 350,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'Umum',
                        'description' => 'Terbuka untuk masyarakat umum. Termasuk snack, materi seminar, dan e-sertifikat kehadiran.',
                        'price'       => 75000,
                        'quota'       => 100,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'Professional',
                        'description' => 'Paket lengkap: makan siang, sesi networking eksklusif, sertifikat cetak, dan akses workshop lanjutan sore hari.',
                        'price'       => 200000,
                        'quota'       => 50,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia4->id,
                'category_id' => $katOlahraga->id,
                'title'       => 'POLIBATAM CUP - Tournament Futsal 2026',
                'slug'        => 'polibatam-cup-futsal-2026',
                'description' => "Tournament futsal bergengsi antar mahasiswa se-Kepulauan Riau!\n\nFormat:\n- 32 tim peserta\n- Sistem gugur\n- Total hadiah Rp 12.000.000\n- Piala bergilir Rektor Polibatam",
                'event_date'  => '3 Oktober 2026',
                'end_date'    => '5 Oktober 2026',
                'event_time'  => '08:00',
                'location'    => 'GOR Temenggung Abdul Jamal, Batam',
                'poster'      => 'image4.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Penonton Reguler',
                        'description' => 'Akses menonton seluruh pertandingan di tribune reguler. Berlaku 3 hari penuh.',
                        'price'       => 15000,
                        'quota'       => 1000,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'Penonton VIP',
                        'description' => 'Kursi VIP di tribune utama, akses 3 hari penuh. Termasuk snack dan minuman gratis setiap hari.',
                        'price'       => 40000,
                        'quota'       => 200,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia3->id,
                'category_id' => $katMusik->id,
                'title'       => 'Acoustic Night Polibatam 2026',
                'slug'        => 'acoustic-night-polibatam-2026',
                'description' => "Malam akustik yang memukau bersama musisi-musisi berbakat Polibatam.\n\n- Open mic mahasiswa\n- Penampilan band akustik\n- Special guest performer\n- Suasana outdoor yang cozy\n- Bazaar kuliner malam",
                'event_date'  => '17 Oktober 2026',
                'end_date'    => null,
                'event_time'  => '19:00',
                'location'    => 'Taman Kampus Polibatam',
                'poster'      => 'image.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Reguler',
                        'description' => 'Nikmati malam akustik syahdu di taman kampus. Akses ke seluruh area outdoor.',
                        'price'       => 20000,
                        'quota'       => 350,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Area VIP lesehan eksklusif paling dekat panggung. Termasuk 1 free minuman dan snack ringan sepanjang malam.',
                        'price'       => 50000,
                        'quota'       => 60,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia4->id,
                'category_id' => $katOlahraga->id,
                'title'       => 'Badminton Open Tournament Polibatam 2026',
                'slug'        => 'badminton-open-polibatam-2026',
                'description' => "Tournament badminton terbuka untuk mahasiswa dan umum.\n\nKategori:\n- Tunggal Putra\n- Tunggal Putri\n- Ganda Campuran\n\nTotal Hadiah: Rp 10.000.000",
                'event_date'  => '24 Oktober 2026',
                'end_date'    => '26 Oktober 2026',
                'event_time'  => '08:00',
                'location'    => 'GOR Bulutangkis Polibatam',
                'poster'      => 'image5.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Peserta',
                        'description' => 'Tiket pendaftaran 1 kategori. Termasuk shuttlecock resmi, nomor punggung, dan sertifikat peserta.',
                        'price'       => 60000,
                        'quota'       => 128,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'Penonton',
                        'description' => 'Akses menonton seluruh pertandingan 3 hari. Saksikan atlet terbaik se-Batam beraksi!',
                        'price'       => 5000,
                        'quota'       => 500,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                ],
            ],

            // ── AKHIR TAHUN 2026 / DRAFT ──────────────────────────────────────

            [
                'user_id'     => $panitia2->id,
                'category_id' => $katTeknologi->id,
                'title'       => 'Hackathon Polibatam 24 Jam 2026',
                'slug'        => 'hackathon-polibatam-2026',
                'description' => "Hackathon 24 jam non-stop, kembali hadir!\n\nTheme: AI for Good\n\nHadiah:\n- Juara 1: Rp 7.000.000\n- Juara 2: Rp 4.000.000\n- Juara 3: Rp 2.000.000\n- Best Innovation: Rp 1.000.000",
                'event_date'  => '7 November 2026',
                'end_date'    => '8 November 2026',
                'event_time'  => '08:00',
                'location'    => 'Lab Komputer Gedung B Politeknik Negeri Batam',
                'poster'      => 'image5.png',
                'status'      => 'published',
                'tickets'     => [
                    [
                        'name'        => 'Peserta (per tim)',
                        'description' => 'Registrasi tim maks. 3 orang. Termasuk akses lab 24 jam, konsumsi lengkap, kaos peserta, sertifikat, dan kesempatan memenangkan total hadiah Rp 14.000.000.',
                        'price'       => 125000,
                        'quota'       => 60,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'Penonton',
                        'description' => 'Akses gratis untuk menonton dan hadir di sesi presentasi final tim peserta.',
                        'price'       => 0,
                        'quota'       => 200,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia2->id,
                'category_id' => $katTeknologi->id,
                'title'       => 'Seminar Cloud Computing & DevOps 2026',
                'slug'        => 'seminar-cloud-devops-2026',
                'description' => "Seminar tentang Cloud Computing modern dan praktik DevOps terkini.\n\nTopik:\n- Cloud Architecture (AWS, GCP, Azure)\n- CI/CD Pipeline\n- Containerization & Kubernetes\n- Cloud Security",
                'event_date'  => 'Desember 2026',
                'end_date'    => null,
                'event_time'  => '09:00',
                'location'    => 'Aula Polibatam',
                'poster'      => 'image1.png',
                'status'      => 'draft',
                'tickets'     => [
                    [
                        'name'        => 'Umum',
                        'description' => 'Akses gratis untuk semua peserta. Termasuk e-sertifikat dan materi seminar digital.',
                        'price'       => 0,
                        'quota'       => 250,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                ],
            ],
            [
                'user_id'     => $panitia1->id,
                'category_id' => $katFestival->id,
                'title'       => 'Polibatam Year End Festival 2026',
                'slug'        => 'polibatam-year-end-festival-2026',
                'description' => "Rayakan akhir tahun 2026 bersama ribuan mahasiswa!\n\nAcara:\n- Pameran Karya & Inovasi Mahasiswa\n- Pentas Seni & Budaya\n- Bazaar UMKM & Kuliner\n- Konser Penutup\n- Countdown 2027",
                'event_date'  => '28 Desember 2026',
                'end_date'    => null,
                'event_time'  => '16:00',
                'location'    => 'Kampus Politeknik Negeri Batam',
                'poster'      => 'image7.png',
                'status'      => 'draft',
                'tickets'     => [
                    [
                        'name'        => 'Umum',
                        'description' => 'Akses penuh ke seluruh area festival: pameran, pentas seni, bazaar, dan konser penutup.',
                        'price'       => 30000,
                        'quota'       => 1000,
                        'sold'        => 0,
                        'status'      => 'active',
                    ],
                    [
                        'name'        => 'VIP',
                        'description' => 'Area VIP eksklusif, tempat duduk terbaik, goodie bag, free drink, dan akses area khusus countdown 2027.',
                        'price'       => 90000,
                        'quota'       => 100,
                        'sold'        => 0,
                        'status'      => 'active',
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
                    'sold'        => $ticket['sold'] ?? 0,
                    'status'      => $ticket['status'] ?? 'active',
                    'closes_at'   => $ticket['closes_at'] ?? null,
                ]);
            }
        }
    }
}

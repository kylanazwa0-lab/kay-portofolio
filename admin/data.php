<?php
// ============================================================
// data.php — Read/Write portfolio.json
// ============================================================
require_once __DIR__ . '/config.php';

function loadData(): array {
    if (!file_exists(DATA_FILE)) {
        return getDefaultData();
    }
    $json = file_get_contents(DATA_FILE);
    $data = json_decode($json, true);
    return $data ?: getDefaultData();
}

function saveData(array $data): bool {
    $dir = dirname(DATA_FILE);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(DATA_FILE, $json) !== false;
}

function getDefaultData(): array {
    return [
        'meta' => [
            'name'     => 'Kyla Nazwa Salsabila',
            'title'    => 'Junior Full-Stack Developer & System Analyst',
            'tagline'  => 'Membangun sistem, bukan hanya kode.',
            'email'    => 'kylanazwa0@gmail.com',
            'phone'    => '085659199540',
            'linkedin' => 'https://linkedin.com/in/kylanazwa',
            'location' => 'Bandung, Jawa Barat',
            'gpa'      => '3,75/4,00',
            'status'   => 'Open to Work',
        ],
        'diagrams' => [
            [
                'id'          => 'bpmn',
                'label'       => 'BPMN',
                'title'       => 'BPMN — Business Process Model & Notation',
                'description' => 'Pemodelan proses bisnis end-to-end menggunakan notasi BPMN 2.0 dengan swimlane per aktor.',
                'highlights'  => ['4 skenario alur proses', 'Swimlane multi-aktor', 'Gateway kondisional (XOR, AND)', 'Notasi standar BPMN 2.0'],
                'image'       => '',
            ],
            [
                'id'          => 'usecase',
                'label'       => 'Use Case',
                'title'       => 'Use Case Diagram',
                'description' => 'Pemodelan interaksi semua aktor dengan sistem — mencakup 5 peran pengguna dan seluruh use case utama.',
                'highlights'  => ['5 aktor pengguna', '15+ use case terdokumentasi', 'Relasi include & extend', 'Basis implementasi RBAC'],
                'image'       => '',
            ],
            [
                'id'          => 'erd',
                'label'       => 'ERD',
                'title'       => 'Entity Relationship Diagram',
                'description' => 'Desain database relasional dengan normalisasi 3NF — mengelola hubungan kompleks antar entitas.',
                'highlights'  => ['11 entitas / tabel utama', 'Normalisasi hingga 3NF', 'Relasi One-to-Many & Many-to-Many', 'Foreign key constraints'],
                'image'       => '',
            ],
            [
                'id'          => 'sequence',
                'label'       => 'Sequence',
                'title'       => 'Sequence Diagram',
                'description' => 'Alur interaksi antar komponen sistem dari request hingga response secara kronologis.',
                'highlights'  => ['Alur request-response real-time', 'Validasi token dengan expiry check', 'Race condition prevention', 'Error handling komprehensif'],
                'image'       => '',
            ],
        ],
        'projects' => [
            [
                'id'          => 'aims',
                'number'      => '01',
                'status'      => 'live',
                'status_label'=> '🟢 Live',
                'type'        => 'Enterprise System',
                'icon'        => '',
                'title'       => 'AIMS — ACF Integrated Management Systems',
                'tagline'     => 'Backbone digital operasional perusahaan · PAC (2025–2026)',
                'description' => 'Membangun sistem manajemen internal terpusat dari nol menggunakan Native PHP MVC — kini berstatus live dan aktif digunakan seluruh karyawan.',
                'highlights'  => [['num'=>'8','label'=>'Modul Operasional'],['num'=>'4','label'=>'Level Akses RBAC'],['num'=>'100%','label'=>'Custom MVC']],
                'features'    => ['Custom MVC Architecture dari nol dengan Native PHP','RBAC multi-level: Admin, Director, Dept Head, Staff (8 modul)','Import/Export Excel massal, Soft-Delete, Trash Recovery','Dashboard interaktif real-time','Integrasi AI-assisted workflow (Claude, Gemini, GPT)'],
                'tech'        => ['Native PHP','MVC','MySQL','MariaDB','Tailwind CSS','JavaScript','AJAX'],
                'tags'        => 'live php enterprise',
                'link_url'    => 'http://inventoryacf.test:8080',
                'attachment'  => ''
            ],
            [
                'id'          => 'guruverse',
                'number'      => '02',
                'status'      => 'indev',
                'status_label'=> ' In Dev',
                'type'        => 'LMS Platform',
                'icon'        => '',
                'title'       => 'Guruverse LMS',
                'tagline'     => 'Platform pembelajaran digital enterprise · PAC (2025–2026)',
                'description' => 'Mengembangkan ekosistem LMS komprehensif berbasis Laravel — mencakup Modul Belajar, Modul Mengajar, dan forum interaktif Komunitas Inspira.',
                'highlights'  => [['num'=>'3','label'=>'Modul Utama'],['num'=>'Laravel','label'=>'Framework'],['num'=>'Full','label'=>'Responsive UX']],
                'features'    => ['3 pilar: Modul Belajar, Modul Mengajar, Komunitas Inspira','Tracking progres belajar pengguna','Backend scalable & secure dengan Laravel','Antarmuka responsif & modern — AJAX untuk UX tanpa interupsi'],
                'tech'        => ['PHP','Laravel','MySQL','Tailwind CSS','JavaScript','AJAX'],
                'tags'        => 'laravel live enterprise',
                'link_url'    => 'http://guruverse.test:8080',
                'attachment'  => ''
            ],
            [
                'id'          => 'siponten',
                'number'      => '03',
                'status'      => 'indev',
                'status_label'=> ' In Dev',
                'type'        => 'Absensi & Payroll',
                'icon'        => '',
                'title'       => 'SIPonten — Absensi & Payroll',
                'tagline'     => 'Sistem Informasi Presensi & Penggajian · Teras Kreasi (2025)',
                'description' => 'Sistem absensi dan penggajian otomatis untuk karyawan Teras Kreasi dengan sistem perhitungan gaji dinamis berdasarkan jam kerja riil.',
                'highlights'  => [['num'=>'100%','label'=>'Akurasi Gaji'],['num'=>'1-Click','label'=>'Generate Report'],['num'=>'Real-time','label'=>'Tracking']],
                'features'    => ['Absensi real-time dengan validasi waktu & lokasi','Perhitungan gaji otomatis (Gaji Pokok, Lembur, Potongan)','Export PDF/Excel untuk laporan HR bulanan','Tampilan admin dashboard informatif untuk manajemen HR'],
                'tech'        => ['PHP','Laravel','MySQL','Tailwind CSS','Bootstrap'],
                'tags'        => 'laravel enterprise',
                'link_url'    => 'http://absensi.test:8080',
                'attachment'  => ''
            ],
            [
                'id'          => 'salespred',
                'number'      => '04',
                'status'      => 'done',
                'status_label'=> ' Selesai',
                'type'        => 'Tugas Akhir',
                'icon'        => '',
                'title'       => 'Sistem Monitoring & Prediksi Penjualan',
                'tagline'     => 'Moving Average Algorithm · PT Maju Global Motor (2025)',
                'description' => 'Sistem prediksi penjualan kendaraan berbasis Simple Moving Average (SMA) dengan MAE hanya 2,11 unit/bulan.',
                'highlights'  => [['num'=>'2.11','label'=>'MAE (unit/bln)'],['num'=>'6','label'=>'Peran Pengguna'],['num'=>'Kano','label'=>'Metode Riset']],
                'features'    => ['Algoritma Simple Moving Average (SMA)','Dashboard real-time monitoring','Laporan otomatis PDF & Excel','RBAC 6 peran + Black Box Testing','Riset Metode Kano'],
                'tech'        => ['PHP','CodeIgniter','MySQL','JavaScript','Moving Average'],
                'tags'        => 'php ci analysis',
            ],
            [
                'id'          => 'hilasari',
                'number'      => '05',
                'status'      => 'done',
                'status_label'=> ' Selesai',
                'type'        => 'Web App',
                'icon'        => '',
                'title'       => 'Sistem Informasi Rekrutmen Karyawan',
                'tagline'     => 'Digitalisasi rekrutmen · HilaSari Catering',
                'description' => 'Merancang dan mengembangkan sistem rekrutmen berbasis web secara mandiri end-to-end menggunakan metodologi Extreme Programming (XP).',
                'highlights'  => [['num'=>'XP','label'=>'Metodologi'],['num'=>'Solo','label'=>'Developer'],['num'=>'Full','label'=>'SDLC']],
                'features'    => ['Halaman lowongan publik','Formulir pendaftaran digital','Transparansi hasil seleksi','ERD + dokumentasi teknis','Solo developer — metodologi XP'],
                'tech'        => ['PHP','MySQL','HTML5','CSS3','JavaScript'],
                'tags'        => 'php analysis',
            ],
            [
                'id'          => 'mgm-report',
                'number'      => '06',
                'status'      => 'done',
                'status_label'=> ' Selesai',
                'type'        => 'System Design',
                'icon'        => '',
                'title'       => 'Sistem Terintegrasi Laporan Penjualan Kendaraan',
                'tagline'     => 'PIECES Analysis · PT Maju Global Motor (2025)',
                'description' => 'Mengotomasi proses penyusunan laporan penjualan yang sebelumnya memakan 15–20 jam/bulan. Analisis PIECES kepuasan informasi 55%.',
                'highlights'  => [['num'=>'55%','label'=>'Kepuasan Awal'],['num'=>'15–20','label'=>'Jam/bln Dihemat'],['num'=>'5','label'=>'Jenis Pengguna']],
                'features'    => ['Analisis PIECES sistematis','Automasi penarikan data faktur','Role-based access 5 pengguna','Perancangan database terintegrasi'],
                'tech'        => ['Visual Basic','SQL Server','PIECES Analysis','Database Design'],
                'tags'        => 'analysis',
            ],
        ],
        'skills' => [
            ['category'=>'Backend',               'icon'=>'', 'items'=>[['name'=>'PHP (Native)','level'=>'expert'],['name'=>'Laravel','level'=>'expert'],['name'=>'CodeIgniter 3','level'=>'intermediate'],['name'=>'MVC Architecture','level'=>'intermediate'],['name'=>'AJAX','level'=>'intermediate'],['name'=>'Visual Basic','level'=>'beginner']]],
            ['category'=>'Database',              'icon'=>'', 'items'=>[['name'=>'MySQL','level'=>'expert'],['name'=>'MariaDB','level'=>'expert'],['name'=>'ERD Design','level'=>'intermediate'],['name'=>'Database Normalization','level'=>'intermediate'],['name'=>'SQL Server','level'=>'beginner']]],
            ['category'=>'Frontend',              'icon'=>'', 'items'=>[['name'=>'HTML5','level'=>'expert'],['name'=>'CSS3','level'=>'expert'],['name'=>'Tailwind CSS','level'=>'expert'],['name'=>'JavaScript','level'=>'intermediate'],['name'=>'Bootstrap','level'=>'intermediate'],['name'=>'Figma','level'=>'beginner']]],
            ['category'=>'System Analysis',       'icon'=>'', 'items'=>[['name'=>'UML','level'=>'expert'],['name'=>'BPMN','level'=>'expert'],['name'=>'PIECES Analysis','level'=>'expert'],['name'=>'Kano Method','level'=>'intermediate'],['name'=>'Black Box Testing','level'=>'intermediate']]],
            ['category'=>'Security & Architecture','icon'=>'','items'=>[['name'=>'RBAC Multi-level','level'=>'expert'],['name'=>'Soft-Delete / Trash Recovery','level'=>'intermediate'],['name'=>'Session Management','level'=>'intermediate'],['name'=>'Import/Export Excel','level'=>'intermediate']]],
            ['category'=>'AI-Assisted Dev',       'icon'=>'', 'items'=>[['name'=>'Claude AI','level'=>'ai-tag'],['name'=>'Gemini','level'=>'ai-tag'],['name'=>'ChatGPT','level'=>'ai-tag'],['name'=>'Prompt Engineering','level'=>'intermediate'],['name'=>'AI Workflow Integration','level'=>'intermediate']]],
        ],
        'last_updated' => date('Y-m-d H:i:s'),
    ];
}

# Flow Sistem SIMKESRA

Dokumentasi alur utama per role berdasarkan definisi route dan fitur di kode saat ini. Flowchart menggunakan PlantUML (dapat ditempel ke https://www.planttext.com/).

## Ringkasan Modul Utama
- Autentikasi: Login, reset password, verifikasi email; middleware `role.redirect`, `auth`, `throttle`.
- Role-based access: Middleware `checkActiveRole:{role}` memisahkan akses per role.
- Dashboard & informasi: Komponen Livewire untuk dashboard, informasi umum, dan log viewer (admin).
- Periode & skema bantuan: CRUD periode, skema, serta dashboard bank per periode.
- Penerima & bantuan: Datalist, edit, upload penerima, cetak kartu, tanda terima.
- Validasi & verifikasi: Scan QR, flagging/validasi, pivot view, laporan pemenangan (validator), bukti unggahan.

## Flow per Role

### Admin
- Akses: Dashboard, log viewer, manajemen user & role, staff teller, informasi, periode, skema, penerima, upload penerima, cetak kartu, dashboard bank (scan QR, flagging, pivot), laporan dashboard informasi.
- Kebutuhan khusus: Hanya admin yang bisa melihat `/logs`.

```plantuml
@startuml
start
:Login (auth + checkActiveRole:admin);
:Dashboard Admin;
if (Kelola user/staff?) then (ya)
  :Datalist user/staff;
  :Add/Edit/Plot Role/Skema;
endif
if (Kelola informasi?) then (ya)
  :Kelola informasi & dashboard info;
endif
if (Kelola periode/skema?) then (ya)
  :Datalist/Add/Edit Periode;
  :Datalist/Add/Edit Skema;
endif
if (Kelola penerima/bantuan?) then (ya)
  :Datalist/Edit Penerima;
  :Upload penerima bantuan;
  :Cetak kartu (single/all/per kecamatan);
endif
if (Validasi bank?) then (ya)
  :Dashboard bank per periode;
  :Scan QR -> Validasi/Biodata Save;
  :Flagging penerima;
  :Pivot view;
endif
:Log viewer (admin only);
stop
@enduml
```

### KESRA Manager
- Akses: Mirip admin untuk periode/skema/penerima, informasi, dashboard bank (scan QR, flagging, pivot) tetapi tanpa log viewer dan tanpa cetak kartu massal kecamatan.

```plantuml
@startuml
start
:Login (auth + checkActiveRole:kesra);
:Dashboard KESRA;
:Kelola user/staff terbatas;
:Kelola informasi & dashboard info;
:Kelola periode & skema;
:Kelola penerima & upload bantuan;
:Dashboard bank per periode (scan QR, flagging, pivot);
:Cetak kartu penerima;
stop
@enduml
```

### Bank Officer
- Akses: Dashboard bank per periode, manage teller (add/edit), scan QR & validasi, flagging, pivot, penerima & kartu, tanda terima. Tidak ada CRUD skema/periode penuh.

```plantuml
@startuml
start
:Login (auth + checkActiveRole:bank);
:Dashboard Bank;
if (Kelola teller?) then (ya)
  :Datalist/Add/Edit teller;
endif
:Periode bank -> dashboard/pivot;
:Scan QR -> Validasi/Biodata Save;
:Flagging penerima;
:Penerima & bantuan (lihat/edit, cetak kartu, tanda terima);
stop
@enduml
```

### Teller
- Akses: Dashboard, periode bank (dashboard, scan QR, flagging, pivot), penerima & bantuan (lihat/edit, cetak kartu, tanda terima). Tidak kelola teller lain.

```plantuml
@startuml
start
:Login (auth + checkActiveRole:teller);
:Dashboard Teller;
:Periode bank -> dashboard/pivot;
:Scan QR -> Validasi/Biodata Save;
:Flagging penerima;
:Penerima & bantuan (lihat/edit, cetak kartu, tanda terima);
stop
@enduml
```

### Validator
- Akses: Dashboard validator, datalist periode, dashboard view, pemenangan (datalist/create), upload bukti, laporan pemenangan, kelola profil.

```plantuml
@startuml
start
:Login (auth + checkActiveRole:validator);
:Dashboard Validator;
:Datalist periode (validator view);
:Dashboard view per periode;
:Pemenangan datalist -> create/edit;
:Upload bukti pemenangan;
:Generate report pemenangan;
:Kelola profil validator;
stop
@enduml
```

### Unit Head
- Akses: Dashboard, datalist/edit penerima, periode (view dashboard/pivot), bantuan (datalist, cetak kartu, kartu per kecamatan).

```plantuml
@startuml
start
:Login (auth + checkActiveRole:unit);
:Dashboard Unit;
:Penerima datalist/edit;
:Periode bank -> dashboard/pivot;
:Bantuan datalist;
:Cetak kartu (all/per user/per kecamatan);
stop
@enduml
```

## Catatan Teknis
- Middleware per role: `checkActiveRole:{role}` pada setiap grup route.
- Autentikasi & keamanan: throttle login, verifikasi email, CSP diaktifkan (`CSP_ENABLED=true`), reCAPTCHA tersedia di frontend.
- Komponen utama berbasis Livewire; beberapa aksi khusus via controller (`Validateqr` untuk scan/validasi QR, `Kartu/Kartuall` untuk cetak kartu).
- Untuk menggambar flow, salin blok PlantUML di atas ke PlantText.

## Flow Detail Modul

### Modul Autentikasi & Akses
- Login → cek throttle → cek role aktif → redirect ke dashboard sesuai role.
- Lupa password → kirim link reset → verifikasi token → set password baru.
- Verifikasi email → kirim ulang link bila diminta → setelah verifikasi, akses normal.
- Middleware role memastikan tiap grup route hanya bisa diakses oleh role sesuai.

### Modul Manajemen User & Staff
- Admin/KESRA: daftar user/staff → tambah/edit → plot role → (opsional) plot skema.
- Bank: daftar teller → tambah/edit → aktif/nonaktif.
- Hasil akhir: user punya peran jelas untuk akses modul lainnya.

### Modul Informasi & Dashboard Informasi
- Lihat daftar informasi → tambah/edit konten informasi → tampil di dashboard informasi.
- Digunakan lintas role (admin/kesra) untuk menyebar info operasional.

### Modul Periode Bantuan
- Daftar periode → tambah/edit periode (nama, waktu, status) → aktifkan untuk penggunaan.
- Periode aktif jadi konteks untuk penerima, bank dashboard, validator, cetak kartu.

### Modul Skema Bantuan
- Daftar skema → tambah/edit skema (jenis bantuan, kriteria, nominal/benefit).
- Skema dikaitkan ke periode dan penerima bantuan.

### Modul Penerima & Bantuan
- Daftar penerima → edit data penerima.
- Upload penerima bantuan (bulk) → mapping ke periode & skema.
- Lihat daftar penerima bantuan per periode → cetak kartu (single/all/per kecamatan) → unggah tanda terima.

### Modul Validasi QR & Flagging (Bank/Teller/Admin/KESRA)
- Scan QR penerima → validasi token → tampil biodata.
- (Opsional) perbarui biodata → simpan validasi status → flagging hasil.
- Pivot view untuk analisis status penerima per periode.

### Modul Pemenangan (Validator)
- Lihat periode → buka dashboard periode → kelola data pemenangan (buat/edit).
- Unggah bukti pemenangan → hasilkan laporan pemenangan.

### Modul Cetak Kartu & Tanda Terima
- Akses daftar penerima bantuan → pilih mode cetak: single, semua, per kecamatan.
- Cetak kartu → distribusi → unggah/isi tanda terima sebagai bukti penyerahan.

### Modul Dashboard Per Role
- Admin/KESRA: dashboard umum + informasi + bank dashboard + manajemen data.
- Bank/Teller: dashboard bank fokus operasional (scan QR, validasi, pivot).
- Validator: dashboard verifikasi dan pemenangan.
- Unit: dashboard monitoring penerima & cetak kartu.

## Detail Status & Keputusan per Modul

### Status Umum Penerima/Bantuan
- Draft → Pending → Approved/Validated → Distributed → Flagged (gagal/tolak) → Done.
- Flagging dipakai saat validasi gagal, data tidak cocok, atau QR tidak valid.
- Tanda terima dan bukti cetak kartu menjadi penutup alur distribusi.

### Periode Bantuan
- Draft: baru dibuat, belum diaktifkan untuk operasi.
- Aktif: dipakai untuk scan/validasi/penerima bantuan.
- Selesai/Close: hanya untuk laporan, tidak menerima transaksi baru.

### Pemenangan (Validator)
- Kandidat → Diverifikasi → Pemenang/Tolak → Bukti diunggah → Laporan diterbitkan.

### Pivot & Analitik
- Menyajikan jumlah penerima per status (pending/validated/flagged/distributed) dan per lokasi/periode.

## Alur Lengkap (lebih rinci)

### Alur Upload Penerima Bantuan (Admin/KESRA)
1) Siapkan file penerima (format sesuai template).
2) Unggah ke halaman upload penerima bantuan.
3) Sistem memetakan ke periode & skema aktif.
4) Data tampil di datalist penerima bantuan untuk verifikasi manual.
5) Lanjut ke proses cetak kartu / distribusi.

### Alur Scan QR → Validasi → Flagging → Distribusi
```plantuml
@startuml
start
:Buka halaman Scan QR;
:Scan kode (kamera/entry manual);
:Validasi token QR;
if (Token valid?) then (ya)
  :Muat biodata penerima + status bantuan;
  if (Data perlu koreksi?) then (ya)
    :Update biodata (opsional);
  endif
  :Set status Validated/Approved;
  if (Ditemukan masalah?) then (ya)
    :Set status Flagged (catat alasan);
  else (tidak)
    :Lanjut ke distribusi/cetak kartu;
  endif
  :(Opsional) Cetak kartu + kumpulkan tanda terima;
else (tidak)
  :Tolak scan, beri notifikasi QR tidak valid/expired;
endif
stop
@enduml
```

### Alur Cetak Kartu & Tanda Terima (Single/All/Per Kecamatan)
1) Pilih penerima atau filter (all/kecamatan).
2) Generate kartu sesuai pilihan.
3) Distribusi fisik kepada penerima.
4) Isi/unggah tanda terima sebagai bukti serah-terima.

### Alur Pemenangan (Validator)
1) Pilih periode yang dievaluasi.
2) Lihat daftar kandidat pemenangan.
3) Verifikasi data & dokumen.
4) Set status pemenang/ditolak.
5) Unggah bukti pendukung.
6) Terbitkan laporan pemenangan.

### Alur Informasi & Dashboard Informasi
1) Buat/ubah konten informasi.
2) Publikasi ke dashboard informasi pengguna.
3) Informasi tampil untuk role terkait sebagai referensi operasional.

### Alur Manajemen User/Staff/Teller
1) Datalist user/staff/teller.
2) Tambah/edit data profil dan penugasan (role, skema, bank/unit).
3) Aktif/nonaktif pengguna.
4) Pengguna baru mengikuti alur autentikasi umum sebelum mengakses modul.

## Edge Case & Validasi Data (ringkasan)
- Scan QR gagal: token tidak dikenal/expired → tampilkan error, tidak ubah status penerima.
- Flagging: digunakan jika data mismatch, dokumen kurang, atau penerima tidak berhak.
- Periode tidak aktif: blok operasi scan/validasi/cetak untuk periode tersebut.
- Duplikasi penerima pada upload: harus ditangani via verifikasi datalist sebelum distribusi.
- Cetak massal: pastikan filter (all/kecamatan) benar untuk mencegah distribusi salah sasaran.

### Proses Bank: Scan QR → Validasi → Flagging → Cetak Kartu
```plantuml
@startuml
start
:Login (role bank/teller/admin/kesra);
:Buka Scan QR (Livewire PeriodScanQrcode);
:Scan QR -> POST /apps/qr/scan-qr (Validateqr@idqr);
if (QR valid?) then (ya)
  :Tampilkan biodata penerima;
  :Opsional: Update biodata -> POST /apps/biodata-save/{id};
  :Validasi status -> POST /apps/validasi-save/{id};
  :Flagging route /apps/qr/validate/{id}/{periode};
  if (Perlu cetak?) then (ya)
    :Cetak kartu single /apps/penerima/bantuan/kartu/{UserId};
    :Cetak kartu all /apps/penerima/bantuan/kartuall;
    :Cetak kartu per kecamatan (post kartukec);
  endif
  :Tanda terima (post tandaterima);
else (tidak)
  :Tampilkan error QR;
endif
stop
@enduml
```

### Proses Pemenangan (Validator)
```plantuml
@startuml
start
:Login (role validator);
:Lihat periode (PeriodDatalistBank);
:Buka dashboard periode (ViewDashboard);
:Kelola pemenangan (datalist -> create/edit);
:Upload bukti pemenangan;
:Generate laporan pemenangan;
stop
@enduml
```

### Proses Cetak Kartu & Tanda Terima (Unit/Bank/Admin)
```plantuml
@startuml
start
:Login (role unit/bank/admin/kesra/teller);
:Buka datalist penerima bantuan;
if (Cetak single?) then (ya)
  :GET /apps/penerima/bantuan/kartu/{UserId};
endif
if (Cetak semua?) then (ya)
  :GET /apps/penerima/bantuan/kartuall;
endif
if (Cetak per kecamatan?) then (ya)
  :POST /apps/penerima/bantuan/kartukec;
endif
:Input/Upload tanda terima (POST tandaterima);
stop
@enduml
```

## Entitas Kunci & Field Utama (ringkasan)
- **Users**: id, name, email, password, role_id/permissions, is_active, unit/bank references.
- **Roles/Permissions**: role name (admin, kesra, bank, teller, validator, unit) dan mapping permission (Spatie).
- **Periode**: id, nama_periode, tahun, status (aktif/selesai), tanggal mulai/akhir, kuota, skema_id.
- **Skema Bantuan**: id, nama_skema, deskripsi, nominal/benefit, kriteria.
- **Penerima**: id, nama, NIK, alamat, kelurahan/kecamatan, bank_id, status verifikasi, kontak.
- **Penerima Bantuan (relasi periode)**: periode_id, penerima_id, status (pending/approved/distributed/flagged), qr_token, bukti/berkas.
- **Teller/Staff Bank**: user_id, bank_id, status aktif, assignment periode.
- **Pemenangan (Validator)**: periode_id, penerima_id, status menang/ditolak, bukti (path file), catatan, tanggal verifikasi.
- **Tanda Terima / Cetak Kartu**: nomor tanda terima, penerima_id, periode_id, lokasi (kecamatan), timestamp cetak, petugas.

Catatan: Field rinci mengikuti skema database; daftar di atas menyorot kolom kunci yang terlibat pada alur scan, validasi, flagging, dan pencetakan.

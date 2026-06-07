# TODO - Perbaikan: “Ganti Menu” tidak masuk ke Dashboard & History

-   [x] Identifikasi flow penyimpanan: menu.select menyimpan ke `menu_recommendations`, sedangkan history baru berubah saat `menu.confirm` memanggil `FoodHistory::create`.
-   [ ] Pilih opsi perbaikan:
    -   [ ] Opsi A: setelah `selectMenu`, langsung buat `FoodHistory` (history ikut terisi tanpa perlu klik konfirmasi terpisah).
    -   [ ] Opsi B: pastikan halaman dashboard merender tombol konfirmasi (`confirm-planned-btn`) sehingga event JS `.confirm-planned-btn` terpanggil.
-   [x] Implementasi perubahan di `app/Http/Controllers/User/MenuController.php` (untuk Opsi A) atau di `resources/views/user/dashboard.blade.php` (untuk Opsi B).
-   [ ] Jalankan pengujian manual:
    -   [ ] Klik “Ganti Menu” dari timeline reminder
    -   [ ] Klik “Pilih Menu”
    -   [ ] Pastikan status timeline berubah menjadi completed dan data muncul di halaman history.
-   [ ] Pastikan tidak ada error CSRF / route mismatch.

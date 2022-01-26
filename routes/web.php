<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/aset', 'HomeController@aset')->name('home.aset');



// DATA MASTER

Route::get('/daftar-stock', 'Master\StockBarang@index')->name('stock.index');

Route::get('/daftar-konsumen', 'Master\Konsumen@index')->name('konsumen.index');
Route::post('/daftar-konsumen', 'Master\Konsumen@store')->name('konsumen.store');
Route::patch('/daftar-konsumen', 'Master\Konsumen@update')->name('konsumen.update');
Route::put('/daftar-konsumen', 'Master\Konsumen@detail')->name('konsumen.detail');
Route::delete('/daftar-konsumen', 'Master\Konsumen@destroy')->name('konsumen.destroy');

Route::get('/daftar-karyawan', 'Master\Karyawan@index')->name('karyawan.index');
Route::post('/daftar-karyawan', 'Master\Karyawan@store')->name('karyawan.store');
Route::patch('/daftar-karyawan', 'Master\Karyawan@update')->name('karyawan.update');
Route::put('/daftar-karyawan', 'Master\Karyawan@detail')->name('karyawan.detail');
Route::delete('/daftar-karyawan', 'Master\Karyawan@destroy')->name('karyawan.destroy');

Route::get('/daftar-supplier', 'Master\Supplier@index')->name('supplier.index');
Route::post('/daftar-supplier', 'Master\Supplier@store')->name('supplier.store');
Route::patch('/daftar-supplier', 'Master\Supplier@update')->name('supplier.update');
Route::put('/daftar-supplier', 'Master\Supplier@detail')->name('supplier.detail');
Route::delete('/daftar-supplier', 'Master\Supplier@destroy')->name('supplier.destroy');

Route::get('/daftar-produk', 'Master\Produk@index')->name('produk.index');
Route::post('/daftar-produk', 'Master\Produk@store')->name('produk.store');
Route::patch('/daftar-produk', 'Master\Produk@update')->name('produk.update');
Route::delete('/daftar-produk', 'Master\Produk@destroy')->name('produk.destroy');

Route::get('/daftar-produkjual', 'Master\ProdukJual@index')->name('produkjual.index');
Route::post('/daftar-produkjual', 'Master\ProdukJual@store')->name('produkjual.store');
Route::patch('/daftar-produkjual', 'Master\ProdukJual@update')->name('produkjual.update');
Route::delete('/daftar-produkjual', 'Master\ProdukJual@destroy')->name('produkjual.destroy');

Route::get('/daftar-tipe', 'Master\Tipe@index')->name('tipe.index');
Route::post('/daftar-tipe', 'Master\Tipe@store')->name('tipe.store');
Route::patch('/daftar-tipe', 'Master\Tipe@update')->name('tipe.update');
Route::delete('/daftar-tipe', 'Master\Tipe@destroy')->name('tipe.destroy');

Route::get('/daftar-satuan', 'Master\Satuan@index')->name('satuan.index');
Route::post('/daftar-satuan', 'Master\Satuan@store')->name('satuan.store');
Route::patch('/daftar-satuan', 'Master\Satuan@update')->name('satuan.update');
Route::delete('/daftar-satuan', 'Master\Satuan@destroy')->name('satuan.destroy');

Route::get('/metode-pembayaran', 'Master\PaymentMethod@index')->name('payment.index');
Route::post('/metode-pembayaran', 'Master\PaymentMethod@store')->name('payment.store');
Route::patch('/metode-pembayaran', 'Master\PaymentMethod@update')->name('payment.update');
Route::delete('/metode-pembayaran', 'Master\PaymentMethod@destroy')->name('payment.destroy');

Route::get('/daftar-kandang', 'Master\Kandang@index')->name('kandang.index');
Route::post('/daftar-kandang', 'Master\Kandang@store')->name('kandang.store');
Route::patch('/daftar-kandang', 'Master\Kandang@update')->name('kandang.update');
Route::put('/daftar-kandang', 'Master\Kandang@bangunan')->name('kandang.bangunan');
Route::delete('/daftar-kandang', 'Master\Kandang@destroy')->name('kandang.destroy');

Route::get('/daftar-strain', 'Master\Strain@index')->name('strain.index');
Route::post('/daftar-strain', 'Master\Strain@store')->name('strain.store');
Route::patch('/daftar-strain', 'Master\Strain@update')->name('strain.update');
Route::put('/daftar-strain', 'Master\Strain@standar')->name('strain.standar');
Route::delete('/daftar-strain', 'Master\Strain@destroy')->name('strain.destroy');

// AKTIVITAS AYAM
Route::get('/jurnal-setoranmodal', 'Jurnal\SetoranModal@index')->name('setormodal.index');
Route::post('/jurnal-setoranmodal', 'Jurnal\SetoranModal@store')->name('setormodal.store');

Route::get('/pengeluaran-lain', 'Jurnal\PengeluaranLain@index')->name('keluarlain.index');
Route::get('/pengeluaran-lain_riwayat', 'Jurnal\PengeluaranLain@riwayat')->name('keluarlain.riwayat');
Route::post('/pengeluaran-lain', 'Jurnal\PengeluaranLain@store')->name('keluarlain.store');
Route::patch('/pengeluaran-lain', 'Jurnal\PengeluaranLain@update')->name('keluarlain.update');
Route::delete('/pengeluaran-lain', 'Jurnal\PengeluaranLain@destroy')->name('keluarlain.destroy');

Route::get('/mutasi', 'Jurnal\Mutasi@index')->name('mutasi.index');
Route::get('/mutasi/search', 'Jurnal\Mutasi@search_produk')->name('mutasi.search');
Route::post('/mutasi', 'Jurnal\Mutasi@store')->name('mutasi.store');

Route::get('/purchasing-order', 'Transaksi\PurchasingOrder@index')->name('purchasing.index');
Route::get('/purchasing-order-index', 'Transaksi\PurchasingOrder@show_index')->name('purchasing.show_index');
Route::post('/purchasing-order', 'Transaksi\PurchasingOrder@store')->name('purchasing.store');
Route::patch('/purchasing-order', 'Transaksi\PurchasingOrder@pdf')->name('purchasing.pdf');
Route::delete('/purchasing-order', 'Transaksi\PurchasingOrder@destroy')->name('purchasing.destroy');
Route::get('/purchasing-order/{id}', 'Transaksi\PurchasingOrder@detailpdf')->name('purchasing.detailpdf');

Route::get('/pembayaran-purchase', 'Transaksi\PembayaranPurchase@index')->name('paypurchase.index');
Route::post('/pembayaran-purchase', 'Transaksi\PembayaranPurchase@store')->name('paypurchase.store');
Route::get('/pembayaran-purchase/{id}', 'Transaksi\PembayaranPurchase@pdf')->name('paypurchase.pdf');

Route::get('/delivery-order', 'Transaksi\DeliveryOrder@index')->name('delivery.index');
Route::get('/delivery-order/search', 'Transaksi\DeliveryOrder@search')->name('delivery.search');
Route::get('/get_kandang', 'Transaksi\DeliveryOrder@get_kandang')->name('delivery.get_kandang');
Route::get('/get_strain', 'Transaksi\DeliveryOrder@get_strain')->name('delivery.get_strain');
Route::get('/get_produk', 'Transaksi\DeliveryOrder@produk_cek')->name('delivery.produk_cek');
Route::post('/delivery-order', 'Transaksi\DeliveryOrder@store')->name('delivery.store');
Route::get('/delivery-order/input', 'Transaksi\DeliveryOrder@input')->name('delivery.input');
Route::get('/delivery-order/purchase', 'Transaksi\DeliveryOrder@purchase')->name('delivery.purchase');
Route::get('/delivery-order/daftar', 'Transaksi\DeliveryOrder@daftar')->name('delivery.daftar');
Route::post('/delivery-order/daftar', 'Transaksi\DeliveryOrder@update')->name('delivery.update');

Route::get('/jurnal-angkatan', 'Jurnal\Angkatan@index')->name('angkatanayam.index');
Route::post('/jurnal-angkatan', 'Jurnal\Angkatan@store')->name('angkatanayam.store');
Route::patch('/jurnal-angkatan', 'Jurnal\Angkatan@destroy')->name('angkatanayam.destroy');
Route::post('/import-record', 'Jurnal\Angkatan@excel')->name('import.record');
Route::post('/edit-record', 'Jurnal\Angkatan@edit_record')->name('edit.record');
Route::post('/record_table', 'Jurnal\Angkatan@edit_record')->name('edit.record');
Route::get('/jurnal-angkatan/table/{id}', 'Jurnal\Angkatan@table')->name('table.record');

// TRANSAKSI

Route::get('/jurnal-penjualan', 'Transaksi\JualAyam@index')->name('penjualan.index');
Route::post('/jurnal-penjualan', 'Transaksi\JualAyam@store')->name('penjualan.store');
Route::patch('/jurnal-penjualan', 'Transaksi\JualAyam@update')->name('penjualan.update');
Route::put('/jurnal-penjualan', 'Transaksi\JualAyam@hapus')->name('penjualan.hapus');
Route::delete('/jurnal-penjualan', 'Transaksi\JualAyam@destroy')->name('penjualan.destroy');
Route::get('/jurnal-penjualan/{id}', 'Transaksi\JualAyam@invoice')->name('penjualan.invoice');

Route::get('/jurnal-penjualanlain', 'Transaksi\JualLain@index')->name('juallain.index');
Route::post('/jurnal-penjualanlain', 'Transaksi\JualLain@store')->name('juallain.store');
Route::patch('/jurnal-penjualanlain', 'Transaksi\JualLain@update')->name('juallain.update');
Route::put('/jurnal-penjualanlain', 'Transaksi\JualLain@hapus')->name('juallain.hapus');
Route::delete('/jurnal-penjualanlain', 'Transaksi\JualLain@destroy')->name('juallain.destroy');
Route::get('/jurnal-penjualanlain/{id}', 'Transaksi\JualLain@invoice')->name('juallain.invoice');

Route::get('/jurnal-penggajian', 'Transaksi\Gaji@index')->name('gaji.index');
Route::post('/jurnal-penggajian', 'Transaksi\Gaji@store')->name('gaji.store');
Route::delete('/jurnal-penggajian', 'Transaksi\Gaji@destroy')->name('gaji.destroy');

Route::get('/jurnal-cashbon', 'Jurnal\Bon@index')->name('cashbon.index');
Route::post('/jurnal-cashbon', 'Jurnal\Bon@store')->name('cashbon.store');

Route::get('/pembelian', 'Transaksi\Pembelian@index')->name('pembelian.index');
Route::post('/pembelian', 'Transaksi\Pembelian@store')->name('pembelian.store');
Route::delete('/pembelian', 'Transaksi\Pembelian@destroy')->name('pembelian.destroy');
Route::get('/pembelian/setup', 'Transaksi\Pembelian@setup')->name('pembelian.setup');
Route::post('/pembelian/setup', 'Transaksi\Pembelian@storesetup')->name('pembelian.storesetup');

Route::get('/mutasi-kas', 'Jurnal\MutasiKas@index')->name('mutasikas.index');
Route::post('/mutasi-kas', 'Jurnal\MutasiKas@store')->name('mutasikas.store');
Route::delete('/mutasi-kas', 'Jurnal\MutasiKas@destroy')->name('mutasikas.destroy');

Route::get('/cut-off', 'Jurnal\Cutoff@index')->name('cutoff.index');
Route::post('/cut-off', 'Jurnal\Cutoff@store')->name('cutoff.store');
Route::patch('/cut-off', 'Jurnal\Cutoff@update')->name('cutoff.patch');
Route::delete('/cut-off', 'Jurnal\Cutoff@destroy')->name('cutoff.destroy');

Route::get('/hak-akses', 'Master\HakAkses@index')->name('hakakses.index');
Route::post('/hak-akses', 'Master\HakAkses@store')->name('hakakses.store');
Route::patch('/hak-akses', 'Master\HakAkses@update')->name('hakakses.update');
Route::delete('/hak-akses', 'Master\HakAkses@destroy')->name('hakakses.destroy');

Route::get('/arus-barang', 'Report\ArusBarang@index')->name('arusbarang.index');

Route::get('/laba-rugi', 'Report\LabaRugi@index')->name('labarugi.index');

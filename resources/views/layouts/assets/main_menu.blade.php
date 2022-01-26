<div class="main_menu">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="navbar-collapse align-items-center collapse" id="navbar">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown"><a href="{{ route('index') }}" class="nav-link"><i class="icon-home"></i>Dashboard</a></li>

                    @if ((Auth::user()->type == 'admin') OR Akun::setIjin('Daftar Karyawan') OR Akun::setIjin('Daftar Konsumen') OR Akun::setIjin('Daftar Supplier') OR Akun::setIjin('Daftar Produk') OR Akun::setIjin('Daftar Kandang') OR Akun::setIjin('Daftar Strain') OR Akun::setIjin('Daftar Tipe') OR Akun::setIjin('Daftar Metode Pembayaran'))
                        <li class="nav-item dropdown">
                            <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="icon-grid"></i> <span>Master</span></a>
                            <ul class="dropdown-menu animated bounceIn">
                                @if (Auth::user()->type == 'admin') <li><a href="{{ route('hakakses.index') }}">Hak Akses</a></li> @endif
                                @if (Akun::setIjin('Daftar Karyawan')) <li><a href="{{ route('karyawan.index') }}">Daftar Karyawan</a></li> @endif
                                @if (Akun::setIjin('Daftar Konsumen')) <li><a href="{{ route('konsumen.index') }}">Daftar Konsumen</a></li> @endif
                                @if (Akun::setIjin('Daftar Supplier')) <li><a href="{{ route('supplier.index') }}">Daftar Supplier</a></li> @endif
                                @if (Akun::setIjin('Daftar Produk')) <li><a href="{{ route('produk.index') }}">Daftar Produk</a></li> @endif
                                @if (Akun::setIjin('Daftar Kandang')) <li><a href="{{ route('kandang.index') }}">Daftar Kandang</a></li> @endif
                                @if (Akun::setIjin('Daftar Strain')) <li><a href="{{ route('strain.index') }}">Daftar Strain</a></li> @endif
                                @if (Akun::setIjin('Daftar Tipe')) <li><a href="{{ route('tipe.index') }}">Daftar Tipe</a></li> @endif
                                @if (Akun::setIjin('Daftar Satuan')) <li><a href="{{ route('satuan.index') }}">Daftar Satuan</a></li> @endif
                                @if (Akun::setIjin('Daftar Metode Pembayaran')) <li><a href="{{ route('payment.index') }}">Daftar Metode Pembayaran</a></li> @endif
                            </ul>
                        </li>
                    @endif

                    @if (Akun::setIjin('Purchasing Order') OR Akun::setIjin('Pembayaran Purchase') OR Akun::setIjin('Delivery Order'))
                    <li class="nav-item dropdown">
                        <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> <span>Transaksi</span></a>
                        <ul class="dropdown-menu animated bounceIn">
                            @if (Akun::setIjin('Purchasing Order')) <li><a href="{{ route('purchasing.index') }}">Purchasing Order</a></li> @endif
                            @if (Akun::setIjin('Pembayaran Purchase')) <li><a href="{{ route('paypurchase.index') }}">Pembayaran Purchase</a></li> @endif
                            @if (Akun::setIjin('Delivery Order')) <li><a href="{{ route('delivery.index') }}">Delivery Order</a></li> @endif
                        </ul>
                    </li>
                    @endif

                    @if (Akun::setIjin('Jurnal Mutasi') OR Akun::setIjin('Jurnal Angkatan Ayam') OR Akun::setIjin('Setoran Modal') OR Akun::setIjin('Pengeluaran Lain') OR Akun::setIjin('Jurnal Mutasi Kas') OR Akun::setIjin('Jurnal Penjualan Ayam') OR Akun::setIjin('Jurnal Penjualan Lain') OR Akun::setIjin('Jurnal Cashbon') OR Akun::setIjin('Jurnal Penggajian') OR Akun::setIjin('Jurnal Pembelian') OR Akun::setIjin('Jurnal Cut Off'))
                    <li class="nav-item dropdown">
                        <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="icon-wallet"></i> <span>Jurnal</span></a>
                        <ul class="dropdown-menu animated bounceIn">
                            @if (Akun::setIjin('Jurnal Mutasi')) <li><a href="{{ route('mutasi.index') }}">Jurnal Mutasi</a></li> @endif
                            @if (Akun::setIjin('Jurnal Angkatan Ayam')) <li><a href="{{ route('angkatanayam.index') }}">Jurnal Angkatan Ayam</a></li> @endif
                            @if (Akun::setIjin('Setoran Modal')) <li><a href="{{ route('setormodal.index') }}">Setoran Modal</a></li> @endif
                            @if (Akun::setIjin('Pengeluaran Lain')) <li><a href="{{ route('keluarlain.index') }}">Pengeluaran Lain</a></li> @endif
                            @if (Akun::setIjin('Jurnal Mutasi Kas')) <li><a href="{{ route('mutasikas.index') }}">Jurnal Mutasi Kas</a></li> @endif
                            @if (Akun::setIjin('Jurnal Penjualan Ayam')) <li><a href="{{ route('penjualan.index') }}">Jurnal Penjualan Ayam</a></li> @endif
                            @if (Akun::setIjin('Jurnal Penjualan Lain')) <li><a href="{{ route('juallain.index') }}">Jurnal Penjualan Lain</a></li> @endif
                            @if (Akun::setIjin('Jurnal Cashbon')) <li><a href="{{ route('cashbon.index') }}">Jurnal Cashbon</a></li> @endif
                            @if (Akun::setIjin('Jurnal Penggajian')) <li><a href="{{ route('gaji.index') }}">Jurnal Penggajian</a></li> @endif
                            @if (Akun::setIjin('Jurnal Pembelian')) <li><a href="{{ route('pembelian.index') }}">Jurnal Pembelian</a></li> @endif
                            @if (Akun::setIjin('Jurnal Cut Off')) <li><a href="{{ route('cutoff.index') }}">Jurnal Cut Off</a></li> @endif
                        </ul>
                    </li>
                    @endif

                    @if (Akun::setIjin('Report Arus Barang') OR Akun::setIjin('Report Laba Rugi'))
                    <li class="nav-item dropdown">
                        <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart-o"></i> <span>Report</span></a>
                        <ul class="dropdown-menu animated bounceIn">
                            @if (Akun::setIjin('Report Arus Barang')) <li><a href="{{ route('arusbarang.index') }}">Arus Barang</a></li> @endif
                            @if (Akun::setIjin('Report Laba Rugi')) <li><a href="{{ route('labarugi.index') }}">Laba Rugi</a></li> @endif
                        </ul>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </nav>
</div>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DompetMahasiswa - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E8EEF5; color: #334155; }
        .neu-card {
            background: linear-gradient(145deg, #f3f7fc, #e2e8f0);
            box-shadow: 10px 10px 20px #cbd5e1, -10px -10px 20px #ffffff;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
        .neu-inset {
            background: #E8EEF5;
            box-shadow: inset 4px 4px 8px #cfd8e3, inset -4px -4px 8px #ffffff;
            border-radius: 16px;
        }
        .neu-icon-btn {
            background: linear-gradient(145deg, #f5f8fc, #dfe6ef);
            box-shadow: 5px 5px 10px #cbd5e1, -5px -5px 10px #ffffff;
            border-radius: 18px;
            transition: all 0.2s ease;
        }
        .neu-icon-btn:hover, .neu-icon-btn.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            box-shadow: 5px 5px 14px rgba(37, 99, 235, 0.35), -3px -3px 8px #ffffff;
        }
        .neu-btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.5);
            border-radius: 9999px;
            transition: transform 0.15s ease;
        }
        .neu-btn-blue:hover { transform: translateY(-2px); }
        .neu-line-input {
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #cbd5e1;
            border-radius: 0;
            padding: 10px 4px;
            transition: border-color 0.2s;
        }
        .neu-line-input:focus { outline: none; border-bottom-color: #2563eb; }
    </style>
</head>
<body class="min-h-screen py-5 sm:py-8 px-4 sm:px-6 lg:px-8">

    @php
        $usagePercent = $totalIncome > 0 ? min(round(($totalExpense / $totalIncome) * 100), 100) : 0;
    @endphp

    <div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">
        
        <!-- Header & Navigasi -->
        <header class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full md:w-auto flex justify-between items-center">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">Dompet<span class="text-blue-600">Mahasiswa</span></h1>
                    <p class="text-xs text-slate-500 mt-0.5">Halo, <span class="font-semibold text-slate-700">{{ Auth::user()->username }}</span></p>
                </div>

                <!-- Menu Profile & Logout Mobile -->
                <div class="flex items-center gap-2 md:hidden">
                    <a href="{{ route('profile') }}" class="neu-icon-btn p-2.5 text-slate-600" title="Profile">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="neu-icon-btn p-2.5 text-rose-500" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Baris Tombol Filter, Download PDF, Profile & Logout -->
            <div class="grid grid-cols-4 md:flex items-center gap-2.5 sm:gap-3 w-full md:w-auto">
                <a href="{{ route('transactions.index') }}" class="neu-icon-btn h-14 md:w-16 md:h-16 flex flex-col items-center justify-center text-[11px] sm:text-xs font-medium {{ !request('type') ? 'active' : 'text-slate-600' }}">
                    <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Semua
                </a>
                <a href="{{ route('transactions.index', ['type' => 'income']) }}" class="neu-icon-btn h-14 md:w-16 md:h-16 flex flex-col items-center justify-center text-[11px] sm:text-xs font-medium {{ request('type') == 'income' ? 'active' : 'text-slate-600' }}">
                    <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Masuk
                </a>
                <a href="{{ route('transactions.index', ['type' => 'expense']) }}" class="neu-icon-btn h-14 md:w-16 md:h-16 flex flex-col items-center justify-center text-[11px] sm:text-xs font-medium {{ request('type') == 'expense' ? 'active' : 'text-slate-600' }}">
                    <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    Keluar
                </a>

                <!-- Tombol Rekap PDF -->
                <button type="button" onclick="openPdfModal()" class="neu-icon-btn h-14 md:w-16 md:h-16 flex flex-col items-center justify-center text-[11px] sm:text-xs font-medium text-blue-600">
                    <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    PDF
                </button>

                <!-- Menu Profile Desktop -->
                <a href="{{ route('profile') }}" class="hidden md:flex neu-icon-btn w-16 h-16 flex-col items-center justify-center text-xs font-medium text-slate-600">
                    <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile
                </a>

                <!-- Tombol Logout Desktop -->
                <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                    @csrf
                    <button type="submit" class="neu-icon-btn w-16 h-16 flex flex-col items-center justify-center text-xs font-medium text-rose-500 hover:!bg-rose-500 hover:!text-white">
                        <svg class="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Notifikasi -->
        @if(session('success'))
            <div class="neu-card px-5 py-3.5 text-xs sm:text-sm font-medium text-blue-700 flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0"></span>
            </div>
        @endif

        @if($errors->any())
            <div class="neu-card px-5 py-3.5 text-xs text-rose-600">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Grid Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

            <!-- KOLOM KIRI: Form Catat Transaksi -->
            <div class="lg:col-span-4 neu-card p-6 sm:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-5">Catat Transaksi</h2>

                <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf
                    
                    <div class="neu-inset p-1.5 grid grid-cols-2 gap-2 text-xs font-semibold">
                        <label class="cursor-pointer text-center">
                            <input type="radio" name="type" value="expense" class="peer hidden" checked>
                            <div class="py-2 rounded-xl text-slate-500 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-md transition">
                                Pengeluaran
                            </div>
                        </label>
                        <label class="cursor-pointer text-center">
                            <input type="radio" name="type" value="income" class="peer hidden">
                            <div class="py-2 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-md transition">
                                Pemasukan
                            </div>
                        </label>
                    </div>

                    <div>
                        <input type="text" name="title" placeholder="Keterangan" class="neu-line-input w-full text-sm text-slate-700 placeholder-slate-400" required>
                    </div>

                    <div>
                        <input type="number" name="amount" placeholder="Nominal" min="1" class="neu-line-input w-full text-sm text-slate-700 placeholder-slate-400" required>
                    </div>

                    <div>
                        <input type="text" name="category" list="category-list" placeholder="Kategori" class="neu-line-input w-full text-sm text-slate-700 placeholder-slate-400" required>
                        <datalist id="category-list">
                            @foreach($userCategories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="neu-line-input w-full text-xs text-slate-500" required>
                    </div>

                    <div class="pt-3 flex justify-center">
                        <button type="submit" class="neu-btn-blue w-full sm:w-auto text-white text-sm font-semibold px-10 py-3.5 tracking-wide">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- KOLOM KANAN: Statistik, Grafik & Riwayat Transaksi -->
            <div class="lg:col-span-8 space-y-6 sm:space-y-8">

                <!-- Progress Bar Rasio Anggaran -->
                <div class="neu-card p-5 sm:p-6">
                    <div class="flex justify-between text-xs font-semibold text-slate-500 mb-3">
                        <span>Rasio Pengeluaran</span>
                        <span class="text-blue-600 font-bold">{{ $usagePercent }}% Terpakai</span>
                    </div>
                    <div class="neu-inset h-4 w-full p-1 rounded-full overflow-hidden flex items-center">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-rose-500 transition-all duration-500" style="width: {{ $usagePercent }}%"></div>
                    </div>
                </div>

                <!-- Kartu Saldo & Grafik Arus Kas (Biru & Merah) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    <div class="neu-card p-6 sm:p-7 flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Sisa Saldo</p>
                            <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 mt-2 break-words">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </h3>
                            <p class="text-xs font-semibold text-blue-600 mt-1">
                                ▲ {{ 100 - $usagePercent }}% Dana Tersisa
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-200/70 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center text-slate-500">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 mr-2"></span> Pemasukan
                                </span>
                                <span class="font-bold text-blue-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center text-slate-500">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mr-2"></span> Pengeluaran
                                </span>
                                <span class="font-bold text-rose-500">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="neu-card p-6 sm:p-7 flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-base sm:text-lg font-bold text-slate-800">Arus Kas</h4>
                                <p class="text-[11px] text-slate-400">Pemasukan (Biru) vs Pengeluaran (Merah)</p>
                            </div>
                            <div class="flex space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            </div>
                        </div>
                        <div class="h-36 w-full">
                            <canvas id="financeWaveChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Transaksi -->
                <div class="neu-card p-5 sm:p-7">
                    <h3 class="text-base font-bold text-slate-800 mb-4 sm:mb-5">Riwayat Transaksi</h3>
                    <div class="space-y-3">
                        @forelse($transactions as $item)
                            <div class="neu-inset px-4 sm:px-5 py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                                <div class="flex items-start sm:items-center space-x-3">
                                    <span class="w-3 h-3 rounded-full mt-1 sm:mt-0 shrink-0 {{ $item->type == 'income' ? 'bg-blue-600' : 'bg-rose-500' }}"></span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $item->title }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $item->date->format('d M Y') }} • {{ $item->category }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end space-x-3 pt-1 sm:pt-0 border-t sm:border-t-0 border-slate-200/50">
                                    <span class="text-sm font-bold {{ $item->type == 'income' ? 'text-blue-600' : 'text-rose-500' }}">
                                        {{ $item->type == 'income' ? '+' : '-' }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </span>

                                    <div class="flex items-center space-x-2">
                                        <button type="button"
                                            onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title) }}', {{ $item->amount }}, '{{ $item->type }}', '{{ addslashes($item->category) }}', '{{ $item->date->format('Y-m-d') }}')"
                                            class="text-xs text-blue-600 hover:text-blue-800 p-1 transition" title="Edit">
                                            ✎
                                        </button>
                                        <form action="{{ route('transactions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-slate-400 hover:text-rose-500 p-1 transition" title="Hapus">✕</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-6">Belum ada transaksi tercatat.</p>
                        @endforelse
                    </div>

                    <div class="mt-5">
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Download Rekap PDF (Filter Jangka Hari atau Bulan) -->
    <div id="pdfModal" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="neu-card w-full max-w-md p-6 sm:p-8 relative">
            <button type="button" onclick="closePdfModal()" class="absolute right-6 top-6 text-slate-400 hover:text-slate-700 text-sm font-bold">✕</button>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Download Rekap PDF</h3>
            <p class="text-xs text-slate-400 mb-5">Pilih periode laporan keuangan yang ingin diunduh</p>

            <form action="{{ route('transactions.pdf') }}" method="GET" class="space-y-5">
                <!-- Toggle Pilihan Bulan atau Jangka Hari -->
                <div class="neu-inset p-1.5 grid grid-cols-2 gap-2 text-xs font-semibold">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="filter_mode" value="month" class="peer hidden" checked onchange="togglePdfFilter('month')">
                        <div class="py-2 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white transition">Per Bulan</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="filter_mode" value="day" class="peer hidden" onchange="togglePdfFilter('day')">
                        <div class="py-2 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white transition">Jangka Hari</div>
                    </label>
                </div>

                <!-- Input Bulan -->
                <div id="pdfMonthInput">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Pilih Bulan</label>
                    <input type="month" name="month" value="{{ date('Y-m') }}" class="neu-line-input w-full text-sm text-slate-700">
                </div>

                <!-- Input Jangka Hari -->
                <div id="pdfDayInput" class="hidden grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-01') }}" class="neu-line-input w-full text-xs text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ date('Y-m-d') }}" class="neu-line-input w-full text-xs text-slate-700">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-3">
                    <button type="button" onclick="closePdfModal()" class="px-4 py-2 text-xs font-semibold text-slate-500">Batal</button>
                    <button type="submit" onclick="setTimeout(closePdfModal, 500)" class="neu-btn-blue text-white text-xs font-semibold px-7 py-3">
                        Unduh PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Transaksi -->
    <div id="editModal" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="neu-card w-full max-w-md p-6 sm:p-8 relative">
            <button type="button" onclick="closeEditModal()" class="absolute right-6 top-6 text-slate-400 hover:text-slate-700 text-sm font-bold">✕</button>
            <h3 class="text-lg font-bold text-slate-800 mb-5">Edit Transaksi</h3>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="neu-inset p-1.5 grid grid-cols-2 gap-2 text-xs font-semibold">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="type" id="editTypeExpense" value="expense" class="peer hidden">
                        <div class="py-2 rounded-xl text-slate-500 peer-checked:bg-rose-500 peer-checked:text-white transition">Pengeluaran</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="type" id="editTypeIncome" value="income" class="peer hidden">
                        <div class="py-2 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white transition">Pemasukan</div>
                    </label>
                </div>

                <div>
                    <input type="text" name="title" id="editTitle" placeholder="Keterangan" class="neu-line-input w-full text-sm text-slate-700" required>
                </div>
                <div>
                    <input type="number" name="amount" id="editAmount" placeholder="Nominal" min="1" class="neu-line-input w-full text-sm text-slate-700" required>
                </div>
                <div>
                    <input type="text" name="category" id="editCategory" list="category-list" placeholder="Kategori" class="neu-line-input w-full text-sm text-slate-700" required>
                </div>
                <div>
                    <input type="date" name="date" id="editDate" class="neu-line-input w-full text-xs text-slate-500" required>
                </div>

                <div class="pt-3 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-xs font-semibold text-slate-500">Batal</button>
                    <button type="submit" class="neu-btn-blue text-white text-xs font-semibold px-7 py-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Chart.js (Pemasukan Biru & Pengeluaran Merah) & Modals -->
    <script>
        function openPdfModal() {
            const modal = document.getElementById('pdfModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closePdfModal() {
            const modal = document.getElementById('pdfModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        function togglePdfFilter(mode) {
            if (mode === 'day') {
                document.getElementById('pdfDayInput').classList.remove('hidden');
                document.getElementById('pdfMonthInput').classList.add('hidden');
            } else {
                document.getElementById('pdfMonthInput').classList.remove('hidden');
                document.getElementById('pdfDayInput').classList.add('hidden');
            }
        }

        function openEditModal(id, title, amount, type, category, date) {
            document.getElementById('editForm').action = `/transactions/${id}`;
            document.getElementById('editTitle').value = title;
            document.getElementById('editAmount').value = amount;
            document.getElementById('editCategory').value = category;
            document.getElementById('editDate').value = date;

            if (type === 'income') {
                document.getElementById('editTypeIncome').checked = true;
            } else {
                document.getElementById('editTypeExpense').checked = true;
            }

            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        const ctx = document.getElementById('financeWaveChart').getContext('2d');

        const gradientBlue = ctx.createLinearGradient(0, 0, 0, 140);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.45)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        const gradientRed = ctx.createLinearGradient(0, 0, 0, 140);
        gradientRed.addColorStop(0, 'rgba(244, 63, 94, 0.45)');
        gradientRed.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

        const chartLabels = {!! json_encode($chartLabels) !!};
        const incomeData = {!! json_encode($chartIncomes) !!};
        const expenseData = {!! json_encode($chartExpenses) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels.length ? chartLabels : ['Hari 1', 'Hari 2'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: incomeData.length ? incomeData : [0, 0],
                        borderColor: '#2563eb', // Biru
                        borderWidth: 2.5,
                        backgroundColor: gradientBlue,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#2563eb'
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenseData.length ? expenseData : [0, 0],
                        borderColor: '#f43f5e', // Merah
                        borderWidth: 2.5,
                        backgroundColor: gradientRed,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#f43f5e'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 9 }, color: '#94a3b8' }
                    },
                    y: { display: false }
                }
            }
        });
    </script>
</body>
</html>
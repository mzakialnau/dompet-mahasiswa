<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>DompetMahasiswa - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #DCE5F0;
            color: #1e293b;
            padding-bottom: env(safe-area-inset-bottom);
            -webkit-tap-highlight-color: transparent;
        }
        .neu-card {
            background: linear-gradient(145deg, #ffffff, #eef3f9);
            box-shadow: 10px 10px 22px rgba(163, 177, 198, 0.55), -10px -10px 22px rgba(255, 255, 255, 0.9);
            border-radius: 26px;
            border: 1.5px solid rgba(255, 255, 255, 0.95);
        }
        .neu-hero-blue {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 10px 12px 24px rgba(37, 99, 235, 0.35), -6px -6px 16px rgba(255, 255, 255, 0.7);
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .neu-inset {
            background: #E4ECF5;
            box-shadow: inset 4px 4px 8px rgba(163, 177, 198, 0.5), inset -4px -4px 8px rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .neu-pill-btn {
            background: linear-gradient(145deg, #ffffff, #e6edf5);
            box-shadow: 5px 5px 12px rgba(163, 177, 198, 0.5), -5px -5px 12px rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.9);
            transition: all 0.2s ease;
        }
        .neu-pill-btn:active { transform: scale(0.97); }
        .neu-btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.55);
            border-radius: 9999px;
            transition: all 0.15s ease;
        }
        .neu-btn-blue:hover { transform: translateY(-1px); }
        .neu-btn-blue:active { transform: scale(0.98); }
        .neu-line-input {
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #cbd5e1;
            border-radius: 0;
            padding: 11px 4px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        @media (min-width: 640px) {
            .neu-line-input { font-size: 14px; }
        }
        .neu-line-input:focus { outline: none; border-bottom-color: #2563eb; }
    </style>
</head>
<body class="min-h-screen py-5 sm:py-8 px-4 sm:px-6 lg:px-8">

    @php
        $usagePercent = $totalIncome > 0 ? min(round(($totalExpense / $totalIncome) * 100), 100) : 0;
    @endphp

    <div class="max-w-6xl mx-auto space-y-6 sm:space-y-7">
        
        <!-- 1. HEADER -->
        <header class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Dompet<span class="text-blue-600">Mahasiswa</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Halo, <span class="font-semibold text-slate-700">{{ Auth::user()->username }}</span></p>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3">
                <button type="button" onclick="openPdfModal()" class="neu-pill-btn h-11 px-3.5 sm:px-4 flex items-center gap-2 text-xs font-semibold text-blue-600 hover:bg-blue-50">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Rekap PDF</span>
                </button>

                <a href="{{ route('profile') }}" class="neu-pill-btn h-11 px-3.5 sm:px-4 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:text-blue-600" title="Profile">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="hidden sm:inline">Profile</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="neu-pill-btn h-11 px-3.5 sm:px-4 flex items-center gap-2 text-xs font-semibold text-rose-500 hover:text-rose-600" title="Logout">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Notifikasi -->
        @if(session('success'))
            <div class="neu-card px-5 py-3.5 text-xs sm:text-sm font-semibold text-blue-700 flex items-center justify-between">
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

        <!-- 2. HERO RINGKASAN KEUANGAN -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
            <!-- Kartu 1: Sisa Saldo -->
            <div class="neu-hero-blue p-6 text-white flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-blue-100">Sisa Saldo</span>
                    <span class="text-[11px] bg-white/20 px-2.5 py-0.5 rounded-full font-medium">
                        {{ 100 - $usagePercent }}% Tersisa
                    </span>
                </div>
                <div class="my-3">
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight break-words">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </h2>
                </div>
                <div>
                    <div class="flex justify-between text-[11px] text-blue-100 mb-1.5">
                        <span>Rasio Pemakaian</span>
                        <span class="font-bold text-white">{{ $usagePercent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-blue-950/35 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ $usagePercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Kartu 2: Total Pemasukan -->
            <div class="neu-card p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pemasukan</span>
                    <button type="button" onclick="quickSelectType('income')" title="Catat Pemasukan" class="w-9 h-9 rounded-xl bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white flex items-center justify-center font-bold text-base transition cursor-pointer shadow-sm">
                        +
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-2xl font-extrabold text-blue-600 break-words">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">Klik + untuk catat pemasukan</p>
                </div>
            </div>

            <!-- Kartu 3: Total Pengeluaran -->
            <div class="neu-card p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengeluaran</span>
                    <button type="button" onclick="quickSelectType('expense')" title="Catat Pengeluaran" class="w-9 h-9 rounded-xl bg-rose-100 hover:bg-rose-500 text-rose-500 hover:text-white flex items-center justify-center font-bold text-base transition cursor-pointer shadow-sm">
                        -
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-2xl font-extrabold text-rose-500 break-words">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">Klik - untuk catat pengeluaran</p>
                </div>
            </div>
        </section>

        <!-- 3. WORKSPACE UTAMA -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-7 items-start">

            <!-- KOLOM KIRI: Form Catat Transaksi -->
            <div id="formTransaksi" class="lg:col-span-4 lg:sticky lg:top-6 neu-card p-6 sm:p-7">
                <h2 class="text-lg font-bold text-slate-800 mb-5">Catat Transaksi</h2>

                <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="neu-inset p-1.5 grid grid-cols-2 gap-2 text-xs font-bold">
                        <label class="cursor-pointer text-center">
                            <input type="radio" name="type" id="radioExpense" value="expense" class="peer hidden" checked>
                            <div class="py-2.5 rounded-xl text-slate-500 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-md transition">
                                Pengeluaran
                            </div>
                        </label>
                        <label class="cursor-pointer text-center">
                            <input type="radio" name="type" id="radioIncome" value="income" class="peer hidden">
                            <div class="py-2.5 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-md transition">
                                Pemasukan
                            </div>
                        </label>
                    </div>

                    <div>
                        <input type="text" name="title" placeholder="Keterangan" class="neu-line-input w-full text-slate-700 placeholder-slate-400" required>
                    </div>

                    <div>
                        <input type="number" name="amount" inputmode="numeric" placeholder="Nominal" min="1" class="neu-line-input w-full text-slate-700 placeholder-slate-400" required>
                    </div>

                    <div>
                        <input type="text" name="category" list="category-list" placeholder="Kategori" class="neu-line-input w-full text-slate-700 placeholder-slate-400" required>
                        <datalist id="category-list">
                            @foreach($userCategories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="neu-line-input w-full text-slate-500" required>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="neu-btn-blue w-full text-white text-sm font-bold py-3.5 tracking-wide">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>

            <!-- KOLOM KANAN: Grafik Arus Kas & Riwayat Transaksi -->
            <div class="lg:col-span-8 space-y-6 sm:space-y-7">

                <!-- Grafik Arus Kas -->
                <div class="neu-card p-6 sm:p-7">
                    <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Grafik Arus Kas</h3>
                            <p class="text-xs text-slate-400">Perbandingan harian terbaru</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-semibold">
                            <span class="flex items-center text-slate-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 mr-1.5"></span> Pemasukan
                            </span>
                            <span class="flex items-center text-slate-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mr-1.5"></span> Pengeluaran
                            </span>
                        </div>
                    </div>
                    <div class="h-44 sm:h-48 w-full">
                        <canvas id="financeWaveChart"></canvas>
                    </div>
                </div>

                <!-- Riwayat Transaksi -->
                <div id="riwayatTransaksi" class="neu-card p-6 sm:p-7 scroll-mt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800">Riwayat Transaksi</h3>

                        <!-- Segmented Filter Control -->
                        <div class="neu-inset p-1 grid grid-cols-3 gap-1 text-xs font-semibold w-full sm:w-auto">
                            <a href="{{ route('transactions.index') }}#riwayatTransaksi" class="px-4 py-2 rounded-xl text-center transition {{ !request('type') ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-800' }}">
                                Semua
                            </a>
                            <a href="{{ route('transactions.index', ['type' => 'income']) }}#riwayatTransaksi" class="px-4 py-2 rounded-xl text-center transition {{ request('type') == 'income' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-800' }}">
                                Masuk
                            </a>
                            <a href="{{ route('transactions.index', ['type' => 'expense']) }}#riwayatTransaksi" class="px-4 py-2 rounded-xl text-center transition {{ request('type') == 'expense' ? 'bg-rose-500 text-white shadow' : 'text-slate-500 hover:text-slate-800' }}">
                                Keluar
                            </a>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @forelse($transactions as $item)
                            <div class="neu-inset px-4 sm:px-5 py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                                <div class="flex items-start sm:items-center space-x-3.5">
                                    <span class="w-3 h-3 rounded-full mt-1.5 sm:mt-0 shrink-0 {{ $item->type == 'income' ? 'bg-blue-600' : 'bg-rose-500' }}"></span>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $item->title }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $item->date->format('d M Y') }} • <span class="font-medium text-slate-600">{{ $item->category }}</span></p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end space-x-3 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-300/60">
                                    <span class="text-sm font-extrabold {{ $item->type == 'income' ? 'text-blue-600' : 'text-rose-500' }}">
                                        {{ $item->type == 'income' ? '+' : '-' }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </span>

                                    <div class="flex items-center space-x-1">
                                        <button type="button"
                                            onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title) }}', {{ $item->amount }}, '{{ $item->type }}', '{{ addslashes($item->category) }}', '{{ $item->date->format('Y-m-d') }}')"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-xs text-blue-600 hover:bg-blue-100/70 transition" title="Edit">
                                            ✎
                                        </button>
                                        <form action="{{ route('transactions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-xs text-slate-400 hover:text-rose-600 hover:bg-rose-100/70 transition" title="Hapus">✕</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="neu-inset py-10 text-center">
                                <p class="text-xs font-medium text-slate-400">Belum ada transaksi pada kategori ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-5">
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Download Rekap PDF -->
    <div id="pdfModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="neu-card w-full max-w-md p-6 sm:p-8 relative">
            <button type="button" onclick="closePdfModal()" class="absolute right-6 top-6 w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 text-sm font-bold">✕</button>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Download Rekap PDF</h3>
            <p class="text-xs text-slate-500 mb-5">Pilih periode laporan keuangan yang ingin diunduh</p>

            <form action="{{ route('transactions.pdf') }}" method="GET" class="space-y-5">
                <div class="neu-inset p-1.5 grid grid-cols-2 gap-2 text-xs font-bold">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="filter_mode" value="month" class="peer hidden" checked onchange="togglePdfFilter('month')">
                        <div class="py-2.5 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white transition">Per Bulan</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="filter_mode" value="day" class="peer hidden" onchange="togglePdfFilter('day')">
                        <div class="py-2.5 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white transition">Jangka Hari</div>
                    </label>
                </div>

                <div id="pdfMonthInput">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Bulan</label>
                    <input type="month" name="month" value="{{ date('Y-m') }}" class="neu-line-input w-full text-slate-700">
                </div>

                <div id="pdfDayInput" class="hidden grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-01') }}" class="neu-line-input w-full text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ date('Y-m-d') }}" class="neu-line-input w-full text-slate-700">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-3">
                    <button type="button" onclick="closePdfModal()" class="px-4 py-2.5 text-xs font-semibold text-slate-500">Batal</button>
                    <button type="submit" onclick="setTimeout(closePdfModal, 500)" class="neu-btn-blue text-white text-xs font-bold px-7 py-3">
                        Unduh PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Transaksi -->
    <div id="editModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="neu-card w-full max-w-md p-6 sm:p-8 relative">
            <button type="button" onclick="closeEditModal()" class="absolute right-6 top-6 w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-700 text-sm font-bold">✕</button>
            <h3 class="text-lg font-bold text-slate-800 mb-5">Edit Transaksi</h3>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="neu-inset p-1.5 grid grid-cols-2 gap-2 text-xs font-bold">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="type" id="editTypeExpense" value="expense" class="peer hidden">
                        <div class="py-2.5 rounded-xl text-slate-500 peer-checked:bg-rose-500 peer-checked:text-white transition">Pengeluaran</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="type" id="editTypeIncome" value="income" class="peer hidden">
                        <div class="py-2.5 rounded-xl text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white transition">Pemasukan</div>
                    </label>
                </div>

                <div>
                    <input type="text" name="title" id="editTitle" placeholder="Keterangan" class="neu-line-input w-full text-slate-700" required>
                </div>
                <div>
                    <input type="number" name="amount" id="editAmount" inputmode="numeric" placeholder="Nominal" min="1" class="neu-line-input w-full text-slate-700" required>
                </div>
                <div>
                    <input type="text" name="category" id="editCategory" list="category-list" placeholder="Kategori" class="neu-line-input w-full text-slate-700" required>
                </div>
                <div>
                    <input type="date" name="date" id="editDate" class="neu-line-input w-full text-slate-500" required>
                </div>

                <div class="pt-3 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-xs font-semibold text-slate-500">Batal</button>
                    <button type="submit" class="neu-btn-blue text-white text-xs font-bold px-7 py-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Chart.js & Modals -->
    <script>
        function quickSelectType(type) {
            if (type === 'income') {
                document.getElementById('radioIncome').checked = true;
            } else {
                document.getElementById('radioExpense').checked = true;
            }
            const formCard = document.getElementById('formTransaksi');
            formCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                formCard.querySelector('input[name="title"]').focus();
            }, 350);
        }

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

        const gradientBlue = ctx.createLinearGradient(0, 0, 0, 170);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        const gradientRed = ctx.createLinearGradient(0, 0, 0, 170);
        gradientRed.addColorStop(0, 'rgba(244, 63, 94, 0.35)');
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
                        borderColor: '#2563eb',
                        borderWidth: 2.5,
                        backgroundColor: gradientBlue,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3.5,
                        pointBackgroundColor: '#2563eb'
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenseData.length ? expenseData : [0, 0],
                        borderColor: '#f43f5e',
                        borderWidth: 2.5,
                        backgroundColor: gradientRed,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3.5,
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
                        ticks: { font: { size: 10 }, color: '#64748b' }
                    },
                    y: { display: false }
                }
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Akun - DompetMahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        }
        .neu-line-input {
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #cbd5e1;
            padding: 10px 4px;
            transition: border-color 0.2s;
        }
        .neu-line-input:focus { outline: none; border-bottom-color: #2563eb; }
        .neu-btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.5);
            border-radius: 9999px;
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4 sm:px-6">

    <div class="max-w-lg mx-auto space-y-6">
        <!-- Header Kembali -->
        <div class="flex items-center justify-between">
            <a href="{{ route('transactions.index') }}" class="neu-icon-btn px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-blue-600 flex items-center gap-2">
                ← Kembali ke Dashboard
            </a>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengaturan Profil</span>
        </div>

        @if(session('success'))
            <div class="neu-card px-5 py-3.5 text-xs font-semibold text-blue-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="neu-card px-5 py-3.5 text-xs text-rose-600">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Kartu Informasi & Edit Username -->
        <div class="neu-card p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center font-bold text-lg shadow-md">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $user->username }}</h2>
                        <p class="text-[11px] text-slate-400">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <button type="button" onclick="toggleEditName()" id="btnToggleEdit" class="neu-icon-btn px-4 py-2 text-xs font-semibold text-blue-600">
                    ✎ Edit Nama
                </button>
            </div>

            <!-- Form Edit Username (Default tersembunyi sampai tombol Edit diklik) -->
            <form id="editUsernameForm" action="{{ route('profile.update') }}" method="POST" class="hidden space-y-4 pt-4 border-t border-slate-200/70">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Username Baru</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="neu-line-input w-full text-sm text-slate-800" required>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="toggleEditName()" class="px-4 py-2 text-xs text-slate-500">Batal</button>
                    <button type="submit" class="neu-btn-blue text-white text-xs font-semibold px-6 py-2.5">Simpan Nama</button>
                </div>
            </form>
        </div>

        <!-- Zona Hapus Akun -->
        <div class="neu-card p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-rose-600">Hapus Akun</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Menghapus akun beserta seluruh catatan keuanganmu.</p>
                </div>
                <button type="button" onclick="toggleDeleteBox()" class="neu-icon-btn px-4 py-2 text-xs font-semibold text-rose-600">
                    Hapus
                </button>
            </div>

            <!-- Konfirmasi Password sebelum Hapus Akun -->
            <form id="deleteAccountForm" action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun secara permanen? Tindakan ini tidak bisa dibatalkan.')" class="hidden space-y-4 pt-5 mt-5 border-t border-slate-200/70">
                @csrf
                @method('DELETE')
                <div class="neu-inset p-4">
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Masukkan Password untuk Konfirmasi</label>
                    <input type="password" name="password" placeholder="Password akunmu" class="neu-line-input w-full text-sm text-slate-800" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleDeleteBox()" class="px-4 py-2 text-xs text-slate-500">Batal</button>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-6 py-2.5 rounded-full shadow-md transition">
                        Konfirmasi Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleEditName() {
            document.getElementById('editUsernameForm').classList.toggle('hidden');
        }
        function toggleDeleteBox() {
            document.getElementById('deleteAccountForm').classList.toggle('hidden');
        }
    </script>
</body>
</html>
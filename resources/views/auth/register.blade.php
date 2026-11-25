<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - DompetMahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E8EEF5; color: #334155; }
        .neu-card {
            background: linear-gradient(145deg, #f3f7fc, #e2e8f0);
            box-shadow: 14px 14px 28px #cbd5e1, -14px -14px 28px #ffffff;
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
        .neu-line-input {
            background: transparent;
            border: none;
            border-bottom: 1.5px solid #cbd5e1;
            padding: 12px 4px;
            transition: border-color 0.2s;
        }
        .neu-line-input:focus { outline: none; border-bottom-color: #2563eb; }
        .neu-btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            box-shadow: 0 10px 22px -5px rgba(37, 99, 235, 0.55);
            border-radius: 9999px;
            transition: transform 0.15s ease;
        }
        .neu-btn-blue:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-sm neu-card p-8 sm:p-10">
        <div class="mb-7">
            <h1 class="text-2xl font-bold text-slate-800">Sign Up</h1>
            <p class="text-xs text-slate-400 mt-1">Buat akun keuangan pribadimu</p>
        </div>

        @if($errors->any())
            <div class="mb-5 text-xs text-rose-600 bg-rose-100/70 px-4 py-2.5 rounded-2xl">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" class="neu-line-input w-full text-sm text-slate-700" required autofocus>
            </div>

            <div>
                <input type="password" name="password" placeholder="Password (min. 6 karakter)" class="neu-line-input w-full text-sm text-slate-700" required>
            </div>

            <div>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="neu-line-input w-full text-sm text-slate-700" required>
            </div>

            <div class="pt-4 flex flex-col items-center space-y-4">
                <button type="submit" class="neu-btn-blue w-full text-white text-sm font-semibold py-3.5 tracking-wide">
                    Sign Up
                </button>
                <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-blue-600 transition">
                    Sudah punya akun? <span class="font-semibold text-blue-600">Log In</span>
                </a>
            </div>
        </form>
    </div>

</body>
</html>
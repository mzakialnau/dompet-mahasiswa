<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Keuangan - {{ $user->username }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1e293b; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #1e3a8a; font-size: 20px; }
        .header p { margin: 4px 0 0; color: #64748b; font-size: 11px; }
        .summary { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary td { padding: 10px; border: 1px solid #cbd5e1; background-color: #f8fafc; }
        .label { font-size: 10px; color: #64748b; text-transform: uppercase; }
        .val-blue { font-size: 14px; font-weight: bold; color: #2563eb; }
        .val-red { font-size: 14px; font-weight: bold; color: #dc2626; }
        .val-dark { font-size: 14px; font-weight: bold; color: #0f172a; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background-color: #2563eb; color: #ffffff; text-align: left; padding: 8px; font-size: 11px; }
        table.data td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .text-right { text-align: right; }
        .income { color: #2563eb; font-weight: bold; }
        .expense { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DompetMahasiswa — Laporan Rekap Keuangan</h1>
        <p>Akun: <strong>{{ $user->username }}</strong> | Periode: <strong>{{ $periodLabel }}</strong> | Dicetak: {{ date('d M Y H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Pemasukan</div>
                <div class="val-blue">+ Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Total Pengeluaran</div>
                <div class="val-red">- Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Selisih / Saldo Periode</div>
                <div class="val-dark">Rp {{ number_format($balance, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->date->format('d/m/Y') }}</td>
                <td>{{ $row->title }}</td>
                <td>{{ $row->category }}</td>
                <td>{{ $row->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td class="text-right {{ $row->type === 'income' ? 'income' : 'expense' }}">
                    {{ $row->type === 'income' ? '+' : '-' }} Rp {{ number_format($row->amount, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #94a3b8;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
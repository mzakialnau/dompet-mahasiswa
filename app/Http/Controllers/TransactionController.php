<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->transactions()->orderBy('date', 'desc')->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(10);

        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = $user->transactions()->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $userCategories = $user->transactions()->select('category')->distinct()->pluck('category');

        // Data Grafik Arus Kas (7 tanggal transaksi terakhir, dipisah Pemasukan vs Pengeluaran)
        $chartDates = $user->transactions()
            ->select('date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->take(7)
            ->pluck('date')
            ->reverse()
            ->values();

        $chartLabels = [];
        $chartIncomes = [];
        $chartExpenses = [];

        foreach ($chartDates as $d) {
            $dateStr = Carbon::parse($d)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($d)->format('d M');
            $chartIncomes[] = (float) $user->transactions()->whereDate('date', $dateStr)->where('type', 'income')->sum('amount');
            $chartExpenses[] = (float) $user->transactions()->whereDate('date', $dateStr)->where('type', 'expense')->sum('amount');
        }

        return view('transactions.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'userCategories',
            'chartLabels',
            'chartIncomes',
            'chartExpenses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        Auth::user()->transactions()->create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dicatat!');
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    // === EXPORT REKAP PDF ===
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $filterMode = $request->input('filter_mode', 'month'); // 'day' atau 'month'
        $query = $user->transactions()->orderBy('date', 'asc');

        if ($filterMode === 'day') {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

            $query->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
            $periodLabel = $start->format('d M Y') . ' - ' . $end->format('d M Y');
        } else {
            $request->validate([
                'month' => 'required|date_format:Y-m',
            ]);
            $monthDate = Carbon::createFromFormat('Y-m', $request->month);

            $query->whereYear('date', $monthDate->year)
                  ->whereMonth('date', $monthDate->month);
            $periodLabel = 'Bulan ' . $monthDate->translatedFormat('F Y');
        }

        $records = $query->get();
        $totalIncome = $records->where('type', 'income')->sum('amount');
        $totalExpense = $records->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $pdf = Pdf::loadView('transactions.pdf', compact(
            'user',
            'records',
            'periodLabel',
            'totalIncome',
            'totalExpense',
            'balance'
        ));

        return $pdf->download('Rekap-Keuangan-' . $user->username . '-' . date('Ymd') . '.pdf');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['user', 'project']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('donor_email', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('bank_tran_id', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $query->orderBy(in_array($sortBy, ['amount', 'status', 'created_at']) ? $sortBy : 'created_at', $sortDir === 'asc' ? 'asc' : 'desc');

        $perPage = min((int)($request->per_page ?? 15), 100);
        return response()->json($query->paginate($perPage));
    }

    public function show($id)
    {
        $donation = Donation::with(['user', 'project'])->findOrFail($id);
        return response()->json($donation);
    }

    public function exportCsv(Request $request)
    {
        $query = Donation::with('project');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->orderBy('created_at', 'desc')->get();

        $filename = 'donations-' . now()->format('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['ID', 'Donor Name', 'Donor Email', 'Project', 'Amount', 'Currency', 'Transaction ID', 'Bank Transaction ID', 'Card Type', 'Status', 'Date'];

        $callback = function () use ($donations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($donations as $d) {
                fputcsv($file, [
                    $d->id,
                    $d->donor_name,
                    $d->donor_email,
                    $d->project?->title ?? 'N/A',
                    $d->amount,
                    $d->currency ?? 'BDT',
                    $d->transaction_id,
                    $d->bank_tran_id ?? '',
                    $d->card_type ?? '',
                    $d->status,
                    $d->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

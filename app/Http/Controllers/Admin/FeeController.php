<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\FeeInvoice;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of students, fee invoices, and statistics.
     */
    public function index(Request $request)
    {
        // Automatically ensure all enrolled students have an active tuition record
        $allStudents = StudentProfile::all();
        foreach ($allStudents as $student) {
            FeeInvoice::firstOrCreate(
                ['student_id' => $student->id, 'title' => 'Term 1 Tuition Fee'],
                [
                    'amount' => 350.00,
                    'due_date' => now()->addDays(30),
                    'status' => 'unpaid',
                ]
            );
        }

        $classrooms = Classroom::orderBy('name')->get();

        $query = FeeInvoice::with(['student.user', 'student.classroom'])->latest();

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Class
        if ($request->filled('classroom_id') && $request->classroom_id !== 'all') {
            $query->whereHas('student', function ($sub) use ($request) {
                $sub->where('classroom_id', $request->classroom_id);
            });
        }

        // Filter by Search (Name or Student Code)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('student', function ($sub) use ($search) {
                        $sub->where('student_code', 'like', "%{$search}%");
                    });
            });
        }

        $invoices = $query->paginate(6)->withQueryString();

        // Exact Real Database Stats Calculation
        $totalStudents = FeeInvoice::count();
        $totalBilled = FeeInvoice::sum('amount');

        $paidCount = FeeInvoice::where('status', 'paid')->count();
        $paidAmount = FeeInvoice::where('status', 'paid')->sum('amount');

        $partialCount = FeeInvoice::where('status', 'partial')->count();
        // 50% partial payment
        $partialAmount = FeeInvoice::where('status', 'partial')->get()->sum(fn ($inv) => (float) $inv->amount * 0.50);

        $unpaidCount = FeeInvoice::where('status', 'unpaid')->count();
        $totalCollected = $paidAmount + $partialAmount;
        $pendingAmount = $totalBilled - $totalCollected;

        $collectionRate = $totalBilled > 0 ? round(($totalCollected / $totalBilled) * 100, 1) : 0;

        return view('admin.fees.index', compact(
            'classrooms',
            'invoices',
            'totalStudents',
            'totalBilled',
            'totalCollected',
            'paidCount',
            'partialCount',
            'unpaidCount',
            'pendingAmount',
            'collectionRate'
        ));
    }

    /**
     * Update the fee payment status (paid, partial, unpaid).
     */
    public function updateStatus(Request $request, FeeInvoice $fee)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,partial,unpaid',
        ]);

        $status = $validated['status'];
        $fee->update([
            'status' => $status,
            'paid_date' => in_array($status, ['paid', 'partial']) ? now() : null,
        ]);

        $studentName = $fee->student?->user?->name ?? 'Student';
        $statusLabels = [
            'paid' => 'Fully Paid (100%)',
            'partial' => 'Partial Payment (50%)',
            'unpaid' => 'Unpaid (0%)',
        ];

        return back()->with('success', "Payment status for {$studentName} updated to {$statusLabels[$status]}.");
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:student_profiles,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
        ]);

        $invoice = FeeInvoice::create([
            'student_id' => $validated['student_id'],
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
            'status' => 'unpaid',
        ]);

        $studentName = $invoice->student?->user?->name ?? 'Student';

        return redirect()->route('admin.fees.index')->with(
            'success',
            "Tuition record for {$studentName} ($".number_format($invoice->amount, 2).') saved successfully.'
        );
    }
}

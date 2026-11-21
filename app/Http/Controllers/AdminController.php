<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Position;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = Employee::count();
        $totalCategories = Category::count();
        $totalTasks = Task::count();
        $recentEmployees = Employee::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalEmployees', 'totalCategories', 'totalTasks', 'recentEmployees'));
    }

    public function admins()
    {
        $admins = Admin::orderBy('name')->get();
        return view('admin.admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:admins,username',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Admin::create($validated);

        return redirect()->route('admin.admins')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function updateAdmin(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('admin.admins')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroyAdmin(Admin $admin)
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $admin->delete();

        return redirect()->route('admin.admins')->with('success', 'Admin berhasil dihapus.');
    }

    public function checkUsername(Request $request)
    {
        $username = $request->query('username');
        $excludeId = $request->query('exclude_id');

        $query = Admin::where('username', $username);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return response()->json([
            'available' => !$query->exists(),
        ]);
    }

    public function checkNip(Request $request)
    {
        $nip = $request->query('nip');
        $excludeId = $request->query('exclude_id');

        $query = Employee::where('nip', $nip);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return response()->json([
            'available' => !$query->exists(),
        ]);
    }

    public function employees()
    {
        $employees = Employee::with(['superior', 'positionData'])
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->orderBy('positions.level')
            ->orderBy('positions.sort_order')
            ->orderBy('employees.name')
            ->select('employees.*')
            ->get();
        $positions = Position::orderBy('level')->orderBy('sort_order')->get();

        return view('admin.employees', compact('employees', 'positions'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:30|unique:employees,nip',
            'name' => 'required|string|max:255',
            'front_title' => 'nullable|string|max:50',
            'back_title' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20|unique:employees,nik',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:8',
            'religion' => 'nullable|string|max:20',
            'marital_status' => 'nullable|string|max:20',
            'education' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'position_id' => 'nullable|exists:positions,id',
            'group_rank' => 'nullable|string|max:50',
            'group_start_date' => 'nullable|date',
            'position_start_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_admin' => 'nullable|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if (!isset($validated['position']) && isset($validated['position_id'])) {
            $position = Position::find($validated['position_id']);
            $validated['position'] = $position?->name;
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Employee::create($validated);

        return redirect()->route('admin.employees')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function updateEmployee(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:30|unique:employees,nip,' . $employee->id,
            'name' => 'required|string|max:255',
            'front_title' => 'nullable|string|max:50',
            'back_title' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20|unique:employees,nik,' . $employee->id,
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'religion' => 'nullable|string|max:20',
            'marital_status' => 'nullable|string|max:20',
            'education' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'position_id' => 'nullable|exists:positions,id',
            'group_rank' => 'nullable|string|max:50',
            'group_start_date' => 'nullable|date',
            'position_start_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_admin' => 'nullable|boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        if (!isset($validated['position']) && isset($validated['position_id'])) {
            $position = Position::find($validated['position_id']);
            $validated['position'] = $position?->name;
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $employee->update($validated);

        return redirect()->route('admin.employees')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroyEmployee(Employee $employee)
    {
        $employee->subordinates()->update(['superior_id' => null]);
        Task::where('assigned_to', $employee->id)->update(['assigned_to' => null]);
        Task::where('created_by', $employee->id)->update(['created_by' => null]);
        $employee->delete();

        return redirect()->route('admin.employees')->with('success', 'Pegawai berhasil dihapus.');
    }

    public function categories()
    {
        $categories = Category::withCount('tasks')->orderBy('name')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->tasks()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki tugas.');
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus.');
    }

    public function performance(Request $request)
    {
        // Periode bulan berjalan (bisa diganti lewat parameter ?month=YYYY-MM)
        $monthInput = $request->input('month');
        if (is_string($monthInput) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $monthInput)) {
            $month = \Carbon\Carbon::createFromFormat('!Y-m', $monthInput)->startOfMonth();
        } else {
            $month = now()->startOfMonth();
        }

        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $employees = Employee::select('employees.*')
            ->withCount([
                'assignedTasks as total_tasks',
                'assignedTasks as pending_tasks' => function ($q) {
                    $q->whereIn('status', ['Inbox', 'In Progress']);
                },
                'assignedTasks as done_tasks' => function ($q) {
                    $q->where('status', 'Done');
                },
                'assignedTasks as overdue_tasks' => function ($q) {
                    $q->whereNotNull('due_date')->where('due_date', '<', now())->where('status', '!=', 'Done');
                },
                'assignedTasks as month_total_tasks' => function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                },
                'assignedTasks as month_done_tasks' => function ($q) use ($startOfMonth, $endOfMonth) {
                    $q->where('status', 'Done')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                },
            ])
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->orderBy('positions.level')
            ->orderBy('positions.sort_order')
            ->orderBy('employees.name')
            ->get();

        // Data periode untuk tampilan & navigasi
        $monthNames = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $monthValue = $month->format('Y-m');
        $monthLabel = $monthNames[(int) $month->format('n')] . ' ' . $month->format('Y');
        $prevMonth = $month->copy()->subMonthNoOverflow()->format('Y-m');
        $nextMonth = $month->copy()->addMonthNoOverflow()->format('Y-m');

        return view('admin.performance', compact('employees', 'monthValue', 'monthLabel', 'prevMonth', 'nextMonth'));
    }

    public function analytics()
    {
        $totalEmployees = Employee::count();
        $totalTasks = Task::count();
        $totalDone = Task::where('status', 'Done')->count();
        $totalOverdue = Task::whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'Done')
            ->count();
        $completionRate = $totalTasks > 0 ? round(($totalDone / $totalTasks) * 100) : 0;

        $thisMonthTasks = Task::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $thisMonthDone = Task::where('status', 'Done')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)->count();

        $employeePerformance = Employee::with([
            'positionData',
        ])->withCount([
            'assignedTasks as total_tasks',
            'assignedTasks as done_tasks' => fn($q) => $q->where('status', 'Done'),
            'assignedTasks as overdue_tasks' => fn($q) => $q->whereNotNull('due_date')->where('due_date', '<', now())->where('status', '!=', 'Done'),
        ])->orderBy('name')->get();

        $statusDistribution = [
            'Inbox' => Task::where('status', 'Inbox')->count(),
            'In Progress' => Task::where('status', 'In Progress')->count(),
            'Done' => Task::where('status', 'Done')->count(),
        ];

        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'created' => Task::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                'completed' => Task::where('status', 'Done')->whereYear('updated_at', $date->year)->whereMonth('updated_at', $date->month)->count(),
            ];
        }

        $priorityDistribution = [
            'Low' => Task::where('priority', 'Low')->count(),
            'Medium' => Task::where('priority', 'Medium')->count(),
            'High' => Task::where('priority', 'High')->count(),
            'Urgent' => Task::where('priority', 'Urgent')->count(),
        ];

        $positionPerformance = [];
        $positions = Position::withCount('employees')->orderBy('level')->orderBy('sort_order')->get();
        foreach ($positions as $position) {
            $empIds = Employee::where('position_id', $position->id)->pluck('id');
            $total = $empIds->isNotEmpty() ? Task::whereIn('assigned_to', $empIds)->count() : 0;
            $done = $empIds->isNotEmpty() ? Task::whereIn('assigned_to', $empIds)->where('status', 'Done')->count() : 0;
            $overdue = $empIds->isNotEmpty() ? Task::whereIn('assigned_to', $empIds)
                ->whereNotNull('due_date')->where('due_date', '<', now())
                ->where('status', '!=', 'Done')->count() : 0;
            $positionPerformance[] = [
                'name' => $position->name,
                'total_employees' => $position->employees_count,
                'total_tasks' => $total,
                'done_tasks' => $done,
                'overdue_tasks' => $overdue,
                'completion_rate' => $total > 0 ? round(($done / $total) * 100) : 0,
            ];
        }

        $topPerformers = $employeePerformance->filter(fn($e) => $e->total_tasks > 0)->sortByDesc(fn($e) => $e->done_tasks / $e->total_tasks)->take(5);

        $mostOverdue = Employee::withCount([
            'assignedTasks as overdue_tasks' => fn($q) => $q->whereNotNull('due_date')->where('due_date', '<', now())->where('status', '!=', 'Done'),
        ])->having('overdue_tasks', '>', 0)->orderByDesc('overdue_tasks')->take(5)->get();

        return view('admin.analytics', compact(
            'totalEmployees', 'totalTasks', 'totalDone', 'totalOverdue', 'completionRate',
            'thisMonthTasks', 'thisMonthDone',
            'employeePerformance', 'statusDistribution', 'monthlyData',
            'priorityDistribution', 'positionPerformance', 'topPerformers', 'mostOverdue'
        ));
    }

    public function reportPerformance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $employees = Employee::with(['positionData'])
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->orderBy('positions.level')
            ->orderBy('positions.sort_order')
            ->orderBy('employees.name')
            ->select('employees.*')
            ->get();

        $reportData = $employees->map(function ($emp) use ($startDate, $endDate) {
            $inbox = Task::where('assigned_to', $emp->id)
                ->where('status', 'Inbox')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();
            $progress = Task::where('assigned_to', $emp->id)
                ->where('status', 'In Progress')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();
            $done = Task::where('assigned_to', $emp->id)
                ->where('status', 'Done')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();
            return [
                'employee' => $emp,
                'inbox' => $inbox,
                'progress' => $progress,
                'done' => $done,
                'total' => $inbox + $progress + $done,
            ];
        })->filter(fn($r) => $r['total'] > 0)->values();

        $summary = [
            'total_employees' => $reportData->count(),
            'total_inbox' => $reportData->sum('inbox'),
            'total_progress' => $reportData->sum('progress'),
            'total_done' => $reportData->sum('done'),
            'total_all' => $reportData->sum('total'),
        ];

        return view('admin.report-performance', compact('reportData', 'summary', 'startDate', 'endDate'));
    }

    public function positions()
    {
        $positions = Position::withCount('employees')->with('parents')->orderBy('level')->orderBy('sort_order')->get();
        $allPositions = Position::orderBy('level')->orderBy('name')->get();
        return view('admin.positions', compact('positions', 'allPositions'));
    }

    public function storePosition(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions,name',
            'description' => 'nullable|string|max:255',
            'level' => 'nullable|integer|min:1',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'exists:positions,id',
        ]);

        $maxSort = Position::max('sort_order');
        $validated['sort_order'] = $maxSort + 1;

        $position = Position::create($validated);

        if (!empty($validated['parent_ids'])) {
            $position->parents()->sync($validated['parent_ids']);
        }

        return redirect()->route('admin.positions')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function updatePosition(Request $request, Position $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
            'description' => 'nullable|string|max:255',
            'level' => 'nullable|integer|min:1',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'exists:positions,id',
        ]);

        $position->update($validated);

        $position->parents()->sync($validated['parent_ids'] ?? []);

        return redirect()->route('admin.positions')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroyPosition(Position $position)
    {
        if ($position->employees()->count() > 0) {
            return back()->with('error', 'Jabatan tidak bisa dihapus karena masih ada pegawai dengan jabatan ini.');
        }

        $position->delete();

        return redirect()->route('admin.positions')->with('success', 'Jabatan berhasil dihapus.');
    }

    public function sortPositions(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:positions,id',
        ]);

        foreach ($request->ids as $index => $id) {
            Position::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}

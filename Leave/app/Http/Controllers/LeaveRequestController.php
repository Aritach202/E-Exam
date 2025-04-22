<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::query();

        //  ค้นหาจากชื่อ
        if ($request->filled('full_name')) {
            $query->where('full_name', 'like', '%' . $request->full_name . '%');
        }

        //  ค้นหาตามช่วงวันที่ขอลา
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('start_date', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->where('start_date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->where('start_date', '<=', $request->to_date);
        }

        // เรียงลำดับตามวันที่บันทึก
        $sortOrder = $request->get('sort', 'desc'); // default: ใหม่สุดก่อน
        $query->orderBy('submitted_at', $sortOrder);

        // pagination
        $leaveRequests = $query->paginate(10);

        return view('leave_requests.index', compact('leaveRequests'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leave_requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'   => 'required|string',
            'department'  => 'nullable|string',
            'email'       => 'nullable|email',
            'phone'       => 'required|string',
            'leave_type'  => 'required|in:ลาป่วย,ลากิจ,พักร้อน,อื่นๆ',
            'reason'      => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);
    
        $today = now()->startOfDay();
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
    
        //  ห้ามลาย้อนหลัง
        if ($startDate->lt($today)) {
            return back()->with('error', 'ไม่สามารถลาย้อนหลังได้');
        }
    
        // เงื่อนไขเฉพาะ "พักร้อน"
        if ($validated['leave_type'] === 'พักร้อน') {
            //  ต้องลาล่วงหน้าอย่างน้อย 3 วัน
            if ($startDate->startOfDay()->lt(now()->addDays(3)->startOfDay())) {
                return back()->with('error', 'การพักร้อนต้องลาล่วงหน้าอย่างน้อย 3 วัน');
            }
    
            //  ห้ามลาเกิน 2 วัน
            if ($startDate->diffInDays($endDate) > 1) {
                return back()->with('error', 'การพักร้อนสามารถลาติดต่อกันได้ไม่เกิน 2 วัน');
            }
        }
    
        //  บันทึกข้อมูล
        LeaveRequest::create([
            ...$validated,
            'submitted_at' => now(),
            'status' => 'รอพิจารณา',
        ]);
    
        return redirect()->route('leave-requests.index')->with('success', 'บันทึกคำขอลาสำเร็จ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:อนุมัติ,ไม่อนุมัติ',
        ]);
    
        $leaveRequest = LeaveRequest::where('id', $id)
            ->where('status', 'รอพิจารณา') // ปรับได้เฉพาะที่ยังไม่พิจารณา
            ->firstOrFail();
    
        $leaveRequest->status = $validated['status'];
        $leaveRequest->save();
    
        return redirect()->route('leave-requests.index')
            ->with('success', 'อัปเดตสถานะคำขอลาสำเร็จแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->delete();
    
        return redirect()->route('leave-requests.index')
            ->with('success', 'ลบคำขอลาเรียบร้อยแล้ว');
    }
}

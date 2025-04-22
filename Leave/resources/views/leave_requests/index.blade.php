@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">รายการคำขอลาหยุด</h2>

    {{-- ฟอร์มค้นหา --}}
    <form method="GET" action="{{ route('leave-requests.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="full_name" class="form-control" placeholder="ค้นหาด้วยชื่อ" value="{{ request('full_name') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </div>
    </form>

    {{-- ตารางแสดงรายการคำขอลา --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ชื่อ - นามสกุล</th>
                <th>เบอร์โทร</th>
                <th>ประเภทการลา</th>
                <th>วันที่ลา</th>
                <th>เหตุผล</th>
                <th>
                    วันบันทึกข้อมูล
                    <a href="{{ route('leave-requests.index', ['sort' => request('sort') === 'asc' ? 'desc' : 'asc']) }}">
                        @if(request('sort') === 'asc')
                            🔼
                        @else
                            🔽
                        @endif
                    </a>
                </th>
                <th>สถานะ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaveRequests as $leave)
                <tr>
                    <td>{{ $leave->full_name }}</td>
                    <td>{{ $leave->phone }}</td>
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->start_date }} ถึง {{ $leave->end_date }}</td>
                    <td>{{ $leave->reason }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->submitted_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $leave->status }}</td>
                    <td>
                        {{-- ปุ่มพิจารณา (เฉพาะรอพิจารณา) --}}
                        @if ($leave->status === 'รอพิจารณา')
                            <form action="{{ route('leave-requests.update', $leave->id) }}" method="POST" class="d-flex mb-1">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select me-1" required>
                                    <option value="อนุมัติ">อนุมัติ</option>
                                    <option value="ไม่อนุมัติ">ไม่อนุมัติ</option>
                                </select>
                                <button type="submit" class="btn btn-success btn-sm">ยืนยัน</button>
                            </form>
                        @endif

                        {{-- ปุ่มลบ --}}
                        <form action="{{ route('leave-requests.destroy', $leave->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">ไม่พบข้อมูล</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $leaveRequests->appends(request()->query())->links() }}
</div>
@endsection
@extends('layouts.app') 

@section('content')
<div class="container">
    <h2>แบบฟอร์มขอลาหยุด</h2>

    {{-- แสดง error จาก validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- แสดง error จาก session --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- แสดง success message จาก session --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- ฟอร์มคำขอลา --}}
    <form method="POST" action="{{ route('leave-requests.store') }}">
        @csrf

        <div class="mb-3">
            <label>ชื่อ - นามสกุล *</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
        </div>

        <div class="mb-3">
            <label>สังกัด / ตำแหน่ง</label>
            <input type="text" name="department" class="form-control" value="{{ old('department') }}">
        </div>

        <div class="mb-3">
            <label>อีเมล</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label>เบอร์โทรศัพท์ *</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        <div class="mb-3">
            <label>ประเภทการลา *</label>
            <select name="leave_type" class="form-select" required>
                <option value="ลาป่วย" {{ old('leave_type') == 'ลาป่วย' ? 'selected' : '' }}>ลาป่วย</option>
                <option value="ลากิจ" {{ old('leave_type') == 'ลากิจ' ? 'selected' : '' }}>ลากิจ</option>
                <option value="พักร้อน" {{ old('leave_type') == 'พักร้อน' ? 'selected' : '' }}>พักร้อน</option>
                <option value="อื่นๆ" {{ old('leave_type') == 'อื่นๆ' ? 'selected' : '' }}>อื่นๆ</option>
            </select>
        </div>

        <div class="mb-3">
            <label>สาเหตุการลา *</label>
            <textarea name="reason" class="form-control" required>{{ old('reason') }}</textarea>
        </div>

        <div class="mb-3">
            <label>วันที่เริ่มลา *</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
        </div>

        <div class="mb-3">
            <label>ถึงวันที่ *</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">ส่งคำขอลา</button>
    </form>
</div>
@endsection
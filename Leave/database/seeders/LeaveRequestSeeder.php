<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveRequest;
use Illuminate\Support\Str;


class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            LeaveRequest::create([
                'full_name' => 'ผู้ใช้ทดสอบ ' . $i,
                'department' => 'แผนกตัวอย่าง',
                'email' => "user{$i}@example.com",
                'phone' => '08123456' . rand(10, 99),
                'leave_type' => collect(['ลาป่วย', 'ลากิจ', 'พักร้อน', 'อื่นๆ'])->random(),
                'reason' => 'ทดสอบเหตุผลการลา ' . Str::random(5),
                'start_date' => now()->addDays($i),
                'end_date' => now()->addDays($i + rand(0, 1)),
                'submitted_at' => now()->subDays(rand(0, 3)),
                'status' => collect(['รอพิจารณา', 'อนุมัติ', 'ไม่อนุมัติ'])->random(),
            ]);
        }
    }
}



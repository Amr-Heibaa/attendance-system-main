@extends('layouts.admin')

@section('page-title', 'استيراد الموظفين')
@section('page-subtitle', 'رفع ملف Excel لاستيراد أو تحديث الموظفين')

@section('content')
<div class="max-w-4xl bg-white rounded-2xl shadow-sm p-8">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">تعليمات مهمة</h3>
        <ul class="text-sm text-gray-600 space-y-2">
            <li>يجب أن يحتوي الملف على الأعمدة التالية بالضبط:</li>
        </ul>

        <div class="mt-3 p-4 bg-gray-50 rounded-xl text-sm text-gray-700 leading-7">
            name, email, password, employee_code, department_name, shift_name, job_title, phone,
            emergency_phone, national_id, birth_date, insurance_number, education_qualification,
            address, cost_center, work_location, hire_date, status
        </div>

        <ul class="text-sm text-gray-600 space-y-2 mt-4">
            <li>إذا كان الموظف موجودًا بنفس <strong>كود الموظف</strong> سيتم تحديثه.</li>
            <li>إذا لم يكن موجودًا سيتم إضافته كموظف جديد.</li>
            <li>القسم يجب أن يكون موجودًا مسبقًا بنفس الاسم العربي أو الإنجليزي.</li>
            <li>الوردية يجب أن تكون موجودة مسبقًا بنفس الاسم.</li>
            <li>الحالة يجب أن تكون واحدة من: active / inactive / suspended</li>
            <li>الرقم القومي إن وُجد يجب أن يكون 14 رقمًا.</li>
            <li>يمكنك تحميل النموذج الجاهز ثم تعبئته ورفعه.</li>
        </ul>
    </div>

    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.employees.template.excel') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
            تحميل نموذج Excel
        </a>

        <a href="{{ route('admin.employees.index') }}"
           class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
            رجوع
        </a>
    </div>

    <form method="POST" action="{{ route('admin.employees.import') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">ملف Excel</label>
            <input type="file" name="file"
                   class="w-full border border-gray-300 rounded-xl px-4 py-3 @error('file') border-red-400 @enderror">
            @error('file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-medium transition">
            بدء الاستيراد
        </button>
    </form>
</div>
@endsection
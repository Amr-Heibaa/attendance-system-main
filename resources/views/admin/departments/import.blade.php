@extends('layouts.admin')

@section('page-title', 'استيراد الأقسام')
@section('page-subtitle', 'رفع ملف Excel لاستيراد أو تحديث الأقسام')

@section('content')
<div class="max-w-3xl bg-white rounded-2xl shadow-sm p-8">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">تعليمات مهمة</h3>
        <ul class="text-sm text-gray-600 space-y-2">
            <li>يجب أن يحتوي الملف على الأعمدة التالية بالضبط: <strong>name_ar, name, description, is_active</strong></li>
            <li>إذا كان القسم موجودًا بنفس الاسم العربي أو الإنجليزي سيتم تحديثه.</li>
            <li>إذا لم يكن موجودًا سيتم إضافته كقسم جديد.</li>
            <li>يمكنك تحميل النموذج الجاهز ثم تعبئته ورفعه.</li>
        </ul>
    </div>

    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.departments.template.excel') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
            تحميل نموذج Excel
        </a>

        <a href="{{ route('admin.departments.index') }}"
           class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
            رجوع
        </a>
    </div>

    <form method="POST" action="{{ route('admin.departments.import') }}" enctype="multipart/form-data">
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
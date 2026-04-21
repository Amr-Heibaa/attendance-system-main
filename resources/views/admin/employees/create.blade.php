@extends('layouts.admin')

@section('page-title', 'إضافة موظف جديد')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm p-8">
    <form method="POST" action="{{ route('admin.employees.store') }}">
        @csrf
        <div class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">كود الموظف <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="EMP001" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 @error('employee_code') border-red-400 @enderror">
                    @error('employee_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">كلمة المرور <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">القسم</label>
                    <select name="department_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- بدون قسم --</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الوردية</label>
                    <select name="shift_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- بدون وردية --</option>
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">المسمى الوظيفي</label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">تاريخ التعيين <span class="text-red-500">*</span></label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 @error('hire_date') border-red-400 @enderror">
                    @error('hire_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">الحالة <span class="text-red-500">*</span></label>
                <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>موقوف</option>
                </select>
            </div>

            {{-- البيانات الشخصية --}}
            <div>
                <h3 class="text-md font-semibold text-gray-800 mb-3">البيانات الشخصية</h3>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">رقم التليفون</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">تليفون الطوارئ</label>
                        <input type="text" name="emergency_phone" value="{{ old('emergency_phone') }}" class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">الرقم القومي</label>
                        <input type="text" name="national_id"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="14"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">تاريخ الميلاد</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">الرقم التأميني</label>
                        <input type="text" name="insurance_number" value="{{ old('insurance_number') }}" class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">المؤهل الدراسي</label>
                        <input type="text" name="education_qualification" value="{{ old('education_qualification') }}" class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                </div>

                <div class="mt-4">
                    <label class="block text-sm text-gray-700 mb-1">العنوان</label>
                    <textarea name="address" rows="2" class="w-full border rounded-xl px-4 py-2.5">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">مركز التكلفة</label>
                        <input type="text" name="cost_center" value="{{ old('cost_center') }}" class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">مكان العمل</label>
                        <input type="text" name="work_location" value="{{ old('work_location') }}" class="w-full border rounded-xl px-4 py-2.5">
                    </div>

                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                    إضافة الموظف
                </button>
                <a href="{{ route('admin.employees.index') }}" class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-medium transition">
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
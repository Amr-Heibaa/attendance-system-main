@extends('layouts.admin')

@section('page-title', 'تعديل موظف')
@section('page-subtitle', 'تحديث بيانات الموظف')

@section('content')
<div class="max-w-4xl bg-white rounded-2xl shadow-sm p-8">

    <form method="POST" action="{{ route('admin.employees.update', $employee) }}">
        @csrf
        @method('PUT')

        <div class="space-y-8">

            {{-- بيانات الحساب --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">بيانات الحساب</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            الاسم بالكامل *
                        </label>
                        <input type="text"
                            name="name"
                            value="{{ old('name', $employee->user->name) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('name') border-red-400 @enderror">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            البريد الإلكتروني *
                        </label>
                        <input type="email"
                            name="email"
                            value="{{ old('email', $employee->user->email) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('email') border-red-400 @enderror">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            كلمة المرور (اتركها فارغة إن لم ترغب بالتغيير)
                        </label>
                        <input type="password"
                            name="password"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('password') border-red-400 @enderror">
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            تأكيد كلمة المرور
                        </label>
                        <input type="password"
                            name="password_confirmation"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                    </div>

                </div>
            </div>


            {{-- البيانات الوظيفية --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">البيانات الوظيفية</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            كود الموظف *
                        </label>
                        <input type="text"
                            name="employee_code"
                            value="{{ old('employee_code', $employee->employee_code) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('employee_code') border-red-400 @enderror">
                        @error('employee_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            القسم *
                        </label>
                        <select name="department_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('department_id') border-red-400 @enderror">
                            <option value="">-- اختر القسم --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name_ar ?? $dept->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            الوردية *
                        </label>
                        <select name="shift_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('shift_id') border-red-400 @enderror">
                            <option value="">-- اختر الوردية --</option>
                            @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}"
                                {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('shift_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            تاريخ التعيين *
                        </label>
                        <input type="date"
                            name="hire_date"
                            value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('hire_date') border-red-400 @enderror">
                        @error('hire_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            الدور (Role) *
                        </label>
                        <select name="role"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('role') border-red-400 @enderror">
                            <option value="admin" {{ old('role', $employee->user->role) == 'admin' ? 'selected' : '' }}>مدير</option>
                            <option value="employee" {{ old('role', $employee->user->role) == 'employee' ? 'selected' : '' }}>موظف</option>
                        </select>
                        @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> -->

                    {{-- الحالة (مطلوبة لأن StoreEmployeeRequest بيطلبها) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            الحالة *
                        </label>
                        <select name="status"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('status') border-red-400 @enderror">
                            <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="suspended" {{ old('status', $employee->status) === 'suspended' ? 'selected' : '' }}>موقوف</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- الدور (Spatie Roles) --}}
                    @php
                    $currentRole = old('role', $employee->user->getRoleNames()->first() ?? 'employee');
                    @endphp
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            الدور (Role)
                        </label>
                        <select name="role"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('role') border-red-400 @enderror">
                            <option value="employee" {{ $currentRole === 'employee' ? 'selected' : '' }}>موظف</option>
                            <option value="manager" {{ $currentRole === 'manager'  ? 'selected' : '' }}>مدير</option>
                            <option value="admin" {{ $currentRole === 'admin'    ? 'selected' : '' }}>مدير النظام</option>
                        </select>
                        @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- البيانات الشخصية --}}
<div>
    <h3 class="text-lg font-semibold text-gray-800 mb-4">البيانات الشخصية</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="text-sm">رقم التليفون</label>
            <input type="text" name="phone"
                value="{{ old('phone', $employee->phone) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="text-sm">تليفون الطوارئ</label>
            <input type="text" name="emergency_phone"
                value="{{ old('emergency_phone', $employee->emergency_phone) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="text-sm">الرقم القومي</label>
            <input type="text" name="national_id"
                value="{{ old('national_id', $employee->national_id) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="text-sm">تاريخ الميلاد</label>
            <input type="date" name="birth_date"
                value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="text-sm">الرقم التأميني</label>
            <input type="text" name="insurance_number"
                value="{{ old('insurance_number', $employee->insurance_number) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="text-sm">المؤهل الدراسي</label>
            <input type="text" name="education_qualification"
                value="{{ old('education_qualification', $employee->education_qualification) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

    </div>

    <div class="mt-4">
        <label class="text-sm">العنوان</label>
        <textarea name="address" rows="2"
            class="w-full border rounded-xl px-4 py-2.5">{{ old('address', $employee->address) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4">

        <div>
            <label class="text-sm">مركز التكلفة</label>
            <input type="text" name="cost_center"
                value="{{ old('cost_center', $employee->cost_center) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="text-sm">مكان العمل</label>
            <input type="text" name="work_location"
                value="{{ old('work_location', $employee->work_location) }}"
                class="w-full border rounded-xl px-4 py-2.5">
        </div>

    </div>
</div>

            {{-- أزرار التحكم --}}
            <div class="flex gap-4 pt-6">
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                    حفظ التعديلات
                </button>

                <a href="{{ route('admin.employees.index') }}"
                    class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-medium transition">
                    إلغاء
                </a>
            </div>

        </div>
    </form>
</div>
@endsection
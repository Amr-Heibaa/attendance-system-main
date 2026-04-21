@extends('layouts.admin')

@section('page-title', 'رصيد الإجازات')
@section('page-subtitle', 'متابعة رصيد الإجازات للموظفين')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">

    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-right">الموظف</th>
                <th class="p-3 text-right">إجمالي</th>
                <th class="p-3 text-right">المستخدم</th>
                <th class="p-3 text-right">المتبقي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr class="border-t">
                    <td class="p-3">{{ $row['name'] }}</td>
                    <td class="p-3">{{ $row['total'] }}</td>
                    <td class="p-3 text-red-600">{{ $row['used'] }}</td>
                    <td class="p-3 text-green-600 font-bold">{{ $row['remaining'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
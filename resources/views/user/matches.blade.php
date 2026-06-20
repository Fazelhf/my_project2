@extends('layouts.app')

@section('title', 'پیش‌بینی بازی‌ها')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">پیش‌بینی بازی‌ها</h1>
            <p class="text-gray-600">نتایج بازی‌های پیش‌رو را حدس بزنید و امتیاز بگیرید</p>
        </div>
    </div>

    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2 border-gray-200">بازی‌های پیش‌رو</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @foreach($matches->where('status', 'upcoming') as $match)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gray-50 p-3 border-b border-gray-100 flex justify-between text-sm text-gray-500">
                <span>{{ $match['group'] }} - {{ $match['stage'] }}</span>
                <span>{{ \Carbon\Carbon::parse($match['date'])->format('Y/m/d') }} - {{ $match['time'] }}</span>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-center w-1/3">
                        <p class="font-bold text-lg text-gray-900">{{ $match['team1'] }}</p>
                    </div>
                    <div class="w-1/3 text-center text-gray-400 font-medium">VS</div>
                    <div class="text-center w-1/3">
                        <p class="font-bold text-lg text-gray-900">{{ $match['team2'] }}</p>
                    </div>
                </div>
                
                @if($match['predicted'])
                    <div class="bg-green-50 rounded-xl p-3 text-center mb-4 border border-green-100">
                        <p class="text-xs text-green-600 mb-1">پیش‌بینی شما</p>
                        <p class="font-bold text-green-800 text-lg">{{ $match['pred_score1'] }} - {{ $match['pred_score2'] }}</p>
                    </div>
                    <a href="{{ route('match.predict', $match['id']) }}" class="block w-full text-center text-sm text-green-700 font-medium hover:text-green-800">ویرایش پیش‌بینی</a>
                @else
                    <a href="{{ route('match.predict', $match['id']) }}" class="block w-full text-center bg-gradient-to-r from-green-600 to-green-800 text-white py-2 rounded-xl font-medium hover:from-green-700 hover:to-green-900 transition-all">
                        ثبت پیش‌بینی
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
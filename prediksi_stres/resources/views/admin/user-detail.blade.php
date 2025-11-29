@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('admin.users') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block">
            ← Kembali ke Data Pengguna
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Pengguna</h1>
    </div>

    <!-- User Info -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Pengguna</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Nama</p>
                <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">NIM</p>
                <p class="text-lg font-medium text-gray-900">{{ $user->nim ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Prediksi</p>
                <p class="text-lg font-medium text-gray-900">{{ $user->predictions->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Predictions History -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">History Prediksi</h2>
        </div>
        <div class="p-6">
            @if($user->predictions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Stres</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->predictions as $prediction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prediction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($prediction->stress_level == 'Low') bg-green-100 text-green-800
                                            @elseif($prediction->stress_level == 'Moderate') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $prediction->stress_level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prediction->confidence_score }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.prediction.detail', $prediction->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Pengguna ini belum melakukan prediksi</p>
            @endif
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Hasil Prediksi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Hasil Prediksi Stres Akademik</h1>
            <p class="text-gray-600">Tanggal: {{ $prediction->created_at->format('d M Y H:i') }}</p>
        </div>

        <!-- Stress Level Card -->
        <div class="mb-8">
            <div class="rounded-lg p-6 text-center
                @if($prediction->stress_level == 'Low') bg-green-50 border-2 border-green-200
                @elseif($prediction->stress_level == 'Moderate') bg-yellow-50 border-2 border-yellow-200
                @else bg-red-50 border-2 border-red-200
                @endif">
                <h2 class="text-2xl font-bold mb-2
                    @if($prediction->stress_level == 'Low') text-green-800
                    @elseif($prediction->stress_level == 'Moderate') text-yellow-800
                    @else text-red-800
                    @endif">
                    Tingkat Stres: {{ $prediction->stress_level }}
                </h2>
                <p class="text-gray-600">Confidence Score: {{ $prediction->confidence_score }}%</p>
            </div>
        </div>

        <!-- Stress Factors -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Faktor Penyebab Utama (XAI Analysis)</h3>
            <div class="space-y-4">
                @foreach($prediction->stressFactors as $factor)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900">{{ $factor->factor_name }}</span>
                            <span class="text-sm text-gray-600">Rank #{{ $factor->rank }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $factor->importance_score }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Importance Score: {{ $factor->importance_score }}%</p>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Recommendations -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Rekomendasi</h3>
            <ul class="list-disc list-inside space-y-2 text-blue-800">
                @if($prediction->stress_level == 'High')
                    <li>Pertimbangkan untuk mengurangi beban akademik atau mencari bantuan konseling</li>
                    <li>Pastikan waktu tidur minimal 7-8 jam per hari</li>
                    <li>Cari dukungan dari teman, keluarga, atau konselor</li>
                    <li>Lakukan aktivitas relaksasi seperti olahraga atau meditasi</li>
                @elseif($prediction->stress_level == 'Moderate')
                    <li>Pertahankan keseimbangan antara akademik dan kehidupan pribadi</li>
                    <li>Tingkatkan manajemen waktu untuk mengurangi tekanan</li>
                    <li>Jaga pola tidur yang teratur</li>
                @else
                    <li>Pertahankan gaya hidup sehat yang sudah Anda jalani</li>
                    <li>Terus jaga keseimbangan antara berbagai aspek kehidupan</li>
                @endif
            </ul>
        </div>

        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Kembali ke Dashboard
            </a>
            <a href="{{ route('history') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Lihat History
            </a>
        </div>
    </div>
</div>
@endsection


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
                @elseif($prediction->stress_level == 'Medium') bg-yellow-50 border-2 border-yellow-200
                @else bg-red-50 border-2 border-red-200
                @endif">
                <h2 class="text-2xl font-bold mb-2
                    @if($prediction->stress_level == 'Low') text-green-800
                    @elseif($prediction->stress_level == 'Medium') text-yellow-800
                    @else text-red-800
                    @endif">
                    Tingkat Stres: {{ $prediction->stress_level }}
                </h2>
                <p class="text-gray-600">Confidence Score: {{ $prediction->confidence_score }}%</p>
            </div>
        </div>

        <!-- Probability Distribution -->
        @if($mlResult && isset($mlResult['probabilities']))
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Distribusi Probabilitas</h3>
            <div class="space-y-3">
                @foreach($mlResult['probabilities'] as $level => $probability)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900">{{ $level }}</span>
                            <span class="text-sm text-gray-600">{{ number_format($probability, 2) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full
                                @if($level == 'Low') bg-green-600
                                @elseif($level == 'Medium') bg-yellow-600
                                @else bg-red-600
                                @endif" style="width: {{ $probability }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Contributing Factors (SHAP Analysis) -->
        @if($mlResult && isset($mlResult['top_factors']))
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Faktor Penyebab Utama (SHAP Analysis)</h3>
            <div class="space-y-3">
                @foreach($mlResult['top_factors'] as $index => $factor)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-900">{{ $factor }}</span>
                            <span class="text-sm bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full">Rank #{{ $index + 1 }}</span>
                        </div>
                        @if(isset($mlResult['feature_importance'][$factor]))
                            <p class="text-sm text-gray-600 mt-2">Impact: {{ number_format($mlResult['feature_importance'][$factor], 4) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif


        <!-- Recommendations -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Rekomendasi</h3>
            <ul class="list-disc list-inside space-y-2 text-blue-800">
                @if($prediction->stress_level == 'High')
                    <li>Pertimbangkan untuk mengurangi beban akademik atau mencari bantuan konseling</li>
                    <li>Pastikan waktu tidur minimal 7-8 jam per hari</li>
                    <li>Cari dukungan dari teman, keluarga, atau konselor</li>
                    <li>Lakukan aktivitas relaksasi seperti olahraga atau meditasi</li>
                @elseif($prediction->stress_level == 'Medium')
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


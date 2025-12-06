@extends('layouts.app')

@section('title', 'Detail Prediksi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block">
            ← Kembali ke Dashboard Admin
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Prediksi</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- User Info -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Informasi Mahasiswa</h2>
            <p class="text-gray-700"><strong>Nama:</strong> {{ $prediction->user->name }}</p>
            <p class="text-gray-700"><strong>Email:</strong> {{ $prediction->user->email }}</p>
            @if($prediction->user->nim)
                <p class="text-gray-700"><strong>NIM:</strong> {{ $prediction->user->nim }}</p>
            @endif
        </div>

        <!-- Prediction Result -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Tanggal: {{ $prediction->created_at->format('d M Y H:i') }}</p>
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
        </div>

        <!-- Stress Factors -->
        @if($prediction->stressFactors->count() > 0)
            <div class="mb-6 pb-6 border-b border-gray-200">
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
        @endif

        <!-- Questionnaire Answers -->
        @if($prediction->questionnaire)
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Jawaban Kuesioner</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Beban Akademik</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->academic_load }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Jam Tidur</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->sleep_hours }} jam</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Dukungan Sosial</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->social_support }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Stres Finansial</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->financial_stress }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Manajemen Waktu</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->time_management }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Kondisi Kesehatan</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->health_condition }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Masalah Keluarga</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->family_issues }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Status Hubungan</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->relationship_status }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Lingkungan Belajar</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->study_environment }}/5</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Kecemasan Masa Depan</p>
                        <p class="text-lg font-semibold">{{ $prediction->questionnaire->future_anxiety }}/5</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


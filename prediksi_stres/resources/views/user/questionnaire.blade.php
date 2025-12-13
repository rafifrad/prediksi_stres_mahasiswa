@extends('layouts.app')

@section('title', 'Kuesioner Stres Akademik')

@push('styles')
<style>
    .question-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        background-color: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Radio button styles */
    .option-label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.5rem;
        margin: 0.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        background-color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        color: #4b5563;
    }

    .option-label:hover {
        border-color: #4f46e5;
        background-color: #f5f3ff;
    }

    input[type="radio"] {
        /* Hide the default radio button */
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    input[type="radio"]:checked + .option-label {
        border-color: #4f46e5;
        background-color: #4f46e5;
        color: white;
        font-weight: 600;
        box-shadow: 0 1px 3px 0 rgba(79, 70, 229, 0.3);
    }

    /* Remove the custom radio circle since we're using numbers */
    .option-label::before {
        display: none;
    }

    /* Hover state for better interaction */
    .option-label:hover {
        border-color: #818cf8;
        background-color: #eef2ff;
    }

    /* Options container */
    .options-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .section-title {
        position: relative;
        padding-left: 1rem;
    }
    .section-title:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(to bottom, #4f46e5, #7c3aed);
        border-radius: 2px;
    }
    .progress-bar {
        height: 6px;
        border-radius: 3px;
        transition: width 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Kuesioner Prediksi Stres Akademik</h1>
            <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">Jawablah setiap pertanyaan dengan jujur untuk mendapatkan analisis yang akurat tentang tingkat stres akademik Anda.</p>
            
            <!-- Progress Bar -->
            <div class="mt-8 max-w-2xl mx-auto">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-indigo-700">Progres Kuesioner</span>
                    <span class="text-sm font-medium text-gray-500">
                        <span id="current-question">0</span>/13
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div id="progress-bar" class="bg-gradient-to-r from-indigo-500 to-purple-600 progress-bar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('questionnaire.submit') }}" id="questionnaire-form" class="space-y-10">
            @csrf

            <div class="space-y-8">
                <!-- Section A: Faktor Akademik -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 section-title">A. Faktor Akademik</h2>
                    
                    <!-- Tekanan Akademik -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">1</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Dalam sebulan terakhir, seberapa sering Anda merasa tertekan akibat tuntutan atau beban akademik?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Pernah dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="academic_load" value="{{ $i }}" required class="sr-only" {{ old('academic_load', session('academic_load')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('academic_load', session('academic_load')) ? 'Anda memilih: ' . old('academic_load', session('academic_load')) : '' }}
                                            @error('academic_load')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kesulitan Akumulasi -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">2</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa kesulitan pelajaran menumpuk begitu tinggi sehingga Anda merasa tidak bisa mengatasinya?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Sama Sekali dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="sleep_hours" value="{{ $i }}" required class="sr-only" {{ old('sleep_hours', session('sleep_hours')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('sleep_hours', session('sleep_hours')) ? 'Anda memilih: ' . old('sleep_hours', session('sleep_hours')) : '' }}
                                            @error('sleep_hours')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stres Tugas Deadline -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">3</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Seberapa sering tugas yang menumpuk dan deadline yang ketat meningkatkan tekanan pikiran Anda?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Pernah dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="social_support" value="{{ $i }}" required class="sr-only" {{ old('social_support', session('social_support')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('social_support', session('social_support')) ? 'Anda memilih: ' . old('social_support', session('social_support')) : '' }}
                                            @error('social_support')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tekanan Eksternal -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">4</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa terbebani oleh ekspektasi tinggi dari orang tua, dosen, atau lingkungan sekitar?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Sama Sekali dan 5 = Sangat Terbebani</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="financial_stress" value="{{ $i }}" required class="sr-only" {{ old('financial_stress', session('financial_stress')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('financial_stress', session('financial_stress')) ? 'Anda memilih: ' . old('financial_stress', session('financial_stress')) : '' }}
                                            @error('financial_stress')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tekanan IPK -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">5</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa cemas atau tertekan untuk selalu mendapatkan nilai IPK yang tinggi?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Sama Sekali dan 5 = Sangat Cemas/Tertekan</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="time_management" value="{{ $i }}" required class="sr-only" {{ old('time_management', session('time_management')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('time_management', session('time_management')) ? 'Anda memilih: ' . old('time_management', session('time_management')) : '' }}
                                            @error('time_management')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kurang Kendali -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">6</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa tidak mampu mengendalikan hal-hal penting dalam studi Anda (misal: jadwal, topik skripsi)?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Sama Sekali dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="health_condition" value="{{ $i }}" required class="sr-only" {{ old('health_condition', session('health_condition')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('health_condition', session('health_condition')) ? 'Anda memilih: ' . old('health_condition', session('health_condition')) : '' }}
                                            @error('health_condition')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rasa Tidak Sanggup -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">7</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa tidak sanggup lagi mengatasi semua kewajiban akademik yang harus dipenuhi?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Sama Sekali dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="health_condition_2" value="{{ $i }}" required class="sr-only" {{ old('health_condition_2', session('health_condition_2')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('health_condition_2', session('health_condition_2')) ? 'Anda memilih: ' . old('health_condition_2', session('health_condition_2')) : '' }}
                                            @error('health_condition_2')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section B: Faktor Eksternal & Pribadi -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 section-title">B. Faktor Eksternal & Pribadi</h2>
                    
                    <!-- Stres Pribadi -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">8</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Seberapa sering masalah pribadi (keuangan, keluarga, asmara) mengganggu fokus belajar Anda?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Pernah dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="family_issues" value="{{ $i }}" required class="sr-only" {{ old('family_issues', session('family_issues')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('family_issues', session('family_issues')) ? 'Anda memilih: ' . old('family_issues', session('family_issues')) : '' }}
                                            @error('family_issues')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Marah Eksternal Studi -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">9</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa marah/kesal karena hal-hal di luar kendali yang memengaruhi studi (misal: birokrasi kampus, dosen sulit ditemui)?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Pernah dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="relationship_status" value="{{ $i }}" required class="sr-only" {{ old('relationship_status', session('relationship_status')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('relationship_status', session('relationship_status')) ? 'Anda memilih: ' . old('relationship_status', session('relationship_status')) : '' }}
                                            @error('relationship_status')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stres Perubahan Akademik -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">10</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa stres berlebih ketika terjadi perubahan mendadak dalam jadwal atau metode perkuliahan?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Pernah dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="study_environment" value="{{ $i }}" required class="sr-only" {{ old('study_environment', session('study_environment')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('study_environment', session('study_environment')) ? 'Anda memilih: ' . old('study_environment', session('study_environment')) : '' }}
                                            @error('study_environment')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cemas Karir -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">11</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Seberapa sering Anda merasa cemas atau bingung mengenai masa depan karir/pekerjaan setelah lulus?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Pernah dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="future_anxiety" value="{{ $i }}" required class="sr-only" {{ old('future_anxiety', session('future_anxiety')) == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('future_anxiety', session('future_anxiety')) ? 'Anda memilih: ' . old('future_anxiety', session('future_anxiety')) : '' }}
                                            @error('future_anxiety')
                                                <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kebiasaan Buruk -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">12</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Saat sedang stres, seberapa sering Anda melakukan kebiasaan buruk (begadang, merokok, makan tidak teratur, menunda tugas)?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Pernah dan 5 = Sangat Sering</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="Kebiasaan_Buruk" value="{{ $i }}" required class="sr-only" {{ old('Kebiasaan_Buruk') == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('Kebiasaan_Buruk') ? 'Anda memilih: ' . old('Kebiasaan_Buruk') : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section C: Faktor Positif / Keyakinan -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 section-title">C. Faktor Positif / Keyakinan</h2>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Jawaban di bagian ini berkontribusi pada <span class="font-semibold">Academic Confidence Score</span> Anda. Pilihlah jawaban yang paling sesuai dengan perasaan Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Proses Sesuai Harapan -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 question-card">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">13</div>
                            <div class="ml-4">
                                <label class="block text-lg font-semibold text-gray-900 mb-3">
                                    Apakah Anda merasa bahwa proses perkuliahan dan kemajuan studi Anda saat ini berjalan sesuai harapan?
                                </label>
                                <p class="text-sm text-gray-500 mb-4">Pilih skala 1-5, dimana 1 = Tidak Sama Sekali dan 5 = Sangat Sesuai</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center">
                                            <input type="radio" name="Proses_Sesuai_Harapan" value="{{ $i }}" required class="sr-only" {{ old('Proses_Sesuai_Harapan') == $i ? 'checked' : '' }}>
                                            <span class="option-label h-10 w-10 rounded-lg flex items-center justify-center cursor-pointer font-medium">
                                                {{ $i }}
                                            </span>
                                        </label>
                                    @endfor
                                    <div class="flex-1 flex items-center">
                                        <span class="ml-4 text-sm text-gray-500">
                                            {{ old('Proses_Sesuai_Harapan') ? 'Anda memilih: ' . old('Proses_Sesuai_Harapan') : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 gap-4">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3 border border-gray-300 bg-white rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Dashboard
                </a>
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    @if(session('latest_prediction_id'))
                    <button type="button" onclick="window.location.href='{{ route('prediction.result', ['id' => session('latest_prediction_id')]) }}'" class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-medium rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg flex items-center justify-center">
                        Lihat Hasil
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    @endif
                    <button type="submit" class="w-full px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-medium rounded-lg hover:from-purple-700 hover:to-indigo-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg flex items-center justify-center">
                        Kirim Jawaban
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Update progress function
    function updateProgress() {
        // Use a flag to prevent multiple simultaneous updates
        if (window.updatingProgress) return;
        window.updatingProgress = true;
        
        try {
            // Define all radio groups
            const radioGroups = [
                'academic_load', 'sleep_hours', 'social_support', 'financial_stress',
                'time_management', 'health_condition', 'health_condition_2', 'family_issues', 'relationship_status',
                'study_environment', 'future_anxiety', 'Kebiasaan_Buruk', 'Proses_Sesuai_Harapan'
            ];
            
            // Get all checked radios
            const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
            const totalQuestions = 13; // Updated to 13 questions
            const answeredQuestions = checkedRadios.length;
            const progress = Math.round((answeredQuestions / totalQuestions) * 100);
            
            // Update elements
            const progressBar = document.getElementById('progress-bar');
            const currentQuestionElement = document.getElementById('current-question');
            
            if (progressBar && currentQuestionElement) {
                currentQuestionElement.textContent = answeredQuestions;
                progressBar.style.width = `${progress}%`;
                
                // Update colors
                progressBar.className = 'h-full rounded-full ' + (
                    progress >= 75 ? 'bg-gradient-to-r from-green-500 to-teal-500' :
                    progress >= 50 ? 'bg-gradient-to-r from-yellow-400 to-orange-500' :
                    'bg-gradient-to-r from-indigo-500 to-purple-600'
                );
                
                console.log(`Progress updated: ${answeredQuestions}/${totalQuestions} (${progress}%)`);
            } else {
                console.error('Progress elements not found!');
            }
        } catch (error) {
            console.error('Error in updateProgress:', error);
        } finally {
            window.updatingProgress = false;
        }
    }
    
    // Make updateProgress available globally
    window.updateProgress = updateProgress;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, initializing progress...');
        updateProgress();
        
        // Add click listeners to option labels
        document.querySelectorAll('.option-label').forEach(label => {
            label.addEventListener('click', function() {
                const radio = this.previousElementSibling;
                if (radio && radio.type === 'radio' && !radio.checked) {
                    console.log(`Clicked option: ${radio.name} = ${radio.value}`);
                    radio.checked = true;
                    updateProgress();
                }
            });
        });
        
        console.log('Event listeners added');
    });
</script>
@endpush

@endsection

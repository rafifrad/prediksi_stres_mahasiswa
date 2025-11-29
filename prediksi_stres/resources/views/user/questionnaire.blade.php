@extends('layouts.app')

@section('title', 'Kuesioner Stres')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Kuesioner Prediksi Stres Akademik</h1>
        <p class="text-gray-600 mb-8">Silakan isi kuesioner berikut untuk mengetahui tingkat stres akademik Anda. Jawablah dengan jujur untuk mendapatkan hasil yang akurat.</p>

        <form method="POST" action="{{ route('questionnaire.submit') }}">
            @csrf

            <div class="space-y-8">
                <!-- Academic Load -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        1. Bagaimana tingkat beban akademik Anda?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Sangat Ringan, 5 = Sangat Berat</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="academic_load" value="{{ $i }}" required class="mr-2" {{ old('academic_load') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Sleep Hours -->
                <div class="border-b border-gray-200 pb-6">
                    <label for="sleep_hours" class="block text-lg font-semibold text-gray-900 mb-3">
                        2. Berapa jam tidur Anda per hari?
                    </label>
                    <input type="number" id="sleep_hours" name="sleep_hours" min="0" max="24" value="{{ old('sleep_hours') }}" required
                        class="w-32 shadow appearance-none border rounded py-2 px-3 text-gray-700 focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Social Support -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        3. Bagaimana tingkat dukungan sosial yang Anda terima?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Sangat Kurang, 5 = Sangat Baik</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="social_support" value="{{ $i }}" required class="mr-2" {{ old('social_support') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Financial Stress -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        4. Bagaimana tingkat stres finansial Anda?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Tidak Ada, 5 = Sangat Tinggi</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="financial_stress" value="{{ $i }}" required class="mr-2" {{ old('financial_stress') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Time Management -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        5. Bagaimana kemampuan manajemen waktu Anda?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Sangat Buruk, 5 = Sangat Baik</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="time_management" value="{{ $i }}" required class="mr-2" {{ old('time_management') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Health Condition -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        6. Bagaimana kondisi kesehatan Anda?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Sangat Buruk, 5 = Sangat Baik</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="health_condition" value="{{ $i }}" required class="mr-2" {{ old('health_condition') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Family Issues -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        7. Bagaimana tingkat masalah keluarga yang mempengaruhi Anda?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Tidak Ada, 5 = Sangat Tinggi</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="family_issues" value="{{ $i }}" required class="mr-2" {{ old('family_issues') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Relationship Status -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        8. Bagaimana status hubungan sosial Anda?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Sangat Buruk, 5 = Sangat Baik</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="relationship_status" value="{{ $i }}" required class="mr-2" {{ old('relationship_status') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Study Environment -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        9. Bagaimana kualitas lingkungan belajar Anda?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Sangat Buruk, 5 = Sangat Baik</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="study_environment" value="{{ $i }}" required class="mr-2" {{ old('study_environment') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Future Anxiety -->
                <div class="pb-6">
                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                        10. Bagaimana tingkat kecemasan Anda tentang masa depan?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">1 = Tidak Ada, 5 = Sangat Tinggi</p>
                    <div class="flex space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="future_anxiety" value="{{ $i }}" required class="mr-2" {{ old('future_anxiety') == $i ? 'checked' : '' }}>
                                <span>{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Submit & Prediksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


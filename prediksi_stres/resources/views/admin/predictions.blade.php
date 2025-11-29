@extends('layouts.app')

@section('title', 'Daftar Prediksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Prediksi</h1>
        <p class="mt-2 text-gray-600">Lihat semua riwayat prediksi mahasiswa</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.predictions') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter berdasarkan User</label>
                <select name="user_id" class="w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 focus:outline-none focus:border-indigo-500">
                    <option value="">Semua User</option>
                    @foreach(\App\Models\User::where('role', 'user')->get() as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter berdasarkan Tingkat Stres</label>
                <select name="stress_level" class="w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Level</option>
                    <option value="Low" {{ request('stress_level') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Moderate" {{ request('stress_level') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="High" {{ request('stress_level') == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Predictions Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Daftar Prediksi</h2>
        </div>
        <div class="p-6">
            @if($predictions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Stres</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($predictions as $prediction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prediction->user->name }}<br>
                                        <span class="text-gray-500">{{ $prediction->user->email }}</span>
                                    </td>
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

                <div class="mt-4">
                    {{ $predictions->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Tidak ada prediksi ditemukan</p>
            @endif
        </div>
    </div>
</div>
@endsection


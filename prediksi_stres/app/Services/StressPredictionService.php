<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StressPredictionService
{
    protected $apiUrl;
    protected $timeout;

    public function __construct()
    {
        $this->apiUrl = env('ML_API_URL', 'http://localhost:5000');
        $this->timeout = 30; // 30 seconds timeout
    }

    /**
     * Check if ML API is healthy
     */
    public function healthCheck()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl . '/health');
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('ML API Health Check Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get required features from ML API
     */
    public function getFeatures()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl . '/features');
            
            if ($response->successful()) {
                return $response->json();
            }
            
            throw new \Exception('Failed to get features from ML API');
        } catch (\Exception $e) {
            Log::error('ML API Get Features Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Make stress prediction
     * 
     * @param array $data - Array of feature values
     * @return array|null
     */
    public function predict(array $data)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl . '/predict', [
                    'data' => $data
                ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                // Log prediction for monitoring
                Log::info('ML Prediction made', [
                    'prediction' => $result['prediction']['label'] ?? 'Unknown',
                    'confidence' => $result['prediction']['confidence'] ?? 0
                ]);
                
                return $result;
            }
            
            // Log error response
            Log::error('ML API Prediction Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('ML Prediction Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate input data before sending to ML API
     * 
     * @param array $data
     * @return bool
     */
    public function validateInputData(array $data)
    {
        $features = $this->getFeatures();
        
        if (!$features || !isset($features['features'])) {
            return false;
        }
        
        $requiredFeatures = $features['features'];
        
        foreach ($requiredFeatures as $feature) {
            if (!isset($data[$feature])) {
                Log::warning("Missing feature: {$feature}");
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get stress level recommendation based on prediction
     * 
     * @param string $stressLevel
     * @return array
     */
    public function getRecommendation(string $stressLevel)
    {
        $recommendations = [
            'Low' => [
                'message' => 'Tingkat stres Anda rendah. Pertahankan kondisi ini!',
                'tips' => [
                    'Tetap jaga pola tidur yang teratur',
                    'Lanjutkan aktivitas fisik rutin',
                    'Pertahankan pola makan sehat',
                    'Luangkan waktu untuk hobi dan relaksasi'
                ],
                'color' => 'green'
            ],
            'Medium' => [
                'message' => 'Tingkat stres Anda sedang. Perhatikan beberapa hal berikut.',
                'tips' => [
                    'Atur waktu belajar dengan baik',
                    'Istirahat cukup minimal 7-8 jam per hari',
                    'Lakukan aktivitas relaksasi seperti meditasi',
                    'Berbagi cerita dengan teman atau keluarga',
                    'Batasi konsumsi kafein'
                ],
                'color' => 'yellow'
            ],
            'High' => [
                'message' => 'Tingkat stres Anda tinggi. Segera ambil tindakan!',
                'tips' => [
                    'Konsultasi dengan psikolog atau konselor kampus',
                    'Atur ulang prioritas dan deadline',
                    'Lakukan teknik pernapasan dalam',
                    'Olahraga ringan secara teratur',
                    'Hindari begadang dan jaga pola tidur',
                    'Kurangi beban aktivitas jika memungkinkan',
                    'Cari dukungan dari orang terdekat'
                ],
                'color' => 'red'
            ]
        ];

        return $recommendations[$stressLevel] ?? $recommendations['Medium'];
    }
}

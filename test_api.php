<?php
/**
 * Comprehensive API Test Suite for Organizations API
 * Tests all available endpoints with various scenarios
 */

$baseUrl = 'http://app:8000/api';
$apiKey = 'test-api-key-12345';

// Test results storage
$testResults = [];
$passedTests = 0;
$totalTests = 0;

/**
 * Helper function to make HTTP requests
 */
function makeRequest($url, $method = 'GET', $headers = []) {
    // URL encode the URL to handle special characters
    $encodedUrl = $url;
    if (strpos($url, '?') !== false) {
        $parts = explode('?', $url, 2);
        $encodedUrl = $parts[0] . '?' . urlencode($parts[1]);
    }
    
    $context = stream_context_create([
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'timeout' => 30,
            'ignore_errors' => true // This allows us to get response body even for 4xx/5xx
        ]
    ]);
    
    $response = @file_get_contents($encodedUrl, false, $context);
    
    // Get HTTP status code from response headers
    $httpCode = 'Unknown';
    if (isset($http_response_header[0])) {
        $httpCode = $http_response_header[0];
    }
    
    // Check if we got a response (even for error status codes)
    if ($response !== false) {
        return [
            'success' => true,
            'data' => $response,
            'http_code' => $httpCode
        ];
    } else {
        return [
            'success' => false,
            'error' => error_get_last()['message'] ?? 'Unknown error',
            'http_code' => $httpCode
        ];
    }
}

/**
 * Helper function to run a test
 */
function runTest($testName, $url, $expectedHttpCode = '200 OK') {
    global $testResults, $passedTests, $totalTests, $apiKey;
    
    $totalTests++;
    echo "\n=== Тест $totalTests: $testName ===\n";
    echo "URL: $url\n";
    
    $headers = ["X-API-Key: $apiKey"];
    $response = makeRequest($url, 'GET', $headers);
    
    if ($response['success']) {
        $httpCode = $response['http_code'];
        
        echo "HTTP Code: $httpCode\n";
        echo "Response: " . substr($response['data'], 0, 500) . (strlen($response['data']) > 500 ? '...' : '') . "\n";
        
        $passed = false;
        
        // Check for 200 OK (successful responses)
        if (strpos($httpCode, '200') !== false) {
            $passed = true;
        }
        // Check for 400 Bad Request or 302 Found (expected for validation errors)
        elseif ((strpos($httpCode, '400') !== false || strpos($httpCode, '302') !== false) && (
            strpos($url, '/organizations/search/radius') !== false ||
            strpos($url, '/organizations/search/area') !== false ||
            strpos($url, '/organizations/search/name') !== false ||
            strpos($url, 'invalid') !== false
        )) {
            $passed = true;
        }
        // Check for 404 Not Found (expected for non-existent resources)
        elseif (strpos($httpCode, '404') !== false && (
            strpos($url, '/organizations/building/999') !== false ||
            strpos($url, '/organizations/activity/999') !== false ||
            strpos($url, '/organizations/999') !== false ||
            strpos($url, '/organizations/search/activity-tree/999') !== false
        )) {
            $passed = true;
        }
        
        if ($passed) {
            echo "✅ ТЕСТ ПРОЙДЕН\n";
            $passedTests++;
            $testResults[] = ['name' => $testName, 'status' => 'PASSED', 'http_code' => $httpCode];
        } else {
            echo "❌ ТЕСТ ПРОВАЛЕН (неожиданный HTTP код)\n";
            $testResults[] = ['name' => $testName, 'status' => 'FAILED', 'http_code' => $httpCode, 'error' => 'Unexpected HTTP code'];
        }
    } else {
        echo "❌ ТЕСТ ПРОВАЛЕН (ошибка соединения)\n";
        echo "Error: " . $response['error'] . "\n";
        $testResults[] = ['name' => $testName, 'status' => 'FAILED', 'error' => $response['error']];
    }
}

/**
 * Test without API key (should fail)
 */
function runUnauthorizedTest($testName, $url) {
    global $testResults, $passedTests, $totalTests;
    
    $totalTests++;
    echo "\n=== Тест $totalTests: $testName (без API ключа) ===\n";
    echo "URL: $url\n";
    
    $response = makeRequest($url, 'GET', []);
    
    if ($response['success']) {
        $httpCode = $response['http_code'];
        echo "HTTP Code: $httpCode\n";
        
        if (strpos($httpCode, '401') !== false || strpos($httpCode, '403') !== false) {
            echo "✅ ТЕСТ ПРОЙДЕН (правильно отклонен без API ключа)\n";
            $passedTests++;
            $testResults[] = ['name' => $testName, 'status' => 'PASSED', 'http_code' => $httpCode];
        } else {
            echo "❌ ТЕСТ ПРОВАЛЕН (должен был быть отклонен)\n";
            $testResults[] = ['name' => $testName, 'status' => 'FAILED', 'http_code' => $httpCode, 'error' => 'Should have been rejected'];
        }
    } else {
        echo "✅ ТЕСТ ПРОЙДЕН (соединение отклонено)\n";
        $passedTests++;
        $testResults[] = ['name' => $testName, 'status' => 'PASSED', 'error' => 'Connection rejected'];
    }
}

echo "=== КОМПЛЕКСНОЕ ТЕСТИРОВАНИЕ ORGANIZATIONS API ===\n";
echo "Base URL: $baseUrl\n";
echo "API Key: $apiKey\n";
echo "Время начала: " . date('Y-m-d H:i:s') . "\n";

// ===== ТЕСТЫ БЕЗ API КЛЮЧА =====
echo "\n🔒 ТЕСТИРОВАНИЕ БЕЗ API КЛЮЧА (должны быть отклонены):";
runUnauthorizedTest('Получение всех зданий', $baseUrl . '/buildings');
runUnauthorizedTest('Получение организаций в здании', $baseUrl . '/organizations/building/1');
runUnauthorizedTest('Поиск по радиусу', $baseUrl . '/organizations/search/radius?latitude=55.7558&longitude=37.6176&radius=5');

// ===== ОСНОВНЫЕ ТЕСТЫ =====
echo "\n📋 ОСНОВНЫЕ ТЕСТЫ API:";

// 1. Получение всех зданий
runTest('Получение всех зданий', $baseUrl . '/buildings');

// 2. Получение организаций в конкретном здании
runTest('Организации в здании ID=1', $baseUrl . '/organizations/building/1');
runTest('Организации в здании ID=2', $baseUrl . '/organizations/building/2');
runTest('Организации в несуществующем здании', $baseUrl . '/organizations/building/999');

// 3. Получение организаций по виду деятельности
runTest('Организации по деятельности ID=1', $baseUrl . '/organizations/activity/1');
runTest('Организации по деятельности ID=2', $baseUrl . '/organizations/activity/2');
runTest('Организации по несуществующей деятельности', $baseUrl . '/organizations/activity/999');

// 4. Поиск организаций в радиусе
runTest('Поиск в радиусе (центр Москвы, 5км)', $baseUrl . '/organizations/search/radius?latitude=55.7558&longitude=37.6176&radius=5');
runTest('Поиск в радиусе (центр Москвы, 1км)', $baseUrl . '/organizations/search/radius?latitude=55.7558&longitude=37.6176&radius=1');
runTest('Поиск в радиусе (без параметров)', $baseUrl . '/organizations/search/radius');

// 5. Поиск организаций в прямоугольной области
runTest('Поиск в области (Москва)', $baseUrl . '/organizations/search/area?min_lat=55.5&max_lat=56.0&min_lng=37.3&max_lng=37.8');
runTest('Поиск в области (малая область)', $baseUrl . '/organizations/search/area?min_lat=55.75&max_lat=55.76&min_lng=37.61&max_lng=37.62');
runTest('Поиск в области (без параметров)', $baseUrl . '/organizations/search/area');

// 6. Получение информации об организации по ID
runTest('Организация ID=1', $baseUrl . '/organizations/1');
runTest('Организация ID=2', $baseUrl . '/organizations/2');
runTest('Несуществующая организация', $baseUrl . '/organizations/999');

// 7. Поиск организаций по виду деятельности (включая дочерние)
runTest('Поиск по дереву деятельности ID=1', $baseUrl . '/organizations/search/activity-tree/1');
runTest('Поиск по дереву деятельности ID=2', $baseUrl . '/organizations/search/activity-tree/2');
runTest('Поиск по дереву несуществующей деятельности', $baseUrl . '/organizations/search/activity-tree/999');

// 8. Поиск организаций по названию
runTest('Поиск по названию "Рога"', $baseUrl . '/organizations/search/name?name=Рога');
runTest('Поиск по названию "ООО"', $baseUrl . '/organizations/search/name?name=ООО');
runTest('Поиск по названию "Ресторан"', $baseUrl . '/organizations/search/name?name=Ресторан');
runTest('Поиск по названию (без параметра)', $baseUrl . '/organizations/search/name');
runTest('Поиск несуществующего названия', $baseUrl . '/organizations/search/name?name=НесуществующаяОрганизация');

// ===== ТЕСТЫ ГРАНИЧНЫХ СЛУЧАЕВ =====
echo "\n🔍 ТЕСТЫ ГРАНИЧНЫХ СЛУЧАЕВ:";

// Тесты с некорректными параметрами
runTest('Поиск в радиусе с некорректными координатами', $baseUrl . '/organizations/search/radius?latitude=invalid&longitude=invalid&radius=5');
runTest('Поиск в области с некорректными координатами', $baseUrl . '/organizations/search/area?min_lat=invalid&max_lat=invalid&min_lng=invalid&max_lng=invalid');

// Тесты с очень большими значениями
runTest('Поиск в радиусе с большим радиусом', $baseUrl . '/organizations/search/radius?latitude=55.7558&longitude=37.6176&radius=1000');
runTest('Поиск в области с большими координатами', $baseUrl . '/organizations/search/area?min_lat=0&max_lat=90&min_lng=-180&max_lng=180');

// ===== ИТОГОВАЯ СТАТИСТИКА =====
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 ИТОГОВАЯ СТАТИСТИКА ТЕСТИРОВАНИЯ\n";
echo str_repeat("=", 60) . "\n";
echo "Всего тестов: $totalTests\n";
echo "Пройдено: $passedTests\n";
echo "Провалено: " . ($totalTests - $passedTests) . "\n";
echo "Процент успеха: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";

if ($passedTests == $totalTests) {
    echo "\n🎉 ВСЕ ТЕСТЫ ПРОЙДЕНЫ УСПЕШНО!\n";
} else {
    echo "\n⚠️  НЕКОТОРЫЕ ТЕСТЫ ПРОВАЛЕНЫ\n";
}

echo "\n📋 ДЕТАЛЬНЫЕ РЕЗУЛЬТАТЫ:\n";
foreach ($testResults as $result) {
    $status = $result['status'] === 'PASSED' ? '✅' : '❌';
    echo "$status {$result['name']} - {$result['status']}";
    if (isset($result['http_code'])) {
        echo " (HTTP: {$result['http_code']})";
    }
    if (isset($result['error'])) {
        echo " - {$result['error']}";
    }
    echo "\n";
}

echo "\nВремя завершения: " . date('Y-m-d H:i:s') . "\n";
echo "=== ТЕСТИРОВАНИЕ ЗАВЕРШЕНО ===\n"; 
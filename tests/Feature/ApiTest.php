<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use App\Models\OrganizationPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected $apiKey = 'test-api-key-12345';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run seeders to populate test data
        $this->seed();
    }

    /**
     * Test API key authentication
     */
    public function test_api_requires_authentication()
    {
        // Test without API key
        $response = $this->get('/api/buildings');
        $response->assertStatus(401);

        // Test with wrong API key
        $response = $this->withHeaders(['X-API-Key' => 'wrong-key'])->get('/api/buildings');
        $response->assertStatus(401);

        // Test with correct API key
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])->get('/api/buildings');
        $response->assertStatus(200);
    }

    /**
     * Test getting all buildings
     */
    public function test_get_all_buildings()
    {
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/buildings');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'address',
                    'latitude',
                    'longitude',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $data = $response->json('data');
        $this->assertCount(5, $data); // We seeded 5 buildings
    }

    /**
     * Test getting organizations by building
     */
    public function test_get_organizations_by_building()
    {
        // Test with existing building
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/building/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'building' => [
                        'id',
                        'address',
                        'latitude',
                        'longitude'
                    ],
                    'phones' => [
                        '*' => [
                            'id',
                            'phone'
                        ]
                    ],
                    'activities' => [
                        '*' => [
                            'id',
                            'name',
                            'parent_id',
                            'level'
                        ]
                    ]
                ]
            ]
        ]);

        // Test with non-existent building
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/building/999');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(0, $data);
    }

    /**
     * Test getting organizations by activity
     */
    public function test_get_organizations_by_activity()
    {
        // Test with existing activity
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/activity/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'building',
                    'phones',
                    'activities'
                ]
            ]
        ]);

        // Test with non-existent activity
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/activity/999');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(0, $data);
    }

    /**
     * Test radius search
     */
    public function test_search_organizations_by_radius()
    {
        // Test with valid coordinates
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/radius?latitude=55.7558&longitude=37.6176&radius=5');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'building',
                    'phones',
                    'activities'
                ]
            ]
        ]);

        // Test without parameters
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/radius');

        $response->assertStatus(400);
    }

    /**
     * Test area search
     */
    public function test_search_organizations_by_area()
    {
        // Test with valid coordinates
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/area?min_lat=55.5&max_lat=56.0&min_lng=37.3&max_lng=37.8');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'building',
                    'phones',
                    'activities'
                ]
            ]
        ]);

        // Test without parameters
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/area');

        $response->assertStatus(400);
    }

    /**
     * Test getting organization by ID
     */
    public function test_get_organization_by_id()
    {
        // Test with existing organization
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'building_id',
                'building' => [
                    'id',
                    'address',
                    'latitude',
                    'longitude'
                ],
                'phones' => [
                    '*' => [
                        'id',
                        'phone'
                    ]
                ],
                'activities' => [
                    '*' => [
                        'id',
                        'name',
                        'parent_id',
                        'level'
                    ]
                ]
            ]
        ]);

        // Test with non-existent organization
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/999');

        $response->assertStatus(404);
    }

    /**
     * Test activity tree search
     */
    public function test_search_organizations_by_activity_tree()
    {
        // Test with existing activity
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/activity-tree/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'building',
                    'phones',
                    'activities'
                ]
            ]
        ]);

        // Test with non-existent activity
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/activity-tree/999');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(0, $data);
    }

    /**
     * Test name search
     */
    public function test_search_organizations_by_name()
    {
        // Test with existing name
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/name?name=Рога');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'building',
                    'phones',
                    'activities'
                ]
            ]
        ]);

        // Test without name parameter
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/name');

        $response->assertStatus(400);

        // Test with non-existent name
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/name?name=НесуществующаяОрганизация');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(0, $data);
    }

    /**
     * Test edge cases for radius search
     */
    public function test_radius_search_edge_cases()
    {
        // Test with invalid coordinates
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/radius?latitude=invalid&longitude=invalid&radius=5');

        $response->assertStatus(400);

        // Test with very large radius
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/radius?latitude=55.7558&longitude=37.6176&radius=1000');

        $response->assertStatus(200);
    }

    /**
     * Test edge cases for area search
     */
    public function test_area_search_edge_cases()
    {
        // Test with invalid coordinates
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/area?min_lat=invalid&max_lat=invalid&min_lng=invalid&max_lng=invalid');

        $response->assertStatus(400);

        // Test with very large area
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/search/area?min_lat=0&max_lat=90&min_lng=-180&max_lng=180');

        $response->assertStatus(200);
    }

    /**
     * Test all endpoints return JSON
     */
    public function test_all_endpoints_return_json()
    {
        $endpoints = [
            '/api/buildings',
            '/api/organizations/building/1',
            '/api/organizations/activity/1',
            '/api/organizations/1',
            '/api/organizations/search/activity-tree/1',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
                ->get($endpoint);

            $response->assertHeader('Content-Type', 'application/json');
        }
    }

    /**
     * Test response structure consistency
     */
    public function test_response_structure_consistency()
    {
        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/buildings');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'address',
                    'latitude',
                    'longitude',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $response = $this->withHeaders(['X-API-Key' => $this->apiKey])
            ->get('/api/organizations/1');

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'building_id',
                'building' => [
                    'id',
                    'address',
                    'latitude',
                    'longitude'
                ],
                'phones' => [
                    '*' => [
                        'id',
                        'phone'
                    ]
                ],
                'activities' => [
                    '*' => [
                        'id',
                        'name',
                        'parent_id',
                        'level'
                    ]
                ]
            ]
        ]);
    }
} 
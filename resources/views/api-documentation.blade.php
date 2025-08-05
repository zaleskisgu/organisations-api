<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizations API Documentation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        h2 {
            color: #007bff;
            margin-top: 30px;
        }
        .endpoint {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        .get { background: #28a745; }
        .post { background: #007bff; }
        .put { background: #ffc107; color: #333; }
        .delete { background: #dc3545; }
        .url {
            font-family: monospace;
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 4px;
            color: #495057;
        }
        .description {
            margin: 10px 0;
            color: #666;
        }
        .parameters {
            margin: 10px 0;
        }
        .parameter {
            background: #fff;
            border: 1px solid #dee2e6;
            padding: 8px;
            margin: 5px 0;
            border-radius: 4px;
        }
        .response {
            background: #e8f5e8;
            border: 1px solid #28a745;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .auth-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .base-url {
            background: #007bff;
            color: white;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Organizations API Documentation</h1>
        
        <div class="base-url">
            <strong>Base URL:</strong> http://localhost:8000/api
        </div>

        <div class="auth-note">
            <strong>🔐 Authentication:</strong> All API endpoints require an API key to be included in the request header as <code>X-API-Key: your-api-key</code>
        </div>

        <h2>🏢 Buildings</h2>
        
        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/buildings</span>
            <div class="description">Get all buildings with their organizations</div>
            <div class="response">
                <strong>Response:</strong> JSON array of buildings with nested organizations
            </div>
        </div>

        <h2>🏢 Organizations</h2>
        
        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/organizations/building/{buildingId}</span>
            <div class="description">Get all organizations in a specific building</div>
            <div class="parameters">
                <strong>Parameters:</strong>
                <div class="parameter">buildingId (integer) - ID of the building</div>
            </div>
            <div class="response">
                <strong>Response:</strong> JSON array of organizations with phones and activities
            </div>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/organizations/activity/{activityId}</span>
            <div class="description">Get organizations by activity type</div>
            <div class="parameters">
                <strong>Parameters:</strong>
                <div class="parameter">activityId (integer) - ID of the activity</div>
            </div>
            <div class="response">
                <strong>Response:</strong> JSON array of organizations
            </div>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/organizations/{id}</span>
            <div class="description">Get organization by ID</div>
            <div class="parameters">
                <strong>Parameters:</strong>
                <div class="parameter">id (integer) - Organization ID</div>
            </div>
            <div class="response">
                <strong>Response:</strong> JSON object with organization details
            </div>
        </div>

        <h2>🔍 Search Endpoints</h2>
        
        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/organizations/search/radius?latitude={lat}&longitude={lng}&radius={r}</span>
            <div class="description">Search organizations within a radius</div>
            <div class="parameters">
                <strong>Parameters:</strong>
                <div class="parameter">latitude (float) - Latitude coordinate (-90 to 90)</div>
                <div class="parameter">longitude (float) - Longitude coordinate (-180 to 180)</div>
                <div class="parameter">radius (float) - Search radius in kilometers</div>
            </div>
            <div class="response">
                <strong>Response:</strong> JSON array of organizations within the radius
            </div>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/organizations/search/area?min_lat={min}&max_lat={max}&min_lng={min}&max_lng={max}</span>
            <div class="description">Search organizations in a rectangular area</div>
            <div class="parameters">
                <strong>Parameters:</strong>
                <div class="parameter">min_lat (float) - Minimum latitude</div>
                <div class="parameter">max_lat (float) - Maximum latitude</div>
                <div class="parameter">min_lng (float) - Minimum longitude</div>
                <div class="parameter">max_lng (float) - Maximum longitude</div>
            </div>
            <div class="response">
                <strong>Response:</strong> JSON array of organizations in the area
            </div>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/organizations/search/activity-tree/{activityId}</span>
            <div class="description">Search organizations by activity including all descendants</div>
            <div class="parameters">
                <strong>Parameters:</strong>
                <div class="parameter">activityId (integer) - ID of the activity (includes all child activities)</div>
            </div>
            <div class="response">
                <strong>Response:</strong> JSON array of organizations with the activity or its descendants
            </div>
        </div>

        <div class="endpoint">
            <span class="method get">GET</span>
            <span class="url">/organizations/search/name?name={search}</span>
            <div class="description">Search organizations by name</div>
            <div class="parameters">
                <strong>Parameters:</strong>
                <div class="parameter">name (string) - Organization name to search for</div>
            </div>
            <div class="response">
                <strong>Response:</strong> JSON array of organizations matching the name
            </div>
        </div>

        <h2>📊 Response Format</h2>
        <p>All successful responses follow this format:</p>
        <pre><code>{
  "success": true,
  "data": [...]
}</code></pre>

        <h2>❌ Error Responses</h2>
        <ul>
            <li><strong>400 Bad Request:</strong> Invalid parameters or missing required fields</li>
            <li><strong>401 Unauthorized:</strong> Missing or invalid API key</li>
            <li><strong>404 Not Found:</strong> Resource not found</li>
        </ul>

        <h2>🧪 Testing</h2>
        <p>You can test the API using the provided test script:</p>
        <pre><code>php test_api.php</code></pre>

        <h2>📝 Example Usage</h2>
        <p>Example curl command:</p>
        <pre><code>curl -H "X-API-Key: test-api-key-12345" \
     http://localhost:8000/api/buildings</code></pre>

        <hr style="margin: 40px 0;">
        <p><em>API Version: 1.0.0 | Last updated: {{ date('Y-m-d H:i:s') }}</em></p>
    </div>
</body>
</html> 
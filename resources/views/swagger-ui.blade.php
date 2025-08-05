<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizations API - Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.27.1/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin:0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background-color: #2c3e50;
        }
        .swagger-ui .topbar .download-url-wrapper .select-label {
            color: #fff;
        }
        .swagger-ui .topbar .download-url-wrapper input[type=text] {
            border: 2px solid #34495e;
        }
        .swagger-ui .info .title {
            color: #2c3e50;
        }
        .swagger-ui .scheme-container {
            background-color: #ecf0f1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .api-key-input {
            width: 300px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        .authorize-btn {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .authorize-btn:hover {
            background-color: #229954;
        }
        .clear-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        .clear-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    
    <script src="https://unpkg.com/swagger-ui-dist@5.27.1/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.27.1/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Конфигурация Swagger UI
            const ui = SwaggerUIBundle({
                url: '/docs',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                onComplete: function() {
                    // Добавляем кастомную кнопку авторизации
                    const authContainer = document.createElement('div');
                    authContainer.className = 'scheme-container';
                    authContainer.innerHTML = `
                        <h3>🔐 API Key Authentication</h3>
                        <p>Для тестирования API используйте API ключ: <code>test-api-key-12345</code></p>
                        <div style="margin: 10px 0;">
                            <input type="text" id="api-key-input" class="api-key-input" 
                                   placeholder="Введите API ключ" value="test-api-key-12345">
                            <button onclick="authorize()" class="authorize-btn">Authorize</button>
                            <button onclick="clearAuth()" class="clear-btn">Clear</button>
                        </div>
                        <p><strong>Инструкция:</strong></p>
                        <ol>
                            <li>Введите API ключ в поле выше</li>
                            <li>Нажмите "Authorize" для авторизации</li>
                            <li>Теперь вы можете тестировать все API endpoints</li>
                            <li>API ключ будет автоматически добавляться к каждому запросу</li>
                        </ol>
                    `;
                    
                    // Вставляем контейнер после заголовка
                    const topbar = document.querySelector('.topbar');
                    if (topbar) {
                        topbar.parentNode.insertBefore(authContainer, topbar.nextSibling);
                    }
                }
            });
            
            // Функция авторизации
            window.authorize = function() {
                const apiKey = document.getElementById('api-key-input').value;
                if (apiKey) {
                    // Устанавливаем API ключ для всех запросов
                    ui.preauthorizeApiKey('api_key', apiKey);
                    
                    // Показываем уведомление
                    const notification = document.createElement('div');
                    notification.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background-color: #27ae60;
                        color: white;
                        padding: 15px;
                        border-radius: 4px;
                        z-index: 9999;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                    `;
                    notification.textContent = '✅ API ключ установлен! Теперь вы можете тестировать API.';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            };
            
            // Функция очистки авторизации
            window.clearAuth = function() {
                ui.preauthorizeApiKey('api_key', '');
                document.getElementById('api-key-input').value = '';
                
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background-color: #e74c3c;
                    color: white;
                    padding: 15px;
                    border-radius: 4px;
                    z-index: 9999;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                `;
                notification.textContent = '🗑️ API ключ очищен!';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            };
        };
    </script>
</body>
</html> 
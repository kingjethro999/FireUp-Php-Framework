<?php include __DIR__ . '/layouts/app.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">API Tester</h1>
        <button id="save_request" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-save mr-2"></i>
            Save Request
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Request Panel -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="space-y-6">
                    <!-- Method and URL -->
                    <div class="flex space-x-4">
                        <div class="w-32">
                            <select id="method" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                                <option value="PATCH">PATCH</option>
                                <option value="DELETE">DELETE</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="text" id="url" placeholder="Enter URL" class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <button id="send_request" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send
                        </button>
                    </div>

                    <!-- Headers -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Headers</label>
                        <div id="headers_container" class="space-y-2">
                            <div class="flex space-x-2">
                                <input type="text" placeholder="Key" class="flex-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <input type="text" placeholder="Value" class="flex-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <button type="button" class="remove-header text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="add_header" class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i>
                            Add Header
                        </button>
                    </div>

                    <!-- Request Body -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Request Body</label>
                        <div class="flex space-x-2 mb-2">
                            <select id="body_type" class="block w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="json">JSON</option>
                                <option value="form">Form Data</option>
                                <option value="raw">Raw</option>
                            </select>
                        </div>
                        <textarea id="request_body" rows="6" class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Panel -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Response</h2>
                    <div class="flex space-x-2">
                        <button id="clear_response" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button id="copy_response" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Response Status -->
                <div id="response_status" class="mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                            Status: <span id="status_code">-</span>
                        </span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                            Time: <span id="response_time">-</span>
                        </span>
                    </div>
                </div>

                <!-- Response Headers -->
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Headers</h3>
                    <div id="response_headers" class="text-sm text-gray-600 space-y-1"></div>
                </div>

                <!-- Response Body -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Body</h3>
                    <pre id="response_body" class="text-sm text-gray-600 bg-gray-50 p-4 rounded-md overflow-auto max-h-96"></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Saved Requests -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Saved Requests</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Get Users</h3>
                            <p class="text-sm text-gray-500">GET /api/users</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Create Product</h3>
                            <p class="text-sm text-gray-500">POST /api/products</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const method = document.getElementById('method');
    const url = document.getElementById('url');
    const sendRequest = document.getElementById('send_request');
    const addHeader = document.getElementById('add_header');
    const headersContainer = document.getElementById('headers_container');
    const bodyType = document.getElementById('body_type');
    const requestBody = document.getElementById('request_body');
    const responseStatus = document.getElementById('response_status');
    const statusCode = document.getElementById('status_code');
    const responseTime = document.getElementById('response_time');
    const responseHeaders = document.getElementById('response_headers');
    const responseBody = document.getElementById('response_body');
    const clearResponse = document.getElementById('clear_response');
    const copyResponse = document.getElementById('copy_response');

    // Add new header
    addHeader.addEventListener('click', function() {
        const template = headersContainer.children[0].cloneNode(true);
        template.querySelectorAll('input').forEach(input => input.value = '');
        headersContainer.appendChild(template);
    });

    // Remove header
    headersContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-header')) {
            const headerRow = e.target.closest('.flex');
            if (headersContainer.children.length > 1) {
                headerRow.remove();
            }
        }
    });

    // Send request
    sendRequest.addEventListener('click', async function() {
        const startTime = performance.now();
        
        // Collect headers
        const headers = {};
        headersContainer.querySelectorAll('.flex').forEach(row => {
            const [key, value] = row.querySelectorAll('input');
            if (key.value && value.value) {
                headers[key.value] = value.value;
            }
        });

        // Prepare request options
        const options = {
            method: method.value,
            headers: headers
        };

        // Add body if needed
        if (['POST', 'PUT', 'PATCH'].includes(method.value) && requestBody.value) {
            if (bodyType.value === 'json') {
                options.headers['Content-Type'] = 'application/json';
                options.body = requestBody.value;
            } else if (bodyType.value === 'form') {
                const formData = new FormData();
                const data = JSON.parse(requestBody.value);
                Object.entries(data).forEach(([key, value]) => {
                    formData.append(key, value);
                });
                options.body = formData;
            } else {
                options.body = requestBody.value;
            }
        }

        try {
            const response = await fetch(url.value, options);
            const endTime = performance.now();

            // Update status
            statusCode.textContent = response.status;
            responseTime.textContent = `${Math.round(endTime - startTime)}ms`;

            // Update headers
            responseHeaders.innerHTML = Array.from(response.headers.entries())
                .map(([key, value]) => `<div>${key}: ${value}</div>`)
                .join('');

            // Update body
            const responseData = await response.json();
            responseBody.textContent = JSON.stringify(responseData, null, 2);
        } catch (error) {
            statusCode.textContent = 'Error';
            responseTime.textContent = '-';
            responseHeaders.innerHTML = '';
            responseBody.textContent = error.message;
        }
    });

    // Clear response
    clearResponse.addEventListener('click', function() {
        statusCode.textContent = '-';
        responseTime.textContent = '-';
        responseHeaders.innerHTML = '';
        responseBody.textContent = '';
    });

    // Copy response
    copyResponse.addEventListener('click', function() {
        navigator.clipboard.writeText(responseBody.textContent)
            .then(() => alert('Response copied to clipboard!'))
            .catch(err => alert('Error copying response: ' + err.message));
    });
});
</script> 
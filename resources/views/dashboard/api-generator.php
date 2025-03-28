<?php include __DIR__ . '/layouts/app.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">API Generator</h1>
        <button id="generate_apis" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-magic mr-2"></i>
            Generate APIs
        </button>
    </div>

    <!-- Model Selection -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Select Models</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="flex items-center p-4 border rounded-lg">
                <input type="checkbox" id="model_user" name="models[]" value="User" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="model_user" class="ml-3 block text-sm font-medium text-gray-700">
                    User
                </label>
            </div>
            <div class="flex items-center p-4 border rounded-lg">
                <input type="checkbox" id="model_product" name="models[]" value="Product" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="model_product" class="ml-3 block text-sm font-medium text-gray-700">
                    Product
                </label>
            </div>
            <div class="flex items-center p-4 border rounded-lg">
                <input type="checkbox" id="model_order" name="models[]" value="Order" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="model_order" class="ml-3 block text-sm font-medium text-gray-700">
                    Order
                </label>
            </div>
        </div>
    </div>

    <!-- API Options -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">API Options</h2>
        <form id="api_options_form" class="space-y-6">
            <!-- API Version -->
            <div>
                <label for="api_version" class="block text-sm font-medium text-gray-700">API Version</label>
                <input type="text" id="api_version" name="api_version" value="v1" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Base Controller -->
            <div>
                <label for="base_controller" class="block text-sm font-medium text-gray-700">Base Controller</label>
                <input type="text" id="base_controller" name="base_controller" value="FireUp\Http\Controllers\ApiController" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Endpoints -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Endpoints</label>
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="endpoints[]" value="index" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">GET /api/{resource}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="endpoints[]" value="show" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">GET /api/{resource}/{id}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="endpoints[]" value="store" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">POST /api/{resource}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="endpoints[]" value="update" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">PUT /api/{resource}/{id}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="endpoints[]" value="destroy" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">DELETE /api/{resource}/{id}</span>
                    </label>
                </div>
            </div>

            <!-- Features -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="pagination" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Pagination</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="filtering" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Filtering</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="sorting" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Sorting</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="relationships" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Relationships</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="validation" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Validation</span>
                    </label>
                </div>
            </div>

            <!-- Authentication -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Authentication</label>
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="auth[]" value="sanctum" class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Laravel Sanctum</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="auth[]" value="jwt" class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">JWT</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="auth[]" value="basic" class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Basic Auth</span>
                    </label>
                </div>
            </div>

            <!-- Documentation -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Documentation</label>
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="docs[]" value="swagger" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Swagger/OpenAPI</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="docs[]" value="postman" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Postman Collection</span>
                    </label>
                </div>
            </div>

            <!-- Generate Button -->
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-magic mr-2"></i>
                    Generate APIs
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateApisBtn = document.getElementById('generate_apis');
    const apiOptionsForm = document.getElementById('api_options_form');

    // Handle form submission
    apiOptionsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Add selected models
        const selectedModels = Array.from(document.querySelectorAll('input[name="models[]"]:checked'))
            .map(checkbox => checkbox.value);
        formData.append('models', JSON.stringify(selectedModels));
        
        fetch('/dashboard/api-generator/generate', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('APIs generated successfully!');
                // Optionally show the generated files
                if (data.files) {
                    console.log('Generated files:', data.files);
                }
            } else {
                alert('Error generating APIs: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error generating APIs: ' + error.message);
        });
    });
});
</script> 
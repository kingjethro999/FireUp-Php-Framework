<?php include __DIR__ . '/layouts/app.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Model Generator</h1>
        <button id="generate_models" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-magic mr-2"></i>
            Generate Models
        </button>
    </div>

    <!-- Table Selection -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Select Tables</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="flex items-center p-4 border rounded-lg">
                <input type="checkbox" id="table_users" name="tables[]" value="users" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="table_users" class="ml-3 block text-sm font-medium text-gray-700">
                    Users
                </label>
            </div>
            <div class="flex items-center p-4 border rounded-lg">
                <input type="checkbox" id="table_products" name="tables[]" value="products" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="table_products" class="ml-3 block text-sm font-medium text-gray-700">
                    Products
                </label>
            </div>
            <div class="flex items-center p-4 border rounded-lg">
                <input type="checkbox" id="table_orders" name="tables[]" value="orders" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="table_orders" class="ml-3 block text-sm font-medium text-gray-700">
                    Orders
                </label>
            </div>
        </div>
    </div>

    <!-- Model Options -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Model Options</h2>
        <form id="model_options_form" class="space-y-6">
            <!-- Namespace -->
            <div>
                <label for="namespace" class="block text-sm font-medium text-gray-700">Namespace</label>
                <input type="text" id="namespace" name="namespace" value="App\Models" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Base Model Class -->
            <div>
                <label for="base_class" class="block text-sm font-medium text-gray-700">Base Model Class</label>
                <input type="text" id="base_class" name="base_class" value="FireUp\Database\Model" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Features -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="timestamps" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Timestamps (created_at, updated_at)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="soft_deletes" class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Soft Deletes</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="fillable" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Fillable Properties</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="hidden" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Hidden Properties</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="features[]" value="casts" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Type Casting</span>
                    </label>
                </div>
            </div>

            <!-- Relationships -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Relationships</label>
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="relationships[]" value="has_many" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Has Many</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="relationships[]" value="belongs_to" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Belongs To</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="relationships[]" value="has_one" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Has One</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="relationships[]" value="belongs_to_many" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Belongs To Many</span>
                    </label>
                </div>
            </div>

            <!-- Validation Rules -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Validation Rules</label>
                <div class="space-y-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="validation[]" value="required" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Required Fields</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="validation[]" value="unique" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Unique Fields</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="validation[]" value="email" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Email Validation</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="validation[]" value="numeric" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Numeric Fields</span>
                    </label>
                </div>
            </div>

            <!-- Generate Button -->
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-magic mr-2"></i>
                    Generate Models
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateModelsBtn = document.getElementById('generate_models');
    const modelOptionsForm = document.getElementById('model_options_form');

    // Handle form submission
    modelOptionsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Add selected tables
        const selectedTables = Array.from(document.querySelectorAll('input[name="tables[]"]:checked'))
            .map(checkbox => checkbox.value);
        formData.append('tables', JSON.stringify(selectedTables));
        
        fetch('/dashboard/model-generator/generate', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Models generated successfully!');
                // Optionally show the generated files
                if (data.files) {
                    console.log('Generated files:', data.files);
                }
            } else {
                alert('Error generating models: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error generating models: ' + error.message);
        });
    });
});
</script> 
<?php include __DIR__ . '/layouts/app.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Table Builder</h1>
        <button id="create_table" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-plus mr-2"></i>
            Create New Table
        </button>
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Existing Tables</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Columns</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Modified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">users</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">8 columns</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table Builder Form -->
    <div id="table_builder_form" class="bg-white rounded-lg shadow-sm p-6 hidden">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Create New Table</h2>
        <form id="create_table_form" class="space-y-6">
            <!-- Table Name -->
            <div>
                <label for="table_name" class="block text-sm font-medium text-gray-700">Table Name</label>
                <input type="text" id="table_name" name="table_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Columns -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Columns</label>
                <div id="columns_container" class="space-y-4">
                    <!-- Column Template -->
                    <div class="column-row grid grid-cols-12 gap-4 items-start">
                        <div class="col-span-3">
                            <input type="text" name="columns[0][name]" placeholder="Column Name" class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="col-span-2">
                            <select name="columns[0][type]" class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="varchar">VARCHAR</option>
                                <option value="text">TEXT</option>
                                <option value="int">INT</option>
                                <option value="bigint">BIGINT</option>
                                <option value="decimal">DECIMAL</option>
                                <option value="datetime">DATETIME</option>
                                <option value="date">DATE</option>
                                <option value="boolean">BOOLEAN</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="columns[0][length]" placeholder="Length" class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="col-span-3">
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="columns[0][nullable]" class="form-checkbox h-4 w-4 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Nullable</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="columns[0][primary]" class="form-checkbox h-4 w-4 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Primary Key</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <button type="button" class="remove-column text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add_column" class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Add Column
                </button>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-save mr-2"></i>
                    Create Table
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const createTableBtn = document.getElementById('create_table');
    const tableBuilderForm = document.getElementById('table_builder_form');
    const addColumnBtn = document.getElementById('add_column');
    const columnsContainer = document.getElementById('columns_container');
    const createTableForm = document.getElementById('create_table_form');

    // Show/hide table builder form
    createTableBtn.addEventListener('click', function() {
        tableBuilderForm.classList.toggle('hidden');
    });

    // Add new column
    addColumnBtn.addEventListener('click', function() {
        const columnCount = columnsContainer.children.length;
        const template = columnsContainer.children[0].cloneNode(true);
        
        // Update input names with new index
        template.querySelectorAll('input, select').forEach(input => {
            input.name = input.name.replace('[0]', `[${columnCount}]`);
            if (input.type === 'text' || input.type === 'number') {
                input.value = '';
            }
        });

        columnsContainer.appendChild(template);
    });

    // Remove column
    columnsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-column')) {
            const columnRow = e.target.closest('.column-row');
            if (columnsContainer.children.length > 1) {
                columnRow.remove();
            }
        }
    });

    // Handle form submission
    createTableForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('/dashboard/table-builder/create', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Table created successfully!');
                location.reload();
            } else {
                alert('Error creating table: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error creating table: ' + error.message);
        });
    });
});
</script> 
<!-- filepath: c:\xampp\htdocs\pilot\resources\views\debug\search.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Debug Search API</h1>

    <div class="mb-6">
        <input
            type="text"
            id="search-query"
            class="px-4 py-2 border rounded-md w-full"
            placeholder="Enter search query..."
        >
        <button
            id="search-button"
            class="mt-2 px-6 py-2 bg-blue-600 text-white rounded-md"
        >
            Test Search
        </button>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold">Raw API Response:</h2>
        <pre id="raw-response" class="bg-gray-100 p-4 rounded-md mt-2 overflow-auto max-h-64"></pre>
    </div>

    <div>
        <h2 class="text-lg font-semibold">Formatted Results:</h2>
        <div id="formatted-results" class="mt-2"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-query');
    const searchButton = document.getElementById('search-button');
    const rawResponse = document.getElementById('raw-response');
    const formattedResults = document.getElementById('formatted-results');

    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();
        if (!query) return;

        rawResponse.textContent = 'Loading...';
        formattedResults.innerHTML = '';

        // Fix: Include error handling to display the full error response
        fetch(`/api/search?query=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(JSON.stringify(errorData));
                });
            }
            return response.json();
        })
        .then(data => {
            // Display raw JSON
            rawResponse.textContent = JSON.stringify(data, null, 2);

            // Count results
            const propertyCount = data.properties ? data.properties.length : 0;
            const subcategoryCount = data.subcategories ? data.subcategories.length : 0;

            // Create summary
            const summary = document.createElement('div');
            summary.className = 'p-4 bg-blue-50 rounded-md mb-4';
            summary.innerHTML = `
                <p class="font-medium">Found ${propertyCount} businesses and ${subcategoryCount} categories</p>
            `;
            formattedResults.appendChild(summary);

            // Format properties
            if (propertyCount > 0) {
                const propertiesHeader = document.createElement('h3');
                propertiesHeader.className = 'font-semibold text-lg mt-4 mb-2';
                propertiesHeader.textContent = 'Businesses:';
                formattedResults.appendChild(propertiesHeader);

                const propertiesList = document.createElement('div');
                propertiesList.className = 'grid grid-cols-1 md:grid-cols-2 gap-4';

                data.properties.forEach(property => {
                    const card = document.createElement('div');
                    card.className = 'border rounded-md p-4';
                    card.innerHTML = `
                        <p class="font-bold">${property.business_name}</p>
                        <p class="text-sm">${property.category} in ${property.city}, ${property.country}</p>
                        <p class="text-xs text-gray-500 mt-2">ID: ${property.id}</p>
                    `;
                    propertiesList.appendChild(card);
                });

                formattedResults.appendChild(propertiesList);
            }

            // Format subcategories
            if (subcategoryCount > 0) {
                const categoriesHeader = document.createElement('h3');
                categoriesHeader.className = 'font-semibold text-lg mt-4 mb-2';
                categoriesHeader.textContent = 'Categories:';
                formattedResults.appendChild(categoriesHeader);

                const categoriesList = document.createElement('div');
                categoriesList.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';

                data.subcategories.forEach(category => {
                    const card = document.createElement('div');
                    card.className = 'border rounded-md p-4';
                    card.innerHTML = `
                        <p class="font-bold">${category.name}</p>
                        <p class="text-sm text-gray-600">${category.description || 'No description'}</p>
                        <p class="text-xs text-gray-500 mt-2">Slug: ${category.slug}</p>
                    `;
                    categoriesList.appendChild(card);
                });

                formattedResults.appendChild(categoriesList);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            try {
                // Try to parse the error as JSON for better debugging
                const errorObj = JSON.parse(error.message);
                rawResponse.textContent = JSON.stringify(errorObj, null, 2);
            } catch (e) {
                rawResponse.textContent = 'Error fetching data: ' + error.message;
            }

            const errorMsg = document.createElement('div');
            errorMsg.className = 'p-4 bg-red-50 text-red-600 rounded-md';
            errorMsg.textContent = 'An error occurred while searching. See details above.';
            formattedResults.appendChild(errorMsg);
        });
    });

    // Enable Enter key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchButton.click();
        }
    });
});
</script>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown with Search</title>
    <style>
        /* Style for the dropdown container */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Style for the search input */
        .search-input {
       
			width:200px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Style for the dropdown list */
        .dropdown-list {
            position: absolute;
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 4px 4px;
            display: none;
        }

        .dropdown-list.show {
            display: block;
        }

        /* Style for dropdown list items */
        .dropdown-item {
            padding: 5px;
            cursor: pointer;
			width: 200px;
        }

        .dropdown-item:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="dropdown">
        <input type="text" class="search-input" placeholder="Search...">
        <div class="dropdown-list" id="dropdown-list">
            <option class="dropdown-item">Option 1</option>
            <option class="dropdown-item">Option 2</option>
            <option class="dropdown-item">Option 3</option>
            <option class="dropdown-item">Option 4</option>
            <option class="dropdown-item">Option 5</option>
            <option class="dropdown-item">Option 6</option>
            <option class="dropdown-item">Option 7</option>
            <!-- Add more options as needed -->
        </div>
    </div>

    <script>
        // JavaScript to handle the search functionality
        const searchInput = document.querySelector('.search-input');
        const dropdownList = document.getElementById('dropdown-list');
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        searchInput.addEventListener('input', function() {
            const searchText = searchInput.value.toLowerCase();

            dropdownItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchText)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Show or hide the dropdown list based on search results
            if (searchText.trim() === '') {
                dropdownList.classList.remove('show');
            } else {
                dropdownList.classList.add('show');
            }
        });

        // Handle item selection (optional)
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                searchInput.value = item.textContent;
                dropdownList.classList.remove('show');
            });
        });
    </script>
</body>
</html>


const searchInput = document.querySelector('.search-input-categorie');
const dropdownList = document.getElementById('categorie');
const dropdownItems = document.querySelectorAll('.dropdown-item-categorie');

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



const searchInput_item = document.querySelector('.search-input-items');
const dropdownList_item = document.getElementById('items');
const dropdownItems_item = document.querySelectorAll('.dropdown-item');

searchInput_item.addEventListener('input', function() {
	const searchText = searchInput_item.value.toLowerCase();

	dropdownItems_item.forEach(item => {
		const text = item.textContent.toLowerCase();
		if (text.includes(searchText)) {
			item.style.display = 'block';
		} else {
			item.style.display = 'none';
		}
	});

	// Show or hide the dropdown list based on search results
	if (searchText.trim() === '') {
		dropdownList_item.classList.remove('show');
	} else {
		dropdownList_item.classList.add('show');
	}
});

// Handle item selection (optional)
dropdownItems_item.forEach(item => {
	item.addEventListener('click', function() {
		searchInput_item.value = item.textContent;
		dropdownList_item.classList.remove('show');
	});
});

$('#categorie-dropdown').on(
	
  { "focus": function() {
      //console.log('clicked!', this, this.value);
          }
  , "change": function() {
      choice = $(this).val();
	  console.log(document.getElementById("product").style.display)
      //console.log('changed!', this, choice);
		
	if(document.getElementById("product").style.display == 'none'){
		document.getElementById("product").style.display = 'table-row';
	}
	else {
		document.getElementById("product").style.display = 'none';
	}
}		
    
  });
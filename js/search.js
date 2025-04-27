window.addEventListener("load", function() {
    document.getElementById("ingredients").addEventListener("input", function() {
        const query = this.value.split(',').pop().trim();  // Get the last typed word
        const suggestionsList = document.getElementById("suggestions-list");

        if (query.length > 0) {
            // Perform AJAX request
            fetch(`autocomplete.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    if (data.length > 0) {
                        suggestionsList.style.display = 'block';
                        data.forEach(item => {
                            const li = document.createElement("li");
                            li.textContent = item.name;
                            li.addEventListener('click', () => {
                                let currentInput = document.getElementById("ingredients").value;
                                let parts = currentInput.split(',');
                                parts[parts.length - 1] = item.name; // Replace last typed part
                                document.getElementById("ingredients").value = parts.join(', ') + ', ';
                                suggestionsList.style.display = 'none';
                            });
                            suggestionsList.appendChild(li);
                        });
                    } else {
                        suggestionsList.style.display = 'none';
                    }
                });
        } else {
            suggestionsList.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        const suggestionsList = document.getElementById("suggestions-list");
        if (!document.getElementById("ingredients").contains(event.target)) {
            suggestionsList.style.display = 'none';
        }
    });
});
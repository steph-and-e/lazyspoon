document.getElementById("ingredients").addEventListener("input", function() {
    const query = this.value;
    const suggestionsList = document.getElementById("suggestions-list");

    if (query.length > 0) {
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
                            document.getElementById("ingredients").value = item.name;
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

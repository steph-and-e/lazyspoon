window.addEventListener("load", function(event) {
    const input = document.getElementById("ingredients");
    const suggestionsList = document.getElementById("suggestions-list");
    const form = input.closest("form");  // Get the form element

    input.addEventListener("input", function() {
        const parts = this.value.split(',');
        const currentQuery = parts[parts.length - 1].trim();  // Only autocomplete the last part

        if (currentQuery.length > 0) {
            fetch(`autocomplete.php?query=${encodeURIComponent(currentQuery)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    if (data.length > 0) {
                        suggestionsList.style.display = 'block';
                        data.forEach(item => {
                            const li = document.createElement("li");
                            li.textContent = item.name;
                            li.style.cursor = 'pointer';
                            li.addEventListener('click', () => {
                                // Replace the last typed word with the clicked suggestion
                                parts[parts.length - 1] = item.name;
                                input.value = parts.join(', ') + ', '; // Add comma and space
                                suggestionsList.style.display = 'none';
                                input.focus(); // Refocus so user can keep typing

                                // Trigger input event manually so autocomplete shows for next ingredient
                                const event = new Event('input', { bubbles: true });
                                input.dispatchEvent(event);
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

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!input.contains(event.target) && !suggestionsList.contains(event.target)) {
            suggestionsList.style.display = 'none';
        }
    });

    // Clean the input value on form submission (before submitting the form)
    form.addEventListener("submit", function(event) {
        const inputValue = input.value.trim();

        // Manually trim any encoded commas before submitting
        const cleanValue = inputValue.replace(/%2C\+$/, '').replace(/,+$/, ''); // Remove trailing encoded commas and regular commas

        // Update the input field with the cleaned value before submitting
        input.value = cleanValue;

        // You can now proceed with the form submission
    });
});

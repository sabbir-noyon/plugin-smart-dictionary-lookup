document.addEventListener('dblclick', function () {
    const selectedText = window.getSelection().toString().trim();

    if (selectedText.length > 0 && sdl_vars.enable_popup ==="1") {
        const popup = document.getElementById('sdl-popup');
        const wordText = popup.querySelector('.sdl-selected-word');
        const definitionText = popup.querySelector('.sdl-definition-text');

        // Set popup theme
        popup.className = '';
        popup.classList.add(sdl_vars.popup_theme === 'dark' ? 'sdl-popup-dark' : 'sdl-popup-light');

        // Set popup position
        popup.style.top = '';
        popup.style.bottom = '';
        popup.style.left = '';
        popup.style.right = '';

        switch (sdl_vars.popup_position) {
            case 'top-left':
                popup.style.top = '40px';
                popup.style.left = '20px';
                break;
            case 'top-right':
                popup.style.top = '40px';
                popup.style.right = '20px';
                break;
            case 'bottom-left':
                popup.style.bottom = '20px';
                popup.style.left = '20px';
                break;
            default: // bottom-right
                popup.style.bottom = '20px';
                popup.style.right = '20px';
        }

        popup.style.display = 'block';
        wordText.textContent = selectedText;
        definitionText.textContent = 'Searching...';

        // AJAX Request
        fetch(sdl_vars.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams({
                action: 'sdl_fetch_data',
                word: selectedText
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                definitionText.textContent = data.data.definition;
            } else {
                definitionText.textContent = 'Definition not found.';
            }
        })
        .catch(() => {
            definitionText.textContent = 'Error fetching definition.';
        });
    }
});

// Close button functionality
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('sdl-close')) {
        const popup = document.getElementById('sdl-popup');
        popup.style.display = 'none';
    }
});

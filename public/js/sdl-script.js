document.addEventListener('dblclick', function () {
    const selectedText = window.getSelection().toString().trim();

    if (selectedText.length > 0) {
        const popup = document.getElementById('sdl-popup');
        
        // Create Close Button if not exists
        if (!document.getElementById('sdl-popup-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.id = 'sdl-popup-close';
            closeBtn.innerHTML = '&times;';
            closeBtn.addEventListener('click', () => {
                popup.style.display = 'none';
            });
            popup.appendChild(closeBtn);
        }

        popup.innerHTML = `
            <div class="sdl-selected">${selectedText}</div>
            <div class="sdl-definition">Searching definition...</div>
        `;
        
        // Append close button again (keep after content)
        const closeBtn = document.createElement('button');
        closeBtn.id = 'sdl-popup-close';
        closeBtn.innerHTML = '&times;';
        closeBtn.addEventListener('click', () => {
            popup.style.display = 'none';
        });
        popup.appendChild(closeBtn);

        popup.style.display = 'block';

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
            const definitionDiv = popup.querySelector('.sdl-definition');
            if (data.success) {
                definitionDiv.textContent = data.data.definition;
            } else {
                definitionDiv.textContent = 'Definition not found.';
            }
        })
        .catch(() => {
            const definitionDiv = popup.querySelector('.sdl-definition');
            definitionDiv.textContent = 'Error fetching definition.';
        });
    }
});

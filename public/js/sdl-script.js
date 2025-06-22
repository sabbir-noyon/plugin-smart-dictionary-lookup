document.addEventListener('dblclick', function () {
	const selectedText = window.getSelection().toString().trim();

	if (selectedText.length > 0) {
		const popup = document.getElementById('sdl-popup');
		popup.style.display = 'block';
		popup.textContent = `Searching for: ${selectedText}`;

		// Send AJAX request to WordPress backend.
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
				popup.textContent = `Definition of '${selectedText}' : ${data.data.definition}`;
			} else {
				popup.textContent = `Definition of '${selectedText}' : Definition not found.`;
			}
		})
		.catch(() => {
			popup.textContent = `Definition of '${selectedText}' : Error fetching definition.`;
		});
	}
});

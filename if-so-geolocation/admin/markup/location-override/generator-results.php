<div class="shortcode-generator-results">
    <div class="shortcode-generator-results-inner">
        <div class="shortcode-error"></div>

        <div class="shortcode-preview">
            <label class="preview-title">Form Preview</label>
            <div class="preview-container">
                <label>Select at least one location to see the preview</label>
            </div>
        </div>

        <label class="preview-title">Form Shortcode</label>
        <p class="instructions">
            Paste the shortcode anywhere on your site to display the location override form.
        </p>
        <textarea class="result-shortcode have-slim-scrollbar" placeholder="Your generated shortcode will appear here"></textarea>

        <div class="copy-button-container">
            <button class="copy-button have-copy-indicator" onclick="event.preventDefault(); locationOverrideGenerator.copyShortcode();">
                <span class="copy-button-icon">🗊</span>
                Copy Shortcode
            </button>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.have-copy-indicator').forEach(el => {
        el.addEventListener('click', function() {
            el.classList.add('active')
            setTimeout(function() { el.classList.remove('active') } , 1000)
        })
    })
</script>

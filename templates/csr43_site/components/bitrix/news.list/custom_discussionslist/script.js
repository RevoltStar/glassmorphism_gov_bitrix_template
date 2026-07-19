(function () {
    'use strict';

    function initDiscussionList(root) {
        if (!root || root.dataset.discussionsInitialized === 'true') {
            return;
        }

        var selector = root.querySelector('[data-discussion-selector]');
        var cards = root.querySelectorAll('[data-discussion-card]');
        var hint = root.querySelector('[data-discussion-hint]');

        if (!selector) {
            return;
        }

        root.dataset.discussionsInitialized = 'true';

        function getFeedbackForm() {
            return document.querySelector('form[name="SIMPLE_FORM_1"]');
        }

        function updateView() {
            var selectedValue = selector.value;
            var selectedText = selector.options[selector.selectedIndex]
                ? selector.options[selector.selectedIndex].text
                : '';
            var form = getFeedbackForm();
            var formTopic = document.querySelector('input[name="form_text_13"]');

            cards.forEach(function (card) {
                card.hidden = card.getAttribute('data-discussion-card') !== selectedValue;
            });

            if (hint) {
                hint.hidden = selectedValue !== '';
            }

            if (form) {
                form.style.display = selectedValue !== '' ? 'block' : 'none';
            }

            if (formTopic && selectedValue !== '') {
                formTopic.value = selectedText;
            }
        }

        selector.addEventListener('change', updateView);
        updateView();
    }

    function initAll() {
        document.querySelectorAll('[data-discussions-list]').forEach(initDiscussionList);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    document.addEventListener('onFrameDataReceived', initAll);
}());

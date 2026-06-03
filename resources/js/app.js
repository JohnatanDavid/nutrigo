import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

Alpine.plugin(persist);
window.Alpine = Alpine;
Alpine.start();

function clearInputs(container) {
	container.querySelectorAll('input[type="text"], input[type="number"], input[type="checkbox"]')
		.forEach((element) => {
			if (element.type === 'checkbox') {
				element.checked = false;
				return;
			}

			element.value = '';
		});

	container.querySelectorAll('select').forEach((element) => {
		element.selectedIndex = 0;
	});
}

function setActiveChoice(section, value) {
	const hiddenInput = section.querySelector('[data-choice-value]');
	const buttons = section.querySelectorAll('[data-choice-button]');
	const detail = section.querySelector('[data-choice-detail]');

	if (hiddenInput) {
		hiddenInput.value = value;
	}

	buttons.forEach((button) => {
		const isActive = button.dataset.choiceButton === value;
		button.classList.toggle('card-selector-active', isActive);
		button.classList.toggle('card-selector-inactive', !isActive);
		button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
	});

	if (detail) {
		const shouldShow = value === 'yes';
		detail.hidden = !shouldShow;

		if (!shouldShow) {
			clearInputs(detail);
		}
	}
}

function initOnboardingPreferences() {
	document.querySelectorAll('[data-onboarding-preferences]').forEach((section) => {
		const hiddenInput = section.querySelector('[data-choice-value]');
		const initialValue = hiddenInput?.value === 'yes' ? 'yes' : 'no';

		setActiveChoice(section, initialValue);

		section.addEventListener('click', (event) => {
			const button = event.target.closest('[data-choice-button]');
			if (!button || !section.contains(button)) {
				return;
			}

			setActiveChoice(section, button.dataset.choiceButton || 'no');
		});
	});
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initOnboardingPreferences);
} else {
	initOnboardingPreferences();
}
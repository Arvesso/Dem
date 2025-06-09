// simple phone input mask for +7(999)-999-99-99
window.addEventListener('DOMContentLoaded', function() {
  const phone = document.querySelector('input[name="phone"]');
  if (phone) {
    phone.addEventListener('input', function() {
      let digits = phone.value.replace(/\D/g, '');

      // remove country code if already present
      if (digits.startsWith('7')) {
        digits = digits.slice(1);
      }
      digits = digits.substring(0, 10);

      let formatted = '+7(' + digits.slice(0, 3);
      if (digits.length >= 3) {
        formatted += ')';
      }
      if (digits.length > 3) {
        formatted += '-' + digits.slice(3, 6);
      }
      if (digits.length > 6) {
        formatted += '-' + digits.slice(6, 8);
      }
      if (digits.length > 8) {
        formatted += '-' + digits.slice(8, 10);
      }
      phone.value = formatted;
    });
  }

  const cargoSelect = document.getElementById('cargo_type');
  if (cargoSelect) {
    cargoSelect.addEventListener('change', function() {
      if (cargoSelect.value === 'Мусор') {
        alert('Стоимость заказа увеличится из-за необходимости утилизации.');
      }
    });
  }

  const toggle = document.getElementById('theme-toggle');
  if (toggle) {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') {
      document.body.classList.add('dark');
    }
    toggle.addEventListener('click', function() {
      document.body.classList.toggle('dark');
      localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    });
  }
});

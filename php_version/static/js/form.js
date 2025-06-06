// simple phone input mask for +7(999)-999-99-99
window.addEventListener('DOMContentLoaded', function() {
  const phone = document.querySelector('input[name="phone"]');
  if (phone) {
    phone.addEventListener('input', function(e) {
      let digits = phone.value.replace(/\D/g, '').substring(0, 10);
      let formatted = '+7(';
    if (digits.length >= 3) {
      formatted += digits.slice(0,3) + ')-';
      if (digits.length >= 6) {
        formatted += digits.slice(3,6) + '-';
        if (digits.length >= 8) {
          formatted += digits.slice(6,8) + '-';
          formatted += digits.slice(8,10);
        } else {
          formatted += digits.slice(6);
        }
      } else {
        formatted += digits.slice(3);
      }
    } else {
      formatted += digits;
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
});

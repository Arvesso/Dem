// Additional UI interactions
window.addEventListener('DOMContentLoaded', () => {
  // intersection observer for fade-in elements
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('show');
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.fade').forEach(el => observer.observe(el));

  const orders = document.getElementById('orders-dynamic');
  if (orders) {
    orders.innerHTML = '<p>Загрузка...</p>';
    fetch('orders_ajax.php')
      .then(r => r.text())
      .then(html => {
        orders.innerHTML = html;
      });
  }
});


// AutoScan - Blade helpers
document.addEventListener('DOMContentLoaded', function () {
    // Logout form for method="POST" links
    document.querySelectorAll('a[method="POST"]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.href;
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
            form.appendChild(csrf);
            if (this.getAttribute('method')) {
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = this.getAttribute('method');
                form.appendChild(method);
            }
            document.body.appendChild(form);
            form.submit();
        });
    });

    // Auto-dismiss flash messages after 5s
    const flashes = document.querySelectorAll('[data-flash]');
    flashes.forEach(flash => {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.3s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 300);
        }, 5000);
    });
});

document.addEventListener('click', function (event) {
    if (event.target.matches('[data-confirm]')) {
        if (!confirm(event.target.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    }

    if (event.target.matches('.menu-toggle')) {
        document.querySelector('.sidebar')?.classList.toggle('open');
    }
});

document.querySelectorAll('input[type="search"][data-table]').forEach(function (input) {
    input.addEventListener('input', function () {
        var table = document.querySelector(input.dataset.table);
        var keyword = input.value.toLowerCase();
        table?.querySelectorAll('tbody tr').forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
});

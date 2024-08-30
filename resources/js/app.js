fetch('/api/users', {
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
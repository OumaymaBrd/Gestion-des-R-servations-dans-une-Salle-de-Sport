function toggleTable() {
    var tableContainer = document.getElementById('tableContainer');
    if (tableContainer.classList.contains('hidden')) {
        tableContainer.classList.remove('hidden');
    } else {
        tableContainer.classList.add('hidden');
    }
}
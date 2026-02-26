document.getElementById('add-classroom').addEventListener('click', function() {
    var modal = document.getElementById('modal');
    if (modal) {
        modal.style.display = 'block';
    }
});

document.getElementById('close-modal').addEventListener('click', function() {
    var modal = document.getElementById('modal');
    if (modal) {
        modal.style.display = 'none';
    }
});
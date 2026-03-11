document.addEventListener('DOMContentLoaded', function() {
    var deleteBtns = document.querySelectorAll('.btn-danger');
    deleteBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if(!confirm('确定删除？')) {
                e.preventDefault();
            }
        });
    });
});
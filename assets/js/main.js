document.addEventListener('DOMContentLoaded', function() {
    var backToTop = document.createElement('button');
    backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTop.style.cssText = 'position:fixed;bottom:30px;right:30px;width:50px;height:50px;background:#3498db;color:white;border:none;border-radius:50%;cursor:pointer;display:none;font-size:20px;box-shadow:0 2px 10px rgba(0,0,0,0.2);z-index:1000;';
    
    document.body.appendChild(backToTop);
    
    window.addEventListener('scroll', function() {
        if(window.scrollY > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });
    
    backToTop.addEventListener('click', function() {
        window.scrollTo({top:0,behavior:'smooth'});
    });
});
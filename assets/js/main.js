jQuery(document).ready(function($){
    $('.menu-toggle').click(function(){
        $('.nav-links').toggleClass('active');
    });

    $('.nav-links a').click(function(){
        if ($(window).width() < 900) {
            $('.nav-links').removeClass('active');
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".menu-toggle");
    const nav = document.querySelector(".nav-links");

    if (toggle && nav) {
        toggle.addEventListener("click", () => {
            nav.classList.toggle("active");
        });
    }
});
function toggleMenu() {
    const nav = document.getElementById("navLinks");
    nav.classList.toggle("active");
}

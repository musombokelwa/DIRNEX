document.addEventListener("DOMContentLoaded", () => {

  // Animation cartes au scroll
  const cards = document.querySelectorAll(".card, .about-card, .bio-card");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add("show");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  cards.forEach(card => observer.observe(card));

  // Hamburger menu
  const hamburger = document.getElementById("hamburger");
  const menu = document.getElementById("menu");
  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("open");
    menu.classList.toggle("active");
  });

});

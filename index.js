document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".card, .about-card, .bio-card, .service-card");

  if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    cards.forEach((card) => observer.observe(card));
  }

  const hamburger = document.getElementById("hamburger");
  const menu = document.getElementById("menu");

  if (hamburger && menu) {
    const toggleMenu = () => {
      hamburger.classList.toggle("open");
      menu.classList.toggle("active");
    };

    hamburger.addEventListener("click", toggleMenu);
    hamburger.addEventListener("keydown", (event) => {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        toggleMenu();
      }
    });
  }
});

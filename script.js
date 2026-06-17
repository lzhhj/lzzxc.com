// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
  var menuBtn = document.querySelector('.mobile-menu-btn');
  var navLinks = document.querySelector('.nav-links');
  if (menuBtn && navLinks) {
    menuBtn.addEventListener('click', function() {
      navLinks.classList.toggle('open');
    });
  }

  // FAQ accordion
  var faqQuestions = document.querySelectorAll('.faq-question');
  faqQuestions.forEach(function(q) {
    q.addEventListener('click', function() {
      this.classList.toggle('open');
      var answer = this.nextElementSibling;
      if (answer) {
        answer.classList.toggle('open');
      }
    });
  });
});
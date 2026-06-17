// script.js — Portfolio Kyla Nazwa Salsabila

document.addEventListener('DOMContentLoaded', () => {

  /* =========================================
     1. NAVBAR — scroll effect & hamburger
  ========================================= */
  const navbar = document.getElementById('navbar');
  const hamburger = document.getElementById('hamburger');
  const navLinks  = document.getElementById('nav-links');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navbar.style.boxShadow = '0 4px 16px rgba(0,0,0,0.08)';
    } else {
      navbar.style.boxShadow = 'none';
    }
  });

  hamburger?.addEventListener('click', () => {
    navLinks.classList.toggle('open');
  });

  // Close mobile menu on link click
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => navLinks.classList.remove('open'));
  });

  /* =========================================
     2. COUNTER ANIMATION — hero stats
  ========================================= */
  const counters = document.querySelectorAll('.stat-num[data-target]');

  const animateCounter = (el) => {
    const target = parseInt(el.dataset.target, 10);
    const duration = 1400;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(() => {
      current += step;
      if (current >= target) {
        el.textContent = target;
        clearInterval(timer);
      } else {
        el.textContent = Math.floor(current);
      }
    }, 16);
  };

  // Trigger when hero is visible
  const heroObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        counters.forEach(animateCounter);
        heroObserver.disconnect();
      }
    });
  }, { threshold: 0.3 });

  const heroSection = document.getElementById('home');
  if (heroSection) heroObserver.observe(heroSection);

  /* =========================================
     3. PROJECT FILTER
  ========================================= */
  const filterBtns  = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('.project-card');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelector('.filter-btn.active')?.classList.remove('active');
      btn.classList.add('active');

      const filter = btn.dataset.filter;
      projectCards.forEach(card => {
        const tags = (card.dataset.tags || '').split(' ');
        const show = filter === 'all' || tags.includes(filter);
        card.style.display = show ? '' : 'none';
        if (show) card.style.animation = 'fadeInUp 0.35s ease both';
      });
    });
  });

  /* =========================================
     4. DIAGRAM TABS
  ========================================= */
  const dtabBtns    = document.querySelectorAll('.dtab-btn');
  const diagContents = document.querySelectorAll('.diagram-content');

  dtabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelector('.dtab-btn.active')?.classList.remove('active');
      btn.classList.add('active');

      const target = btn.dataset.dtab;
      diagContents.forEach(content => {
        if (content.id === `diag-${target}`) {
          content.classList.add('active');
        } else {
          content.classList.remove('active');
        }
      });
    });
  });

  /* =========================================
     5. CONTACT FORM
  ========================================= */
  const form       = document.getElementById('contact-form');
  const successMsg = document.getElementById('form-success');

  // Internal submit handler (used by both addEventListener and global handleFormSubmit)
  function processFormSubmit(e) {
    e.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.querySelector('span').textContent = 'Mengirim…';

    const name = document.getElementById('form-name').value;
    const email = document.getElementById('form-email').value;
    const selectElement = document.getElementById('form-subject');
    const subject = selectElement.options[selectElement.selectedIndex].text;
    const message = document.getElementById('form-message').value;

    const waText = `Halo Kyla, saya ingin menghubungi Anda dari website portofolio.%0A%0A*Nama:* ${name}%0A*Email:* ${email}%0A*Subjek:* ${subject}%0A*Pesan:*%0A${message}`;
    const waUrl = `https://wa.me/6285659199540?text=${waText}`;

    // Simulate short loading to make it feel responsive
    setTimeout(() => {
      window.open(waUrl, '_blank');
      form.reset();
      
      // Also reset custom flyoui select text if present
      const flyTrigger = form.querySelector('.flyoui-select-trigger span');
      if (flyTrigger) flyTrigger.textContent = selectElement.options[0].text;
      const flyOptions = form.querySelectorAll('.flyoui-select-option');
      if (flyOptions.length > 0) {
        flyOptions.forEach(opt => opt.classList.remove('selected'));
        flyOptions[0].classList.add('selected');
      }

      successMsg.style.display = 'block';
      btn.disabled = false;
      btn.querySelector('span').textContent = 'Kirim Pesan';
      setTimeout(() => { successMsg.style.display = 'none'; }, 4000);
    }, 800);
  }

  // Remove inline onsubmit attribute to prevent double-firing
  if (form) {
    form.removeAttribute('onsubmit');
    form.addEventListener('submit', processFormSubmit);
  }

  /* =========================================
     6. SCROLL-IN ANIMATION (IntersectionObserver)
  ========================================= */
  const animatables = document.querySelectorAll(
    '.project-card, .timeline-item, .info-card, .skill-category, .sa-card, .cert-card, .edu-card, .highlight-item'
  );

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'fadeInUp 0.5s ease both';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  animatables.forEach(el => {
    el.style.opacity = '0';
    observer.observe(el);
  });

  /* =========================================
     7. SECTION ROUTING (SPA Mode)
  ========================================= */
  const sections = document.querySelectorAll('section');
  const navLinksList = document.querySelectorAll('.nav-link, .nav-logo');

  function handleRoute() {
    let hash = window.location.hash || '#home';
    
    // Check if section exists, if not default to home
    if (!document.querySelector(hash)) {
      hash = '#home';
    }

    // Hide all sections, show the active one
    sections.forEach(sec => {
      if ('#' + sec.id === hash) {
        sec.style.display = ''; // Restore to default display (block/flex)
        sec.style.animation = 'fadeInUp 0.4s ease both';
      } else {
        sec.style.display = 'none';
      }
    });

    // Update active state in navbar
    navLinksList.forEach(link => {
      if (link.getAttribute('href') === hash) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });

    // Reset scroll position to top
    window.scrollTo(0, 0);
  }

  // Listen for hash changes
  window.addEventListener('hashchange', handleRoute);
  
  // Initial route handling on page load
  handleRoute();

  /* =========================================
     8. CUSTOM FLYOUI DROPDOWN
  ========================================= */
  const selectElement = document.getElementById('form-subject');
  if (selectElement) {
    // Wrapper
    const wrapper = document.createElement('div');
    wrapper.className = 'flyoui-select-wrapper';
    selectElement.parentNode.insertBefore(wrapper, selectElement);
    wrapper.appendChild(selectElement);
    selectElement.style.display = 'none'; // Hide original select

    // Trigger
    const trigger = document.createElement('div');
    trigger.className = 'flyoui-select-trigger';
    trigger.innerHTML = `<span>${selectElement.options[selectElement.selectedIndex].text}</span>
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>`;
    wrapper.appendChild(trigger);

    // Options container
    const optionsContainer = document.createElement('div');
    optionsContainer.className = 'flyoui-select-options';
    
    Array.from(selectElement.options).forEach((option, index) => {
      const optionDiv = document.createElement('div');
      optionDiv.className = 'flyoui-select-option' + (option.selected ? ' selected' : '');
      if (option.value === "") optionDiv.classList.add('placeholder');
      optionDiv.textContent = option.text;
      
      optionDiv.addEventListener('click', () => {
        selectElement.selectedIndex = index;
        trigger.querySelector('span').textContent = option.text;
        wrapper.classList.remove('open');
        // Update selected class
        optionsContainer.querySelectorAll('.flyoui-select-option').forEach(opt => opt.classList.remove('selected'));
        optionDiv.classList.add('selected');
        
        // Trigger change event on original select for form validation
        const event = new Event('change');
        selectElement.dispatchEvent(event);
      });
      optionsContainer.appendChild(optionDiv);
    });
    
    wrapper.appendChild(optionsContainer);

    // Toggle dropdown
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      wrapper.classList.toggle('open');
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target)) {
        wrapper.classList.remove('open');
      }
    });
  }

});

/* =========================================
   CSS Animations (injected via JS)
========================================= */
const styleSheet = document.createElement('style');
styleSheet.textContent = `
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
`;
document.head.appendChild(styleSheet);

/* =========================================
   Global fallback for inline onsubmit (Bug fix)
========================================= */
function handleFormSubmit(event) {
  event.preventDefault();
  const form       = document.getElementById('contact-form');
  const successMsg = document.getElementById('form-success');
  if (!form || !successMsg) return;

  const btn = form.querySelector('button[type="submit"]');
  if (btn) {
    btn.disabled = true;
    const span = btn.querySelector('span');
    if (span) span.textContent = 'Mengirim…';
  }

  const name = document.getElementById('form-name').value;
  const email = document.getElementById('form-email').value;
  const selectElement = document.getElementById('form-subject');
  const subject = selectElement ? selectElement.options[selectElement.selectedIndex].text : '';
  const message = document.getElementById('form-message').value;

  const waText = `Halo Kyla, saya ingin menghubungi Anda dari website portofolio.%0A%0A*Nama:* ${name}%0A*Email:* ${email}%0A*Subjek:* ${subject}%0A*Pesan:*%0A${message}`;
  const waUrl = `https://wa.me/6285659199540?text=${waText}`;

  setTimeout(() => {
    window.open(waUrl, '_blank');
    form.reset();
    
    const flyTrigger = form.querySelector('.flyoui-select-trigger span');
    if (flyTrigger && selectElement) flyTrigger.textContent = selectElement.options[0].text;
    const flyOptions = form.querySelectorAll('.flyoui-select-option');
    if (flyOptions.length > 0) {
      flyOptions.forEach(opt => opt.classList.remove('selected'));
      flyOptions[0].classList.add('selected');
    }

    successMsg.style.display = 'block';
    if (btn) {
      btn.disabled = false;
      const span = btn.querySelector('span');
      if (span) span.textContent = 'Kirim Pesan';
    }
    setTimeout(() => { successMsg.style.display = 'none'; }, 4000);
  }, 800);
}

/* ===== DEMO MODAL ===== */
function openDemoModal(btnElement) {
  const card = btnElement.closest('.project-card');
  const title = card.querySelector('.project-title').innerText;
  
  const modal = document.getElementById('demoModal');
  const modalTitle = document.getElementById('demoModalTitle');
  const modalDesc = document.getElementById('demoDesc');
  const modalImage = document.getElementById('demoImage');

  modalTitle.innerText = "Demo UI: " + title;
  modalDesc.innerText = "Gambar di atas hanyalah contoh sementara. Anda dapat mengganti gambarnya dengan menyimpan file screenshot di folder assets dan mengubah src-nya di kode.";
  
  // Menggunakan gambar placeholder acak bertema teknologi
  modalImage.src = "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80";

  modal.classList.add('show');
}

function closeDemoModal() {
  document.getElementById('demoModal').classList.remove('show');
}

// Close modal when clicking outside of the content
window.addEventListener('click', function(event) {
  const modal = document.getElementById('demoModal');
  if (event.target === modal) {
    closeDemoModal();
  }
});

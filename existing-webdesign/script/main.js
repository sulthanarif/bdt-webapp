

document.addEventListener('DOMContentLoaded', () => {
  const tabPanels = Array.from(document.querySelectorAll('[data-tab-panel]'));
  const tabButtons = Array.from(document.querySelectorAll('[data-tab-button]'));
  const navLinks = Array.from(document.querySelectorAll('[data-nav-link]'));
  const tabTriggers = Array.from(document.querySelectorAll('[data-tab-target]'));
  const mainContent = document.getElementById('main-content');
  let accordionItems = [];

  const navbar = document.querySelector('[data-navbar]');
  const logoText = document.querySelector('[data-logo-text]');
  const menuToggle = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  const menuIconOpen = document.querySelector('[data-menu-icon="open"]');
  const menuIconClose = document.querySelector('[data-menu-icon="close"]');
  const scrollTopButton = document.querySelector('[data-scroll-top]');

  const linkMap = {
    daftar: '/daftar',
    whatsapp: 'https://wa.me/62811889829',
    whatsappGroup: 'https://wa.me/62811889829?text=Halo%20Admin%2C%20saya%20ingin%20mendaftarkan%20rombongan%20ke%20BDT',
    whatsappEvent: 'https://wa.me/62811889829?text=Halo%20Admin%2C%20saya%20ingin%20konsultasi%20untuk%20mengadakan%20acara%20di%20BDT',
    loketYearGeneral: 'https://www.loket.com/event/tikettahunanumumbdtpekan5januari2026', // tiket tahunan umum
    loketYearStudent: 'https://www.loket.com/event/tikettahunanpelajarbdtpekan5januari2026', // tiket tahunan pelajar
    loketQuarter: 'https://www.loket.com/event/tiket3bulananbdtpekan5januari2026', // tiket 3 bulanan
    loketMonth: 'https://www.loket.com/event/tiketbulananbdtpekan5januari2026', // tiket bulanan
    loketDaily: 'https://www.loket.com/event/tiketharianbdtpekan5januari2026', // tiket harian
    blog: 'https://blog.bacaditebet.id',
    email: 'mailto:bacaditebet@gmail.com',
    instagram: 'https://instagram.com/bacaditebet',
    maps: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2087279435477!2d106.8470392!3d-6.236195299999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3d2517ea14b%3A0xe276c06b5f3b3c3b!2sBaca%20Di%20Tebet%20Perpustakaan%20dan%20Ruang%20Temu!5e0!3m2!1sen!2sid!4v1768049825195!5m2!1sen!2sidhttps://maps.app.goo.gl/8G837VxrUoBRDop97'
  };

  document.querySelectorAll('[data-link]').forEach((link) => {
    const key = link.dataset.link;
    const url = linkMap[key];
    if (url) {
      link.setAttribute('href', url);
    }
  });

  const setActiveTab = (id, options = {}) => {
    const { scroll = false } = options;

    tabPanels.forEach((panel) => {
      panel.classList.toggle('hidden', panel.dataset.tabPanel !== id);
    });

    tabButtons.forEach((button) => {
      const isActive = button.dataset.tabTarget === id;
      button.classList.toggle('bg-teal-700', isActive);
      button.classList.toggle('text-white', isActive);
      button.classList.toggle('shadow-lg', isActive);
      button.classList.toggle('scale-105', isActive);
      button.classList.toggle('bg-white', !isActive);
      button.classList.toggle('text-slate-500', !isActive);
      button.classList.toggle('border', !isActive);
      button.classList.toggle('border-slate-200', !isActive);
    });

    navLinks.forEach((link) => {
      const isActive = link.dataset.tabTarget === id;
      link.classList.toggle('text-teal-700', isActive);
      link.classList.toggle('font-bold', isActive);
      link.classList.toggle('text-slate-600', !isActive);
    });

    if (scroll && mainContent) {
      mainContent.scrollIntoView({ behavior: 'smooth' });
    }

    if (id === 'pricing' && accordionItems.length > 0) {
      const openItem = accordionItems.find((item) => item.dataset.open === 'true');
      if (openItem) {
        const content = openItem.querySelector('[data-accordion-content]');
        if (content) {
          content.style.maxHeight = `${content.scrollHeight}px`;
        }
      }
    }

    // Dispatch custom event untuk tab change
    const tabChangedEvent = new CustomEvent('tabChanged', {
      detail: { tabId: id }
    });
    document.dispatchEvent(tabChangedEvent);

    closeMobileMenu();
  };

  const closeMobileMenu = () => {
    if (!mobileMenu || !menuToggle) return;
    mobileMenu.classList.add('hidden');
    menuToggle.setAttribute('aria-expanded', 'false');
    if (menuIconOpen && menuIconClose) {
      menuIconOpen.classList.remove('hidden');
      menuIconClose.classList.add('hidden');
    }
  };

  tabTriggers.forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      const target = trigger.dataset.tabTarget;
      if (!target) return;
      const shouldScroll = trigger.dataset.scroll === 'true';
      setActiveTab(target, { scroll: shouldScroll });
    });
  });

  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
      const isHidden = mobileMenu.classList.toggle('hidden');
      const isOpen = !isHidden;
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (menuIconOpen && menuIconClose) {
        menuIconOpen.classList.toggle('hidden', isOpen);
        menuIconClose.classList.toggle('hidden', !isOpen);
      }
    });
  }

  if (scrollTopButton) {
    scrollTopButton.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  const updateNavbar = () => {
    if (!navbar) return;
    const isScrolled = window.scrollY > 50;
    navbar.classList.toggle('bg-white', isScrolled);
    navbar.classList.toggle('shadow-md', isScrolled);
    navbar.classList.toggle('py-4', isScrolled);
    navbar.classList.toggle('bg-transparent', !isScrolled);
    navbar.classList.toggle('py-6', !isScrolled);

    if (logoText) {
      logoText.classList.toggle('text-slate-800', isScrolled);
      logoText.classList.toggle('text-slate-900', !isScrolled);
    }
  };

  updateNavbar();
  window.addEventListener('scroll', updateNavbar);

  // Check for tab parameter in URL
  const urlParams = new URLSearchParams(window.location.search);
  const initialTab = urlParams.get('tab') || 'about';
  setActiveTab(initialTab, { scroll: !!urlParams.get('tab') });

  accordionItems = Array.from(document.querySelectorAll('[data-accordion-item]'));

  const closeAccordionItem = (item) => {
    const content = item.querySelector('[data-accordion-content]');
    const icon = item.querySelector('[data-accordion-icon]');
    item.dataset.open = 'false';
    item.querySelector('[data-accordion-header]')?.setAttribute('aria-expanded', 'false');
    if (content) {
      content.style.maxHeight = '0px';
    }
    if (icon) {
      icon.classList.remove('rotate-45');
    }
  };

  const openAccordionItem = (item) => {
    const content = item.querySelector('[data-accordion-content]');
    const icon = item.querySelector('[data-accordion-icon]');
    item.dataset.open = 'true';
    item.querySelector('[data-accordion-header]')?.setAttribute('aria-expanded', 'true');
    if (content) {
      content.style.maxHeight = `${content.scrollHeight}px`;
    }
    if (icon) {
      icon.classList.add('rotate-45');
    }
  };

  accordionItems.forEach((item) => {
    const header = item.querySelector('[data-accordion-header]');
    if (!header) return;

    header.addEventListener('click', () => {
      const isOpen = item.dataset.open === 'true';
      const openItem = accordionItems.find((candidate) => candidate.dataset.open === 'true');
      if (openItem && openItem !== item) {
        closeAccordionItem(openItem);
      }

      if (isOpen) {
        closeAccordionItem(item);
      } else {
        openAccordionItem(item);
      }
    });
  });

  if (accordionItems.length > 0) {
    openAccordionItem(accordionItems[0]);
  }

  window.addEventListener('resize', () => {
    const openItem = accordionItems.find((item) => item.dataset.open === 'true');
    if (!openItem) return;
    const content = openItem.querySelector('[data-accordion-content]');
    if (content) {
      content.style.maxHeight = `${content.scrollHeight}px`;
    }
  });

  const yearEl = document.getElementById('copyright-year');
  if (yearEl) {
    yearEl.textContent = String(new Date().getFullYear());
  }

  if (window.lucide && window.lucide.createIcons) {
    window.lucide.createIcons();
  }
});

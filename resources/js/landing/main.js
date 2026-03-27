

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

  const defaultLinkMap = {
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
  const linkMap = { ...defaultLinkMap, ...(window.landingLinks || {}) };

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

  const membershipModal = document.querySelector('[data-membership-modal]');
  const membershipInput = document.querySelector('[data-membership-input]');
  const studentFields = Array.from(document.querySelectorAll('[data-student-fields]'));
  const memberFields = Array.from(document.querySelectorAll('[data-member-fields]'));
  const membershipOpeners = Array.from(document.querySelectorAll('[data-membership-open]'));
  const membershipClosers = Array.from(document.querySelectorAll('[data-membership-close]'));
  const membershipTitle = document.querySelector('[data-membership-title]');
  const membershipOptions = Array.from(document.querySelectorAll('[data-membership-option]'));
  const stepper = document.querySelector('[data-membership-stepper]');
  const memberSteps = Array.from(document.querySelectorAll('[data-member-step]'));
  const stepIndicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
  const stepLabels = Array.from(document.querySelectorAll('[data-step-label]'));
  const stepNext = document.querySelector('[data-step-next]');
  const stepBack = document.querySelector('[data-step-back]');
  const submitButton = document.querySelector('[data-submit-button]');
  const dailyFields = document.querySelector('[data-daily-fields]');
  const modalStep = Number(membershipModal?.dataset?.step || 1);
  const membershipForm = document.querySelector('[data-membership-form]');
  const paymentOpenButton = document.querySelector('[data-payment-open]');
  const paymentModal = document.querySelector('[data-payment-modal]');
  const paymentCloseButtons = Array.from(document.querySelectorAll('[data-payment-close]'));
  const paymentSubmitButton = document.querySelector('[data-payment-submit]');
  const paymentCountdown = document.querySelector('[data-payment-countdown]');
  let paymentTimerId = null;

  const requiredRules = {
    name: ['member', 'daily'],
    gender: ['member', 'daily'],
    qty: ['daily'],
    nik: ['member'],
    birth_date: ['member'],
    domicile: ['member'],
    nim: ['student'],
    university: ['student'],
    payment_method: ['member', 'daily'],
  };

  const isFieldRequiredForMode = (name) => {
    const rules = requiredRules[name];
    if (!rules) return false;
    if (isDailyMode) {
      return rules.includes('daily');
    }
    if (rules.includes('member')) {
      return true;
    }
    return isStudentMode && rules.includes('student');
  };

  const setRequiredForStep = (step) => {
    if (!membershipForm) return;
    const fields = Array.from(membershipForm.querySelectorAll('input, select, textarea'));
    fields.forEach((field) => {
      const name = field.getAttribute('name');
      if (!name || !Object.prototype.hasOwnProperty.call(requiredRules, name)) {
        field.required = false;
        return;
      }
      const stepContainer = field.closest('[data-member-step]');
      const fieldStep = Number(stepContainer?.dataset?.memberStep || 0);
      const isHidden = stepContainer?.classList.contains('hidden');
      if (isHidden) {
        field.required = false;
        return;
      }
      field.required = fieldStep === step && isFieldRequiredForMode(name);
    });
  };

  // Global flag to track if current mode is daily ticket or member
  let isDailyMode = false;
  let isStudentMode = false;

  const membershipMap = new Map();
  membershipOpeners.forEach((opener) => {
    const id = opener.dataset.membershipId;
    if (!id) return;
    membershipMap.set(id, {
      name: opener.dataset.membershipName || '',
      price: Number(opener.dataset.membershipPrice || 0),
      isStudent: opener.dataset.membershipStudent === '1',
      kind: opener.dataset.membershipKind || 'member',
    });
  });

  const formatCurrency = (value) => {
    const numberValue = Number.isFinite(value) ? value : 0;
    return `Rp ${numberValue.toLocaleString('id-ID')}`;
  };

  let renewalState = { isRenewal: false, discountAmount: 0, durationBonus: 0, finalPrice: null, campaignName: null };
  let voucherState = { valid: false, discountAmount: 0, durationBonus: 0, finalPrice: null, campaignName: null };

  const updatePaymentSummary = () => {
    if (!membershipInput) return;
    const data = membershipMap.get(membershipInput.value);
    if (!data) return;

    const qtyInput = membershipForm?.querySelector('input[name="qty"]');
    const qty = isDailyMode ? Number(qtyInput?.value || 1) : 1;
    const originalTotal = data.price * (qty || 1);

    const voucherCode = membershipForm?.querySelector('[data-voucher-input]')?.value.trim();
    const activeState = !isDailyMode && voucherCode && voucherState.valid ? voucherState : renewalState;
    const discountAmount = !isDailyMode && activeState.finalPrice !== null
      ? Math.max(0, originalTotal - activeState.finalPrice)
      : 0;
    const total = discountAmount > 0 ? activeState.finalPrice : originalTotal;
    const method = membershipForm?.querySelector('input[name="payment_method"]:checked')?.value;
    const methodLabel = method === 'bank_transfer' ? 'Transfer Bank' : 'QRIS';

    document.querySelectorAll('[data-payment-package]').forEach((el) => {
      el.textContent = data.name;
    });
    document.querySelectorAll('[data-payment-qty]').forEach((el) => {
      el.textContent = isDailyMode ? `${qty} orang` : '1 paket';
    });
    document.querySelectorAll('[data-payment-method]').forEach((el) => {
      el.textContent = methodLabel;
    });
    document.querySelectorAll('[data-payment-total]').forEach((el) => {
      el.textContent = formatCurrency(total);
    });

    const discountRow = document.querySelector('[data-discount-row]');
    const discountEl = document.querySelector('[data-payment-discount]');
    if (discountRow && discountEl) {
      if (!isDailyMode && discountAmount > 0) {
        discountRow.classList.remove('hidden');
        discountRow.classList.add('flex');
        discountEl.textContent = '- ' + formatCurrency(discountAmount);
      } else {
        discountRow.classList.add('hidden');
        discountRow.classList.remove('flex');
      }
    }
  };

  const updateStudentFields = (isStudent) => {
    const shouldShow = Boolean(isStudent);

    studentFields.forEach((field) => {
      field.classList.toggle('hidden', !shouldShow);
      field.querySelectorAll('input').forEach((input) => {
        input.required = shouldShow;
      });
    });
  };

  const updateMemberFields = (isMember) => {
    const shouldShow = Boolean(isMember);

    memberFields.forEach((field) => {
      field.classList.toggle('hidden', !shouldShow);
      field.querySelectorAll('input, select').forEach((input) => {
        input.required = shouldShow;
      });
    });
  };

  const setStep = (step, maxSteps = 3) => {
    // First, toggle visibility based on step number
    memberSteps.forEach((section) => {
      const sectionStep = Number(section.dataset.memberStep);
      const shouldShow = sectionStep === step;

      // Check if this is a member-only or daily-only field
      const isMemberField = section.hasAttribute('data-member-fields');
      const isStudentField = section.hasAttribute('data-student-fields');
      const isDailyField = section.hasAttribute('data-daily-fields');

      // Apply visibility logic
      if (shouldShow) {
        // Only show if it matches the current flow type
        if ((isMemberField || isStudentField) && isDailyMode) {
          // Member-only field in daily flow - KEEP HIDDEN
          section.classList.add('hidden');
        } else if (isStudentField && !isStudentMode) {
          // Student-only field in non-student member flow
          section.classList.add('hidden');
        } else if (isDailyField && !isDailyMode) {
          // Daily-only field in member flow - keep hidden
          section.classList.add('hidden');
        } else {
          // Show the field
          section.classList.remove('hidden');
        }
      } else {
        // Wrong step - hide it
        section.classList.add('hidden');
      }
    });

    stepIndicators.forEach((indicator) => {
      const indicatorStep = Number(indicator.dataset.stepIndicator);
      indicator.classList.toggle('bg-teal-600', indicatorStep === step);
      indicator.classList.toggle('text-white', indicatorStep === step);
      indicator.classList.toggle('border-teal-100', indicatorStep === step);
      indicator.classList.toggle('shadow-md', indicatorStep === step);
      indicator.classList.toggle('bg-white', indicatorStep !== step);
      indicator.classList.toggle('text-slate-400', indicatorStep !== step);
      indicator.classList.toggle('border-slate-200', indicatorStep !== step);
      indicator.classList.toggle('shadow-sm', indicatorStep !== step);
    });
    stepLabels.forEach((label) => {
      const labelStep = Number(label.dataset.stepLabel);
      label.classList.toggle('text-teal-700', labelStep === step);
      label.classList.toggle('text-slate-400', labelStep !== step);
    });
    if (stepBack) {
      stepBack.classList.toggle('hidden', step === 1);
    }
    if (stepNext) {
      stepNext.classList.toggle('hidden', step === maxSteps);
    }
    if (submitButton) {
      submitButton.classList.toggle('hidden', step !== maxSteps);
    }

    setRequiredForStep(step);
    if (step === maxSteps) {
      updatePaymentSummary();
    }
  };

  const setMembership = (memberTypeId) => {
    if (!membershipInput) return;
    if (memberTypeId) {
      membershipInput.value = memberTypeId;
    }

    const data = membershipMap.get(membershipInput.value);
    if (!data) {
      updateStudentFields(false);
      updateMemberFields(false);
      return;
    }

    const prefix = data.kind === 'ticket' ? 'Form Pembelian Tiket' : 'Form Pendaftaran Anggota';
    if (membershipTitle) {
      membershipTitle.textContent = `${prefix} - ${data.name}`;
    }
    const isDaily = data.kind === 'ticket';

    // Set global flags
    isDailyMode = isDaily;
    isStudentMode = !isDaily && data.isStudent;

    // Update member-specific fields (NIK, birth date, domicile)
    updateMemberFields(!isDaily);

    if (isDaily) {
      updateStudentFields(false);
    } else {
      updateStudentFields(isStudentMode);
    }
    if (dailyFields) {
      dailyFields.classList.toggle('hidden', !isDaily);
      dailyFields.querySelectorAll('select, input').forEach((input) => {
        input.required = isDaily;
      });
    }

    // Update stepper for member (4 steps) vs daily (3 steps)
    const step4 = document.querySelector('[data-step-4]');
    const stepperGrid = document.querySelector('[data-stepper-grid]');

    if (isDaily) {
      // Daily: 3 steps
      if (step4) step4.classList.add('hidden');
      if (stepperGrid) stepperGrid.classList.remove('grid-cols-4');
      if (stepperGrid) stepperGrid.classList.add('grid-cols-3');

      if (stepper && stepLabels.length >= 3) {
        stepLabels[0].textContent = 'Data Pemesanan';
        stepLabels[1].textContent = 'Konfirmasi';
        stepLabels[2].textContent = 'Pembayaran';
      }
      window.currentMaxSteps = 3;
    } else {
      // Member: 4 steps
      if (step4) step4.classList.remove('hidden');
      if (stepperGrid) stepperGrid.classList.remove('grid-cols-3');
      if (stepperGrid) stepperGrid.classList.add('grid-cols-4');

      if (stepper && stepLabels.length >= 4) {
        stepLabels[0].textContent = 'Data Utama';
        stepLabels[1].textContent = 'Detail';
        stepLabels[2].textContent = 'Konfirmasi';
        stepLabels[3].textContent = 'Pembayaran';
      }
      window.currentMaxSteps = 4;
    }

    // Always show stepper for both member and daily ticket flows
    if (stepper) {
      stepper.classList.remove('hidden');
    }

    // Set initial step
    setStep(modalStep, window.currentMaxSteps || 3);
  };

  const openMembershipModal = (memberTypeId) => {
    if (!membershipModal) return;
    membershipModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    setMembership(memberTypeId);
  };

  const closeMembershipModal = () => {
    if (!membershipModal) return;
    membershipModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  };

  if (membershipOpeners.length > 0) {
    membershipOpeners.forEach((trigger) => {
      trigger.addEventListener('click', (event) => {
        event.preventDefault();
        openMembershipModal(trigger.dataset.membershipId);
      });
    });
  }

  if (membershipClosers.length > 0) {
    membershipClosers.forEach((trigger) => {
      trigger.addEventListener('click', () => closeMembershipModal());
    });
  }

  if (membershipModal) {
    const shouldOpen = membershipModal.dataset.open === 'true';
    if (shouldOpen) {
      openMembershipModal(membershipInput?.value);
    }
  }

  if (stepNext) {
    stepNext.addEventListener('click', () => {
      const current = Number(stepIndicators.find((indicator) => indicator.classList.contains('bg-teal-600'))?.dataset?.stepIndicator || 1);
      const maxSteps = window.currentMaxSteps || 3;
      const nextStep = Math.min(current + 1, maxSteps);

      // Validate current step before moving forward
      setRequiredForStep(current);
      if (membershipForm && !membershipForm.checkValidity()) {
        membershipForm.reportValidity();
        return;
      }

      // Populate confirmation data for daily tickets when moving to step 2
      if (nextStep === 2 && dailyFields && !dailyFields.classList.contains('hidden')) {
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        const phoneInput = document.querySelector('input[name="phone"]');
        const genderInput = document.querySelector('select[name="gender"]');
        const qtyInput = document.querySelector('input[name="qty"]');

        if (nameInput) document.querySelector('[data-confirm-name]').textContent = nameInput.value || '-';
        if (emailInput) document.querySelector('[data-confirm-email]').textContent = emailInput.value || '-';
        if (phoneInput) document.querySelector('[data-confirm-phone]').textContent = phoneInput.value || '-';
        if (genderInput) {
          const genderText = genderInput.options[genderInput.selectedIndex]?.text || '-';
          document.querySelector('[data-confirm-gender]').textContent = genderText;
        }
        if (qtyInput) document.querySelector('[data-confirm-qty]').textContent = qtyInput.value || '1';
      }

      // Populate confirmation data for members when moving to step 3
      if (nextStep === 3 && !dailyFields.classList.contains('hidden') === false) {
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        const phoneInput = document.querySelector('input[name="phone"]');
        const genderInput = document.querySelector('select[name="gender"]');
        const nikInput = document.querySelector('input[name="nik"]');
        const birthDateInput = document.querySelector('input[name="birth_date"]');
        const domicileInput = document.querySelector('input[name="domicile"]');
        const nimInput = document.querySelector('input[name="nim"]');
        const universityInput = document.querySelector('input[name="university"]');

        if (nameInput) document.querySelector('[data-confirm-member-name]').textContent = nameInput.value || '-';
        if (emailInput) document.querySelector('[data-confirm-member-email]').textContent = emailInput.value || '-';
        if (phoneInput) document.querySelector('[data-confirm-member-phone]').textContent = phoneInput.value || '-';
        if (genderInput) {
          const genderText = genderInput.options[genderInput.selectedIndex]?.text || '-';
          document.querySelector('[data-confirm-member-gender]').textContent = genderText;
        }
        if (nikInput) document.querySelector('[data-confirm-member-nik]').textContent = nikInput.value || '-';
        if (birthDateInput) document.querySelector('[data-confirm-member-birthdate]').textContent = birthDateInput.value || '-';
        if (domicileInput) document.querySelector('[data-confirm-member-domicile]').textContent = domicileInput.value || '-';

        const studentConfirmFields = Array.from(document.querySelectorAll('[data-confirm-member-student]'));
        const isStudentMember = nimInput && nimInput.offsetParent !== null;
        studentConfirmFields.forEach(field => field.classList.toggle('hidden', !isStudentMember));

        if (isStudentMember) {
          if (nimInput) document.querySelector('[data-confirm-member-nim]').textContent = nimInput.value || '-';
          if (universityInput) document.querySelector('[data-confirm-member-university]').textContent = universityInput.value || '-';
        }
      }

      setStep(nextStep, maxSteps);
    });
  }

  if (stepBack) {
    stepBack.addEventListener('click', () => {
      const current = Number(stepIndicators.find((indicator) => indicator.classList.contains('bg-teal-600'))?.dataset?.stepIndicator || 1);
      const maxSteps = window.currentMaxSteps || 3;
      setStep(Math.max(current - 1, 1), maxSteps);
    });
  }

  // NIK-based renewal & discount detection
  const nikInput = membershipForm?.querySelector('[data-nik-input]');
  const nikChecking = document.querySelector('[data-nik-checking]');
  const renewalBanner = document.querySelector('[data-renewal-banner]');
  const renewalBannerTitle = document.querySelector('[data-renewal-banner-title]');
  const renewalBannerDesc = document.querySelector('[data-renewal-banner-desc]');
  let nikCheckTimer = null;

  const resetRenewalState = () => {
    renewalState.isRenewal = false;
    renewalState.discountAmount = 0;
    renewalState.durationBonus = 0;
    renewalState.finalPrice = null;
    renewalState.campaignName = null;
    if (renewalBanner) renewalBanner.classList.add('hidden');
  };

  const checkNik = async (nik) => {
    const memberTypeId = membershipInput?.value;
    if (!nik || !memberTypeId) { resetRenewalState(); return; }
    if (nikChecking) nikChecking.classList.remove('hidden');
    try {
      const url = `/api/membership/check-nik?nik=${encodeURIComponent(nik)}&member_type_id=${encodeURIComponent(memberTypeId)}`;
      const response = await fetch(url, { headers: { Accept: 'application/json' } });
      if (!response.ok) throw new Error('Network error');
      const result = await response.json();

      renewalState.isRenewal = result.is_renewal;
      renewalState.discountAmount = result.discount_amount || 0;
      renewalState.durationBonus = result.duration_bonus || 0;
      renewalState.finalPrice = result.discount_amount > 0 ? result.final_price : null;
      renewalState.campaignName = result.campaign?.name || null;

      if (renewalBanner) {
        const hasActiveVoucher = voucherState.valid;
        if (result.campaign && !hasActiveVoucher) {
          const title = result.is_renewal ? 'Perpanjangan anggota terdeteksi' : 'Promo aktif!';
          let desc = result.campaign.name;
          if (result.duration_bonus > 0) {
            desc += ` – bonus +${result.duration_bonus} hari`;
          } else if (result.discount_amount > 0) {
            desc += ` – hemat ${formatCurrency(result.discount_amount)}`;
          }
          if (renewalBannerTitle) renewalBannerTitle.textContent = title;
          if (renewalBannerDesc) renewalBannerDesc.textContent = desc;
          renewalBanner.classList.remove('hidden');
        } else if (result.is_renewal) {
          if (renewalBannerTitle) renewalBannerTitle.textContent = 'Perpanjangan keanggotaan';
          if (renewalBannerDesc) renewalBannerDesc.textContent = hasActiveVoucher ? 'NIK terdaftar. Voucher aktif diterapkan.' : 'NIK terdaftar. Tidak ada promo aktif saat ini.';
          renewalBanner.classList.remove('hidden');
        } else {
          renewalBanner.classList.add('hidden');
        }
      }
      updatePaymentSummary();
    } catch {
      resetRenewalState();
    } finally {
      if (nikChecking) nikChecking.classList.add('hidden');
    }
  };

  if (nikInput) {
    nikInput.addEventListener('input', () => {
      clearTimeout(nikCheckTimer);
      const nik = nikInput.value.trim();
      if (!nik) { resetRenewalState(); updatePaymentSummary(); return; }
      nikCheckTimer = setTimeout(async () => {
        const prevIsRenewal = renewalState.isRenewal;
        await checkNik(nik);
        const voucherCode = membershipForm?.querySelector('[data-voucher-input]')?.value.trim();
        if (voucherCode && renewalState.isRenewal !== prevIsRenewal) checkVoucher(voucherCode);
      }, 600);
    });
  }

  // Voucher code validation
  const voucherInput = membershipForm?.querySelector('[data-voucher-input]');
  const voucherChecking = document.querySelector('[data-voucher-checking]');
  const voucherFeedback = document.querySelector('[data-voucher-feedback]');
  const voucherFeedbackText = document.querySelector('[data-voucher-feedback-text]');
  let voucherCheckTimer = null;

  const resetVoucherState = () => {
    voucherState.valid = false;
    voucherState.discountAmount = 0;
    voucherState.durationBonus = 0;
    voucherState.finalPrice = null;
    voucherState.campaignName = null;
    if (voucherFeedback) voucherFeedback.classList.add('hidden');
  };

  const checkVoucher = async (code) => {
    const memberTypeId = membershipInput?.value;
    const nik = membershipForm?.querySelector('[data-nik-input]')?.value.trim() || '';
    if (!code || !memberTypeId) { resetVoucherState(); return; }
    if (voucherChecking) voucherChecking.classList.remove('hidden');
    try {
      const url = `/api/membership/check-voucher?code=${encodeURIComponent(code)}&member_type_id=${encodeURIComponent(memberTypeId)}&nik=${encodeURIComponent(nik)}`;
      const response = await fetch(url, { headers: { Accept: 'application/json' } });
      if (!response.ok) throw new Error('Network error');
      const result = await response.json();

      voucherState.valid = result.valid;
      voucherState.discountAmount = result.discount_amount || 0;
      voucherState.durationBonus = result.duration_bonus || 0;
      voucherState.finalPrice = result.valid && result.discount_amount > 0 ? result.final_price : null;
      voucherState.campaignName = result.campaign?.name || null;

      if (voucherFeedback && voucherFeedbackText) {
        if (result.valid) {
          voucherFeedback.className = 'mt-2 rounded-xl px-4 py-2.5 text-xs font-medium bg-teal-50 border border-teal-200 text-teal-700';
          let text = `Voucher "${result.campaign?.name}"`;
          if (result.duration_bonus > 0) {
            text += ` – bonus +${result.duration_bonus} hari berhasil diterapkan!`;
          } else if (result.discount_amount > 0) {
            text += ` – hemat ${formatCurrency(result.discount_amount)} berhasil diterapkan!`;
          } else {
            text += ` berhasil diterapkan!`;
          }
          voucherFeedbackText.textContent = text;
        } else {
          voucherFeedback.className = 'mt-2 rounded-xl px-4 py-2.5 text-xs font-medium bg-rose-50 border border-rose-200 text-rose-600';
          voucherFeedbackText.textContent = result.error || 'Voucher tidak valid.';
        }
        voucherFeedback.classList.remove('hidden');
      }
      updatePaymentSummary();
    } catch {
      resetVoucherState();
    } finally {
      if (voucherChecking) voucherChecking.classList.add('hidden');
    }
  };

  if (voucherInput) {
    voucherInput.addEventListener('input', () => {
      clearTimeout(voucherCheckTimer);
      const code = voucherInput.value.trim();
      if (!code) { resetVoucherState(); updatePaymentSummary(); return; }
      voucherCheckTimer = setTimeout(() => checkVoucher(code), 700);
    });
  }

  let togglePaymentPanels = null;
  if (membershipForm) {
    togglePaymentPanels = () => {
      const selected = membershipForm.querySelector('input[name="payment_method"]:checked')?.value;
      const panels = Array.from(document.querySelectorAll('[data-payment-panel]'));
      panels.forEach((panel) => {
        const isMatch = panel.dataset.paymentPanel === selected;
        panel.classList.toggle('hidden', !isMatch);
      });
      updatePaymentSummary();
    };

    membershipForm.addEventListener('change', (event) => {
      if (event.target?.name === 'payment_method' || event.target?.name === 'qty') {
        togglePaymentPanels();
      }
    });

    togglePaymentPanels();

    membershipForm.addEventListener('submit', (event) => {
      const current = Number(stepIndicators.find((indicator) => indicator.classList.contains('bg-teal-600'))?.dataset?.stepIndicator || 1);
      setRequiredForStep(current);
      if (!membershipForm.checkValidity()) {
        event.preventDefault();
        membershipForm.reportValidity();
        return;
      }
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('opacity-70', 'cursor-not-allowed');
        submitButton.textContent = 'Memproses...';
      }
    });
  }

  const openPaymentModal = () => {
    if (!paymentModal) return;
    paymentModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    if (togglePaymentPanels) {
      togglePaymentPanels();
    }

    let remaining = 600;
    const renderCountdown = () => {
      if (!paymentCountdown) return;
      const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
      const seconds = String(remaining % 60).padStart(2, '0');
      paymentCountdown.textContent = `${minutes}:${seconds}`;
    };
    renderCountdown();
    if (paymentTimerId) {
      clearInterval(paymentTimerId);
    }
    paymentTimerId = setInterval(() => {
      remaining -= 1;
      if (remaining <= 0) {
        clearInterval(paymentTimerId);
        paymentTimerId = null;
        if (paymentCountdown) paymentCountdown.textContent = '00:00';
        if (paymentSubmitButton) {
          paymentSubmitButton.disabled = true;
          paymentSubmitButton.classList.add('opacity-70', 'cursor-not-allowed');
          paymentSubmitButton.textContent = 'Waktu habis';
        }
        return;
      }
      renderCountdown();
    }, 1000);
  };

  const closePaymentModal = () => {
    if (!paymentModal) return;
    paymentModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    if (paymentTimerId) {
      clearInterval(paymentTimerId);
      paymentTimerId = null;
    }
  };

  if (paymentOpenButton) {
    paymentOpenButton.addEventListener('click', () => {
      const current = Number(stepIndicators.find((indicator) => indicator.classList.contains('bg-teal-600'))?.dataset?.stepIndicator || 1);
      setRequiredForStep(current);
      if (membershipForm && !membershipForm.checkValidity()) {
        membershipForm.reportValidity();
        return;
      }
      if (paymentSubmitButton) {
        paymentSubmitButton.disabled = false;
        paymentSubmitButton.classList.remove('opacity-70', 'cursor-not-allowed');
        paymentSubmitButton.textContent = 'Saya sudah bayar';
      }
      openPaymentModal();
    });
  }

  if (paymentCloseButtons.length > 0) {
    paymentCloseButtons.forEach((button) => {
      button.addEventListener('click', () => closePaymentModal());
    });
  }

  if (paymentSubmitButton && membershipForm) {
    paymentSubmitButton.addEventListener('click', () => {
      closePaymentModal();
      membershipForm.requestSubmit();
    });
  }

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && membershipModal && !membershipModal.classList.contains('hidden')) {
      closeMembershipModal();
    }
  });
});

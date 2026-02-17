let blogData = null;
let blogFetchPromise = null;

document.addEventListener('DOMContentLoaded', () => {
  const blogContainer = document.querySelector('.blog-entries');
  if (!blogContainer) return;

  const rssSource = 'https://blog.bacaditebet.id/feeds/posts/default?alt=rss';
  const proxyUrls = [
    'https://api.allorigins.win/raw?url=' + encodeURIComponent(rssSource),
    'https://api.allorigins.win/get?url=' + encodeURIComponent(rssSource),
    'https://corsproxy.io/?' + encodeURIComponent(rssSource),
    'https://api.codetabs.com/v1/proxy?quest=' + encodeURIComponent(rssSource)
  ];
  const fallbackImage = 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=800';

  const renderMessage = (message) => {
    blogContainer.innerHTML = `
      <div class="md:col-span-3 rounded-xl border border-slate-100 bg-white p-6 text-center text-slate-500">
        ${message}
      </div>
    `;
  };

  const renderBlogContent = (items) => {
    blogContainer.innerHTML = '';

    if (items.length === 0) {
      renderMessage('Artikel belum tersedia untuk sekarang. Silakan kembali lagi nanti atau kunjungi <a href="https://blog.bacaditebet.id" target="_blank" rel="noreferrer" class="text-teal-700 font-semibold hover:underline">blog kami</a>.');
      return;
    }

    const maxItems = Math.min(6, items.length);
    for (let i = 0; i < maxItems; i += 1) {
      const item = items[i];
      const title = item.querySelector('title')?.textContent || 'Tanpa Judul';
      const description = item.querySelector('description')?.textContent || '';
      const link = item.querySelector('link')?.textContent || '#';
      const pubDate = item.querySelector('pubDate')?.textContent || '';
      const category = item.querySelector('category')?.textContent || 'Blog';

      const date = new Date(pubDate);
      const formattedDate = Number.isNaN(date.getTime())
        ? ''
        : date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = description;
      const img = tempDiv.querySelector('img');
      const imgSrc = img ? img.src : fallbackImage;

      const article = document.createElement('article');
      article.className = 'bg-white p-6 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex flex-col';
      article.innerHTML = `
        <div class="mb-4">
          <div class="relative h-40 rounded-lg overflow-hidden bg-slate-100">
            <img src="${imgSrc}" alt="${escapeHtml(title)}" class="w-full h-full object-cover" loading="lazy" />
          </div>
        </div>
        <div class="flex items-center gap-3 mb-4 text-xs font-bold uppercase tracking-wider">
          <span class="text-teal-600">${escapeHtml(category)}</span>
          <span class="text-slate-300">|</span>
          <span class="text-slate-400">${formattedDate}</span>
        </div>
        <h3 class="font-serif text-xl font-bold text-slate-800 mb-3 hover:text-teal-700">
          <a href="${link}" target="_blank" rel="noreferrer">${escapeHtml(title)}</a>
        </h3>
        <p class="text-slate-600 text-sm mb-4 line-clamp-3">
          ${escapeHtml(truncateText(stripHtml(description), 140))}
        </p>
        <a href="${link}" target="_blank" rel="noreferrer" class="text-sm font-medium text-slate-900 flex items-center gap-1 hover:gap-2 transition-all">
          Baca selengkapnya
        </a>
      `;
      blogContainer.appendChild(article);
    }

    if (items.length > 6) {
      const viewMore = document.createElement('div');
      viewMore.className = 'md:col-span-3 text-center';
      viewMore.innerHTML = `
        <a href="https://blog.bacaditebet.id" target="_blank" rel="noreferrer" class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-700 px-6 py-3 text-sm font-bold text-white hover:bg-teal-800 transition-colors">
          Lihat Semua Artikel
        </a>
      `;
      blogContainer.appendChild(viewMore);
    }
  };

  // Mulai fetch blog di background saat page load
  blogFetchPromise = fetchRss(proxyUrls)
    .then((data) => {
      const parser = new DOMParser();
      const xmlDoc = parser.parseFromString(data, 'text/xml');
      if (xmlDoc.querySelector('parsererror')) {
        throw new Error('Invalid RSS response');
      }
      const items = Array.from(xmlDoc.querySelectorAll('item'));
      blogData = items;
      return items;
    })
    .catch(() => {
      blogData = 'error';
      return 'error';
    });

  // Listen untuk tab change
  document.addEventListener('tabChanged', (event) => {
    if (event.detail.tabId === 'blog') {
      // Check if data already loaded
      if (blogData && blogData !== 'error') {
        renderBlogContent(blogData);
      } else if (blogData === 'error') {
        renderMessage('Maaf, gagal memuat artikel. Silakan kunjungi <a href="https://blog.bacaditebet.id" target="_blank" rel="noreferrer" class="text-teal-700 font-semibold hover:underline">blog kami</a> untuk membaca langsung.');
      } else {
        // Data masih loading
        renderMessage('Memuat artikel terbaru...');
        blogFetchPromise.then((items) => {
          if (items === 'error') {
            renderMessage('Maaf, gagal memuat artikel. Silakan kunjungi <a href="https://blog.bacaditebet.id" target="_blank" rel="noreferrer" class="text-teal-700 font-semibold hover:underline">blog kami</a> untuk membaca langsung.');
          } else {
            renderBlogContent(items);
          }
        });
      }
    }
  });

  // Check if blog tab is already active on load
  const blogPanel = document.querySelector('[data-tab-panel="blog"]');
  if (blogPanel && !blogPanel.classList.contains('hidden')) {
    if (blogData && blogData !== 'error') {
      renderBlogContent(blogData);
    } else if (blogData === 'error') {
      renderMessage('Maaf, gagal memuat artikel. Silakan kunjungi <a href="https://blog.bacaditebet.id" target="_blank" rel="noreferrer" class="text-teal-700 font-semibold hover:underline">blog kami</a> untuk membaca langsung.');
    } else {
      renderMessage('Memuat artikel terbaru...');
      blogFetchPromise.then((items) => {
        if (items === 'error') {
          renderMessage('Maaf, gagal memuat artikel. Silakan kunjungi <a href="https://blog.bacaditebet.id" target="_blank" rel="noreferrer" class="text-teal-700 font-semibold hover:underline">blog kami</a> untuk membaca langsung.');
        } else {
          renderBlogContent(items);
        }
      });
    }
  }

  function stripHtml(html) {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    return tempDiv.textContent || tempDiv.innerText || '';
  }

  function truncateText(text, maxLength) {
    if (text.length > maxLength) {
      return text.substring(0, maxLength) + '...';
    }
    return text;
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/\"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function fetchRss(urls) {
    return urls.reduce((promise, url) => {
      return promise.catch(() => fetch(url).then((response) => {
        if (!response.ok) {
          throw new Error('Bad status');
        }
        const contentType = response.headers.get('content-type') || '';
        if (contentType.includes('application/json') || url.includes('/get?url=')) {
          return response.json().then((payload) => {
            if (payload && payload.contents) {
              return payload.contents;
            }
            throw new Error('Invalid JSON payload');
          });
        }
        return response.text();
      }));
    }, Promise.reject());
  }
});

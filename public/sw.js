const CACHE_NAME = 'wc2026-v1';
const ASSETS_TO_CACHE = [
  '/',
];

// Installation: Cache essential assets only
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      // Only cache the root page - assets will be cached dynamically
      return cache.addAll(ASSETS_TO_CACHE);
    }).catch((error) => {
      console.error('Service Worker install failed:', error);
    })
  );
  self.skipWaiting();
});

// Activation: Clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch: Smart caching strategy
self.addEventListener('fetch', (event) => {
  // Only handle GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  const url = new URL(event.request.url);

  // Skip external requests
  if (url.origin !== location.origin) {
    return;
  }

  // API calls - Network only, never cache
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(fetch(event.request));
    return;
  }

  // HTML pages - Network first, fallback to cache
  if (event.request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          if (!response || response.status !== 200) {
            return response;
          }

          // Cache successful HTML responses
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseClone);
          });

          return response;
        })
        .catch(() => {
          // Network failed - return cached version
          return caches.match(event.request).then((cached) => {
            return cached || caches.match('/');
          });
        })
    );
    return;
  }

  // Static assets (CSS, JS, images, fonts) - Cache first
  event.respondWith(
    caches.match(event.request).then((cached) => {
      if (cached) {
        return cached;
      }

      return fetch(event.request).then((response) => {
        // Only cache successful responses
        if (!response || response.status !== 200) {
          return response;
        }

        // Cache the response
        const responseClone = response.clone();
        caches.open(CACHE_NAME).then((cache) => {
          cache.put(event.request, responseClone);
        });

        return response;
      }).catch(() => {
        // Network failed and not in cache
        // For images, return a placeholder
        if (event.request.destination === 'image') {
          return caches.match('/build/assets/placeholder.png').catch(() => {
            return new Response('', { status: 404 });
          });
        }
        return caches.match(event.request);
      });
    })
  );
});

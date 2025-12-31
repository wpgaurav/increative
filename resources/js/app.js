/**
 * Increative Theme - Main JavaScript
 */

// Import assets
import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

// ============================================
// Mobile Menu
// ============================================
class MobileMenu {
  constructor() {
    this.toggle = document.getElementById('mobile-menu-toggle');
    this.close = document.getElementById('mobile-menu-close');
    this.menu = document.getElementById('mobile-menu');
    
    if (this.toggle && this.menu) {
      this.init();
    }
  }

  init() {
    this.toggle.addEventListener('click', () => this.open());
    this.close?.addEventListener('click', () => this.closeMenu());
    
    // Close on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen()) {
        this.closeMenu();
      }
    });

    // Close when clicking outside
    this.menu.addEventListener('click', (e) => {
      if (e.target === this.menu) {
        this.closeMenu();
      }
    });
  }

  open() {
    this.menu.classList.add('is-open');
    this.toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  closeMenu() {
    this.menu.classList.remove('is-open');
    this.toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  isOpen() {
    return this.menu.classList.contains('is-open');
  }
}

// ============================================
// Dark Mode Toggle
// ============================================
class DarkMode {
  constructor() {
    this.toggle = document.getElementById('theme-toggle');
    this.storageKey = 'increative-theme';
    
    if (this.toggle) {
      this.init();
    }
  }

  init() {
    // Check for saved preference or system preference
    const savedTheme = localStorage.getItem(this.storageKey);
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme) {
      document.documentElement.setAttribute('data-theme', savedTheme);
    } else if (systemPrefersDark) {
      document.documentElement.setAttribute('data-theme', 'dark');
    }

    // Toggle on click
    this.toggle.addEventListener('click', () => this.toggleTheme());

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem(this.storageKey)) {
        document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
      }
    });
  }

  toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem(this.storageKey, next);
  }
}

// ============================================
// Sticky Header
// ============================================
class StickyHeader {
  constructor() {
    this.header = document.querySelector('.site-header');
    this.lastScrollY = 0;
    this.ticking = false;
    
    if (this.header) {
      this.init();
    }
  }

  init() {
    window.addEventListener('scroll', () => {
      this.lastScrollY = window.scrollY;
      
      if (!this.ticking) {
        window.requestAnimationFrame(() => {
          this.update();
          this.ticking = false;
        });
        this.ticking = true;
      }
    });
  }

  update() {
    if (this.lastScrollY > 100) {
      this.header.classList.add('is-scrolled');
    } else {
      this.header.classList.remove('is-scrolled');
    }
  }
}

// ============================================
// Smooth Scroll
// ============================================
class SmoothScroll {
  constructor() {
    this.init();
  }

  init() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', (e) => {
        const href = anchor.getAttribute('href');
        
        if (href === '#') return;
        
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  }
}

// ============================================
// Reading Progress Bar (for single posts)
// ============================================
class ReadingProgress {
  constructor() {
    this.bar = document.querySelector('.reading-progress');
    this.article = document.querySelector('.post-content');
    
    if (this.bar && this.article) {
      this.init();
    }
  }

  init() {
    window.addEventListener('scroll', () => {
      const articleTop = this.article.offsetTop;
      const articleHeight = this.article.offsetHeight;
      const windowHeight = window.innerHeight;
      const scrollY = window.scrollY;
      
      const progress = Math.min(
        Math.max((scrollY - articleTop + windowHeight) / articleHeight, 0),
        1
      );
      
      this.bar.style.transform = `scaleX(${progress})`;
    });
  }
}

// ============================================
// Copy Code Button
// ============================================
class CopyCode {
  constructor() {
    this.init();
  }

  init() {
    document.querySelectorAll('pre').forEach(pre => {
      const button = document.createElement('button');
      button.className = 'copy-code-btn';
      button.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>';
      button.title = 'Copy code';
      
      button.addEventListener('click', async () => {
        const code = pre.querySelector('code')?.textContent || pre.textContent;
        
        try {
          await navigator.clipboard.writeText(code);
          button.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
          setTimeout(() => {
            button.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>';
          }, 2000);
        } catch (err) {
          console.error('Failed to copy:', err);
        }
      });
      
      pre.style.position = 'relative';
      pre.appendChild(button);
    });
  }
}

// ============================================
// Initialize
// ============================================
document.addEventListener('DOMContentLoaded', () => {
  new MobileMenu();
  new DarkMode();
  new StickyHeader();
  new SmoothScroll();
  new ReadingProgress();
  new CopyCode();
});

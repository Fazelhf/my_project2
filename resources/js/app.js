import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/* Scroll-reveal */
const observer = new IntersectionObserver(
    entries => entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('revealed'); observer.unobserve(e.target); }
    }),
    { threshold: 0.08 }
);
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

/* Bento card mouse-glow */
document.addEventListener('mousemove', e => {
    document.querySelectorAll('.bento-card').forEach(c => {
        const r = c.getBoundingClientRect();
        c.style.setProperty('--gx', (e.clientX - r.left) + 'px');
        c.style.setProperty('--gy', (e.clientY - r.top) + 'px');
        c.style.setProperty('--glow-x', (e.clientX - r.left) + 'px');
        c.style.setProperty('--glow-y', (e.clientY - r.top) + 'px');
    });
});

/* Ripple on .btn-primary */
document.addEventListener('click', e => {
    const btn = e.target.closest('.btn-primary, .liquid-button');
    if (!btn) return;
    const r = btn.getBoundingClientRect();
    const ripple = document.createElement('span');
    const size = Math.max(r.width, r.height) * 2;
    Object.assign(ripple.style, {
        position: 'absolute', borderRadius: '50%',
        width: size + 'px', height: size + 'px',
        left: (e.clientX - r.left - size / 2) + 'px',
        top: (e.clientY - r.top - size / 2) + 'px',
        background: 'rgba(255,255,255,0.3)', transform: 'scale(0)',
        animation: 'ripple-anim 0.5s linear', pointerEvents: 'none',
    });
    btn.appendChild(ripple);
    ripple.addEventListener('animationend', () => ripple.remove());
});

/* Auto-dismiss flash messages */
document.querySelectorAll('[data-flash]').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity 0.5s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 3500);
});

/* Score inputs: select on focus */
document.addEventListener('focusin', e => {
    if (e.target.matches('input[type="number"], .score-input')) e.target.select();
});

if (!document.getElementById('ripple-style')) {
    const s = document.createElement('style');
    s.id = 'ripple-style';
    s.textContent = '@keyframes ripple-anim { to { transform: scale(1); opacity: 0; } }';
    document.head.appendChild(s);
}

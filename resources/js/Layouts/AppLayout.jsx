import { Link, usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';

// ── SplashCursor WebGL ─────────────────────────────────────────────────────
function SplashCursor() {
    const canvasRef = useRef(null);
    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const pr = window.devicePixelRatio || 1;
        const params = { alpha: true, depth: false, stencil: false, antialias: false };
        let gl = canvas.getContext('webgl2', params);
        const isGL2 = !!gl;
        if (!isGL2) gl = canvas.getContext('webgl', params) || canvas.getContext('experimental-webgl', params);
        if (!gl) return;

        let hf, sl;
        if (isGL2) { gl.getExtension('EXT_color_buffer_float'); sl = gl.getExtension('OES_texture_float_linear'); }
        else { hf = gl.getExtension('OES_texture_half_float'); sl = gl.getExtension('OES_texture_half_float_linear'); }
        const HFT = isGL2 ? gl.HALF_FLOAT : (hf && hf.HALF_FLOAT_OES);
        const FL = sl ? gl.LINEAR : gl.NEAREST;

        function chk(i, f) {
            const t = gl.createTexture(); gl.bindTexture(gl.TEXTURE_2D, t);
            gl.texImage2D(gl.TEXTURE_2D, 0, i, 4, 4, 0, f, HFT, null);
            const fb = gl.createFramebuffer(); gl.bindFramebuffer(gl.FRAMEBUFFER, fb);
            gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, t, 0);
            return gl.checkFramebufferStatus(gl.FRAMEBUFFER) === gl.FRAMEBUFFER_COMPLETE;
        }
        function fmt(i, f) {
            if (!chk(i, f)) { if (i === gl.R16F) return fmt(gl.RG16F, gl.RG); if (i === gl.RG16F) return fmt(gl.RGBA16F, gl.RGBA); return null; }
            return { i, f };
        }
        const RGBA = isGL2 ? fmt(gl.RGBA16F, gl.RGBA) : { i: gl.RGBA, f: gl.RGBA };
        const RG = isGL2 ? fmt(gl.RG16F, gl.RG) : RGBA;
        const R = isGL2 ? fmt(gl.R16F, gl.RED) : RGBA;

        const VS = `precision highp float;attribute vec2 p;varying vec2 U,L,Rv,T,B;uniform vec2 s;
        void main(){U=p*.5+.5;L=U-vec2(s.x,0.);Rv=U+vec2(s.x,0.);T=U+vec2(0.,s.y);B=U-vec2(0.,s.y);gl_Position=vec4(p,0.,1.);}`;
        function sh(t, src) { const s = gl.createShader(t); gl.shaderSource(s, src); gl.compileShader(s); return s; }
        function prog(vs, fs) {
            const p = gl.createProgram(); gl.attachShader(p, vs); gl.attachShader(p, fs); gl.linkProgram(p);
            const u = {}; const n = gl.getProgramParameter(p, gl.ACTIVE_UNIFORMS);
            for (let i = 0; i < n; i++) { const nm = gl.getActiveUniform(p, i).name; u[nm] = gl.getUniformLocation(p, nm); }
            return { p, u, bind() { gl.useProgram(p); } };
        }
        const vsh = sh(gl.VERTEX_SHADER, VS);
        const P = {
            adv: prog(vsh, sh(gl.FRAGMENT_SHADER, `precision highp float;precision highp sampler2D;varying vec2 U;uniform sampler2D uV,uS;uniform vec2 s;uniform float dt,d;void main(){vec2 c=U-dt*texture2D(uV,U).xy*s;gl_FragColor=texture2D(uS,c)/(1.+d*dt);}`)),
            div: prog(vsh, sh(gl.FRAGMENT_SHADER, `precision mediump float;precision mediump sampler2D;varying vec2 U,L,Rv,T,B;uniform sampler2D uV;void main(){float l=texture2D(uV,L).x,r=texture2D(uV,Rv).x,t=texture2D(uV,T).y,b=texture2D(uV,B).y;vec2 C=texture2D(uV,U).xy;if(L.x<0.)l=-C.x;if(Rv.x>1.)r=-C.x;if(T.y>1.)t=-C.y;if(B.y<0.)b=-C.y;gl_FragColor=vec4(.5*(r-l+t-b),0.,0.,1.);}`)),
            prs: prog(vsh, sh(gl.FRAGMENT_SHADER, `precision mediump float;precision mediump sampler2D;varying vec2 U,L,Rv,T,B;uniform sampler2D uP,uD;void main(){float l=texture2D(uP,L).x,r=texture2D(uP,Rv).x,t=texture2D(uP,T).x,b=texture2D(uP,B).x;gl_FragColor=vec4((l+r+b+t-texture2D(uD,U).x)*.25,0.,0.,1.);}`)),
            grd: prog(vsh, sh(gl.FRAGMENT_SHADER, `precision mediump float;precision mediump sampler2D;varying vec2 U,L,Rv,T,B;uniform sampler2D uP,uV;void main(){float l=texture2D(uP,L).x,r=texture2D(uP,Rv).x,t=texture2D(uP,T).x,b=texture2D(uP,B).x;vec2 v=texture2D(uV,U).xy-vec2(r-l,t-b);gl_FragColor=vec4(v,0.,1.);}`)),
            spl: prog(vsh, sh(gl.FRAGMENT_SHADER, `precision highp float;precision highp sampler2D;varying vec2 U;uniform sampler2D uT;uniform float aR;uniform vec3 C;uniform vec2 pt;uniform float r;void main(){vec2 p=U-pt;p.x*=aR;vec3 s=exp(-dot(p,p)/r)*C;gl_FragColor=vec4(texture2D(uT,U).xyz+s,1.);}`)),
            clr: prog(vsh, sh(gl.FRAGMENT_SHADER, `precision mediump float;precision mediump sampler2D;varying vec2 U;uniform sampler2D uT;uniform float v;void main(){gl_FragColor=v*texture2D(uT,U);}`)),
            dsp: prog(vsh, sh(gl.FRAGMENT_SHADER, `precision highp float;precision highp sampler2D;varying vec2 U;uniform sampler2D uT;void main(){vec3 c=texture2D(uT,U).rgb;gl_FragColor=vec4(c,max(c.r,max(c.g,c.b))*.7);}`)),
        };
        gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, -1, 1, 1, 1, 1, -1]), gl.STATIC_DRAW);
        gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, gl.createBuffer());
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array([0, 1, 2, 0, 2, 3]), gl.STATIC_DRAW);
        gl.vertexAttribPointer(0, 2, gl.FLOAT, false, 0, 0); gl.enableVertexAttribArray(0);

        function fbo(w, h, ii, ff, fl) {
            gl.activeTexture(gl.TEXTURE0); const tx = gl.createTexture(); gl.bindTexture(gl.TEXTURE_2D, tx);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, fl); gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, fl);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE); gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
            gl.texImage2D(gl.TEXTURE_2D, 0, ii, w, h, 0, ff, HFT, null);
            const fb = gl.createFramebuffer(); gl.bindFramebuffer(gl.FRAMEBUFFER, fb);
            gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, tx, 0);
            gl.viewport(0, 0, w, h); gl.clear(gl.COLOR_BUFFER_BIT);
            return { fb, tx, w, h, sx: 1 / w, sy: 1 / h, at(id) { gl.activeTexture(gl.TEXTURE0 + id); gl.bindTexture(gl.TEXTURE_2D, tx); return id; } };
        }
        function dbl(w, h, ii, ff, fl) {
            let a = fbo(w, h, ii, ff, fl), b = fbo(w, h, ii, ff, fl);
            return { w, h, sx: a.sx, sy: a.sy, get r() { return a; }, get w2() { return b; }, swap() { [a, b] = [b, a]; } };
        }
        function blit(t) {
            if (!t) { gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight); gl.bindFramebuffer(gl.FRAMEBUFFER, null); }
            else { gl.viewport(0, 0, t.w, t.h); gl.bindFramebuffer(gl.FRAMEBUFFER, t.fb); }
            gl.drawElements(gl.TRIANGLES, 6, gl.UNSIGNED_SHORT, 0);
        }
        function res(r) {
            const ar = gl.drawingBufferWidth / gl.drawingBufferHeight, mn = Math.round(r), mx = Math.round(r * (ar > 1 ? ar : 1));
            return gl.drawingBufferWidth > gl.drawingBufferHeight ? { w: mx, h: mn } : { w: mn, h: mx };
        }
        let dy, vl, dv, pr2;
        function init() {
            const s = res(128), d = res(512);
            dy = dbl(d.w, d.h, RGBA.i, RGBA.f, FL);
            vl = dbl(s.w, s.h, RG.i, RG.f, FL);
            dv = fbo(s.w, s.h, R.i, R.f, gl.NEAREST);
            pr2 = dbl(s.w, s.h, R.i, R.f, gl.NEAREST);
        }
        function rsz() {
            const w = Math.floor(canvas.clientWidth * pr), h = Math.floor(canvas.clientHeight * pr);
            if (canvas.width !== w || canvas.height !== h) { canvas.width = w; canvas.height = h; return true; } return false;
        }
        function hsv(h, s, v) {
            const i = Math.floor(h * 6), f = h * 6 - i, p = v * (1 - s), q = v * (1 - f * s), t2 = v * (1 - (1 - f) * s);
            const c = [[v, t2, p], [q, v, p], [p, v, t2], [p, q, v], [t2, p, v], [v, p, q]][i % 6];
            return { r: c[0] * .12, g: c[1] * .12, b: c[2] * .12 };
        }
        init();
        let mx = .5, my = .5, pmx = .5, pmy = .5, moved = false, col = { r: .05, g: .02, b: .15 }, lt = Date.now();
        let rafId;
        function splat(x, y, dx, dy2, c) {
            const sp = P.spl; sp.bind();
            gl.uniform1i(sp.u.uT, vl.r.at(0)); gl.uniform1f(sp.u.aR, canvas.width / canvas.height);
            gl.uniform2f(sp.u.pt, x, y); gl.uniform3f(sp.u.C, dx, dy2, 0); gl.uniform1f(sp.u.r, .002);
            blit(vl.w2); vl.swap();
            gl.uniform1i(sp.u.uT, dy.r.at(0)); gl.uniform3f(sp.u.C, c.r, c.g, c.b);
            blit(dy.w2); dy.swap();
        }
        function step(dt) {
            gl.disable(gl.BLEND);
            const ap = P.adv; ap.bind(); gl.uniform2f(ap.u.s, vl.sx, vl.sy);
            const vi = vl.r.at(0); gl.uniform1i(ap.u.uV, vi); gl.uniform1i(ap.u.uS, vi);
            gl.uniform1f(ap.u.dt, dt); gl.uniform1f(ap.u.d, 2); blit(vl.w2); vl.swap();
            gl.uniform1i(ap.u.uV, vl.r.at(0)); gl.uniform1i(ap.u.uS, dy.r.at(1));
            gl.uniform1f(ap.u.d, 3.5); blit(dy.w2); dy.swap();
            const dv2 = P.div; dv2.bind(); gl.uniform2f(dv2.u.s, vl.sx, vl.sy); gl.uniform1i(dv2.u.uV, vl.r.at(0)); blit(dv);
            const cp = P.clr; cp.bind(); gl.uniform1i(cp.u.uT, pr2.r.at(0)); gl.uniform1f(cp.u.v, .1); blit(pr2.w2); pr2.swap();
            const pp = P.prs; pp.bind(); gl.uniform2f(pp.u.s, vl.sx, vl.sy); gl.uniform1i(pp.u.uD, dv.at(0));
            for (let i = 0; i < 20; i++) { gl.uniform1i(pp.u.uP, pr2.r.at(1)); blit(pr2.w2); pr2.swap(); }
            const gp = P.grd; gp.bind(); gl.uniform2f(gp.u.s, vl.sx, vl.sy); gl.uniform1i(gp.u.uP, pr2.r.at(0)); gl.uniform1i(gp.u.uV, vl.r.at(1)); blit(vl.w2); vl.swap();
        }
        function loop() {
            const now = Date.now(), dt = Math.min((now - lt) / 1000, .016); lt = now;
            if (rsz()) init();
            if (moved) { moved = false; splat(mx, my, (mx - pmx) * 6000, (my - pmy) * 6000, col); }
            step(dt);
            gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA); gl.enable(gl.BLEND);
            const dp = P.dsp; dp.bind(); gl.uniform1i(dp.u.uT, dy.r.at(0)); blit(null);
            rafId = requestAnimationFrame(loop);
        }
        const onMove = (e) => {
            pmx = mx; pmy = my;
            mx = e.clientX / window.innerWidth; my = 1 - e.clientY / window.innerHeight;
            col = hsv(Math.random(), 1, 1); moved = true;
        };
        window.addEventListener('mousemove', onMove);
        loop();
        return () => { cancelAnimationFrame(rafId); window.removeEventListener('mousemove', onMove); };
    }, []);
    return (
        <canvas ref={canvasRef} style={{ position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none', width: '100vw', height: '100vh' }} />
    );
}

// ── BentoGlow hook ─────────────────────────────────────────────────────────
export function useBentoGlow(ref) {
    useEffect(() => {
        const el = ref.current;
        if (!el) return;
        const onMove = (e) => {
            const r = el.getBoundingClientRect();
            el.style.setProperty('--glow-x', (e.clientX - r.left) + 'px');
            el.style.setProperty('--glow-y', (e.clientY - r.top) + 'px');
        };
        el.addEventListener('mousemove', onMove);
        return () => el.removeEventListener('mousemove', onMove);
    }, []);
}

// ── NavPill ────────────────────────────────────────────────────────────────
function NavPill({ href, active, children }) {
    return (
        <Link href={href} className={`nav-pill${active ? ' active' : ''}`}>
            {children}
        </Link>
    );
}

// ── AppLayout ──────────────────────────────────────────────────────────────
export default function AppLayout({ children, title }) {
    const { auth, flash } = usePage().props;
    const user = auth?.user;
    const url = usePage().url;

    const is = (prefix) => url.startsWith(prefix);

    return (
        <>
            <div className="ether-bg" />
            <SplashCursor />

            {/* Top Navbar */}
            <header className="fixed top-0 inset-x-0 z-50 px-4 pt-3">
                <nav className="glass rounded-2xl px-4 py-2.5 flex items-center justify-between max-w-5xl mx-auto">
                    <Link href="/dashboard" className="flex items-center gap-2 flex-shrink-0">
                        <div className="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style={{ background: 'linear-gradient(135deg,#F5A623,#A78BFA)' }}>
                            <svg className="w-4 h-4" viewBox="0 0 24 24" fill="white">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z" />
                            </svg>
                        </div>
                        <span className="font-heading font-black text-sm tracking-wide text-gold hidden sm:block">WC 2026</span>
                    </Link>

                    <div className="flex items-center gap-1">
                        <NavPill href="/dashboard" active={url === '/dashboard'}>
                            <svg className="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            <span>داشبورد</span>
                        </NavPill>
                        <NavPill href="/games" active={is('/games')}>
                            <svg className="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" /></svg>
                            <span>پیش‌بینی</span>
                        </NavPill>
                        <NavPill href="/results" active={is('/results')}>
                            <svg className="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            <span>نتایج</span>
                        </NavPill>
                        <NavPill href="/leaderboard" active={is('/leaderboard')}>
                            <svg className="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            <span>جدول</span>
                        </NavPill>
                    </div>

                    <div className="flex items-center gap-2">
                        {user && (
                            <div className="hidden sm:flex items-center gap-2">
                                <span className="badge badge-gold font-heading text-xs">{user.total_score ?? 0} pt</span>
                                <span className="text-xs text-brand-muted max-w-[90px] truncate">{user.name}</span>
                            </div>
                        )}
                        {user?.is_admin && (
                            <Link href="/admin" className="nav-pill"
                                  style={{ color: '#C4B5FD', background: 'rgba(167,139,250,0.1)', border: '1px solid rgba(167,139,250,0.2)' }}>
                                <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span className="hidden md:block">ادمین</span>
                            </Link>
                        )}
                        <Link href="/profile" className="nav-pill"
                              style={{ color: 'rgba(185,203,185,0.7)' }}
                              onMouseOver={e => { e.currentTarget.style.background = 'rgba(255,255,255,0.08)'; e.currentTarget.style.color = '#F0F4FF'; }}
                              onMouseOut={e => { e.currentTarget.style.background = ''; e.currentTarget.style.color = 'rgba(185,203,185,0.7)'; }}>
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span className="hidden sm:block">پروفایل</span>
                        </Link>
                        <Link href="/logout" method="post" as="button" className="nav-pill cursor-pointer"
                              style={{ color: 'rgba(255,90,90,0.7)' }}
                              onMouseOver={e => { e.currentTarget.style.background = 'rgba(255,90,90,0.1)'; e.currentTarget.style.color = '#FF8A8A'; }}
                              onMouseOut={e => { e.currentTarget.style.background = ''; e.currentTarget.style.color = 'rgba(255,90,90,0.7)'; }}>
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            <span className="hidden sm:block">خروج</span>
                        </Link>
                    </div>
                </nav>
            </header>

            {/* Flash messages */}
            {(flash?.success || flash?.error) && (
                <div className="fixed top-20 left-1/2 -translate-x-1/2 z-50 w-full max-w-sm px-4 animate-slide-up">
                    {flash.success && (
                        <div className="glass rounded-xl px-4 py-3 text-sm font-semibold flex items-center gap-2"
                             style={{ background: 'rgba(0,229,160,0.1)', borderColor: 'rgba(0,229,160,0.3)', color: '#00E5A0' }}>
                            <svg className="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" /></svg>
                            {flash.success}
                        </div>
                    )}
                    {flash.error && (
                        <div className="glass rounded-xl px-4 py-3 text-sm font-semibold flex items-center gap-2"
                             style={{ background: 'rgba(255,90,90,0.1)', borderColor: 'rgba(255,90,90,0.3)', color: '#FF8A8A' }}>
                            <svg className="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" /></svg>
                            {flash.error}
                        </div>
                    )}
                </div>
            )}

            <main className="relative z-10 max-w-5xl mx-auto px-4 pt-24 pb-10 min-h-screen">
                {children}
            </main>
        </>
    );
}

<!DOCTYPE html>
<html class="dark" lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'جام جهانی ۲۰۲۶') — wc2026</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#0e141d">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="wc2026">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen">

{{-- Background --}}
<div class="stitch-bg"></div>

{{-- WebGL fluid cursor --}}
<canvas id="fluid-canvas" style="position:fixed;inset:0;z-index:0;pointer-events:none;width:100vw;height:100vh;"></canvas>

{{-- ── Top Navbar ──────────────────────────────────────────────────────────── --}}
<header class="fixed top-0 inset-x-0 z-50 px-4 pt-3">
    <nav class="apple-nav rounded-2xl px-4 py-2.5 flex items-center justify-between max-w-5xl mx-auto gap-3">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 flex-shrink-0">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#00b85e,#00e476);box-shadow:0 0 12px rgba(0,228,118,0.3);">
                <span class="material-symbols-outlined text-base" style="color:#003919;font-size:18px;font-variation-settings:'FILL' 1,'wght' 700,'GRAD' 0,'opsz' 24;">sports_soccer</span>
            </div>
            <span class="font-heading font-black text-sm tracking-wide gradient-text-green hidden sm:block">WC 2026</span>
        </a>

        {{-- Main nav --}}
        <div class="flex items-center gap-0.5 hide-mobile">
            <a href="{{ route('dashboard') }}"
               class="nav-pill {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-base">home</span>
                <span>داشبورد</span>
            </a>
            <a href="{{ route('games.index') }}"
               class="nav-pill {{ request()->routeIs('games.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-base">sports_soccer</span>
                <span>پیش‌بینی</span>
            </a>
            <a href="{{ route('results.index') }}"
               class="nav-pill {{ request()->routeIs('results.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-base">equalizer</span>
                <span>نتایج</span>
            </a>
            <a href="{{ route('leaderboard') }}"
               class="nav-pill {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-base">leaderboard</span>
                <span>جدول</span>
            </a>
        </div>

        {{-- User area --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="hidden sm:flex items-center gap-2">
                <span class="badge badge-green font-mono text-xs">{{ auth()->user()->effective_score ?? 0 }} pt</span>
                <span class="text-xs max-w-[80px] truncate" style="color:rgba(221,226,240,0.7);">{{ auth()->user()->name }}</span>
            </div>

            @if(auth()->user()->is_admin ?? false)
            <a href="{{ route('admin.dashboard') }}"
               class="nav-pill"
               style="color:#C4B5FD;background:rgba(167,139,250,0.08);border:1px solid rgba(167,139,250,0.2);">
                <span class="material-symbols-outlined text-base">settings</span>
                <span class="hidden md:block">ادمین</span>
            </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-pill cursor-pointer" style="color:rgba(255,90,90,0.7);"
                        onmouseover="this.style.background='rgba(255,90,90,0.08)';this.style.color='#FF8A8A'"
                        onmouseout="this.style.background='';this.style.color='rgba(255,90,90,0.7)'">
                    <span class="material-symbols-outlined text-base">logout</span>
                    <span class="hidden sm:block">خروج</span>
                </button>
            </form>
        </div>
    </nav>
</header>

{{-- ── Flash Messages ──────────────────────────────────────────────────────── --}}
@if(session('success') || session('error'))
<div class="fixed top-20 left-1/2 -translate-x-1/2 z-50 w-full max-w-sm px-4 animate-slide-up" data-flash>
    @if(session('success'))
    <div class="liquid-glass rounded-2xl px-4 py-3 text-sm font-semibold flex items-center gap-2 flash-success">
        <span class="material-symbols-outlined text-base flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="liquid-glass rounded-2xl px-4 py-3 text-sm font-semibold flex items-center gap-2 flash-error">
        <span class="material-symbols-outlined text-base flex-shrink-0">error</span>
        {{ session('error') }}
    </div>
    @endif
</div>
@endif

{{-- ── Page Content ────────────────────────────────────────────────────────── --}}
<main class="relative z-10 max-w-5xl mx-auto px-4 pt-24 pb-10 min-h-screen main-content page-enter">
    @yield('content')
</main>

{{-- ── Footer ──────────────────────────────────────────────────────────────── --}}
<footer class="relative z-10 text-center py-4 pb-24 sm:pb-6">
    <p class="text-xs" style="color:rgba(185,203,185,0.25);">ساخته شده با عشق در نمابر مهر</p>
</footer>

{{-- ── Mobile Bottom Nav ───────────────────────────────────────────────────── --}}
<nav class="mobile-nav">
    <a href="{{ route('dashboard') }}"
       class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="material-symbols-outlined">home</span>
        داشبورد
    </a>
    <a href="{{ route('results.index') }}"
       class="mobile-nav-item {{ request()->routeIs('results.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">equalizer</span>
        نتایج
    </a>

    {{-- دکمه مرکزی floating --}}
    <div class="mobile-nav-fab-wrap">
        <a href="{{ route('games.index') }}" class="mobile-nav-fab">
            <span class="material-symbols-outlined">sports_soccer</span>
        </a>
        <span class="mobile-nav-fab-label">پیش‌بینی</span>
    </div>

    <a href="{{ route('leaderboard') }}"
       class="mobile-nav-item {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
        <span class="material-symbols-outlined">leaderboard</span>
        جدول
    </a>
    <a href="{{ route('profile.index') }}"
       class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <span class="material-symbols-outlined">person</span>
        پروفایل
    </a>
</nav>

{{-- WebGL Fluid ──────────────────────────────────────────────────────────── --}}
<script>
(function(){
    const canvas=document.getElementById('fluid-canvas');
    if(!canvas)return;
    const pr=window.devicePixelRatio||1;
    const params={alpha:true,depth:false,stencil:false,antialias:false,preserveDrawingBuffer:false};
    let gl=canvas.getContext('webgl2',params);
    const isGL2=!!gl;
    if(!isGL2)gl=canvas.getContext('webgl',params)||canvas.getContext('experimental-webgl',params);
    if(!gl)return;
    let hf,sl;
    if(isGL2){gl.getExtension('EXT_color_buffer_float');sl=gl.getExtension('OES_texture_float_linear');}
    else{hf=gl.getExtension('OES_texture_half_float');sl=gl.getExtension('OES_texture_half_float_linear');}
    const HFT=isGL2?gl.HALF_FLOAT:(hf&&hf.HALF_FLOAT_OES);
    const FL=sl?gl.LINEAR:gl.NEAREST;
    function chk(i,f){const t=gl.createTexture();gl.bindTexture(gl.TEXTURE_2D,t);gl.texImage2D(gl.TEXTURE_2D,0,i,4,4,0,f,HFT,null);const fb=gl.createFramebuffer();gl.bindFramebuffer(gl.FRAMEBUFFER,fb);gl.framebufferTexture2D(gl.FRAMEBUFFER,gl.COLOR_ATTACHMENT0,gl.TEXTURE_2D,t,0);return gl.checkFramebufferStatus(gl.FRAMEBUFFER)===gl.FRAMEBUFFER_COMPLETE;}
    function fmt(i,f){if(!chk(i,f)){if(i===gl.R16F)return fmt(gl.RG16F,gl.RG);if(i===gl.RG16F)return fmt(gl.RGBA16F,gl.RGBA);return null;}return{i,f};}
    const RGBA=isGL2?fmt(gl.RGBA16F,gl.RGBA):{i:gl.RGBA,f:gl.RGBA};
    const RG=isGL2?fmt(gl.RG16F,gl.RG):RGBA;
    const R=isGL2?fmt(gl.R16F,gl.RED):RGBA;
    const VS=`precision highp float;attribute vec2 p;varying vec2 U,L,Rv,T,B;uniform vec2 s;void main(){U=p*.5+.5;L=U-vec2(s.x,0.);Rv=U+vec2(s.x,0.);T=U+vec2(0.,s.y);B=U-vec2(0.,s.y);gl_Position=vec4(p,0.,1.);}`;
    function sh(t,src){const s=gl.createShader(t);gl.shaderSource(s,src);gl.compileShader(s);return s;}
    function prog(vs,fs){const p=gl.createProgram();gl.attachShader(p,vs);gl.attachShader(p,fs);gl.linkProgram(p);const u={};const n=gl.getProgramParameter(p,gl.ACTIVE_UNIFORMS);for(let i=0;i<n;i++){const nm=gl.getActiveUniform(p,i).name;u[nm]=gl.getUniformLocation(p,nm);}return{p,u,bind(){gl.useProgram(p);}};}
    const vsh=sh(gl.VERTEX_SHADER,VS);
    const P={
        adv:prog(vsh,sh(gl.FRAGMENT_SHADER,`precision highp float;precision highp sampler2D;varying vec2 U;uniform sampler2D uV,uS;uniform vec2 s;uniform float dt,d;void main(){vec2 c=U-dt*texture2D(uV,U).xy*s;gl_FragColor=texture2D(uS,c)/(1.+d*dt);}`)),
        div:prog(vsh,sh(gl.FRAGMENT_SHADER,`precision mediump float;precision mediump sampler2D;varying vec2 U,L,Rv,T,B;uniform sampler2D uV;void main(){float l=texture2D(uV,L).x,r=texture2D(uV,Rv).x,t=texture2D(uV,T).y,b=texture2D(uV,B).y;vec2 C=texture2D(uV,U).xy;if(L.x<0.)l=-C.x;if(Rv.x>1.)r=-C.x;if(T.y>1.)t=-C.y;if(B.y<0.)b=-C.y;gl_FragColor=vec4(.5*(r-l+t-b),0.,0.,1.);}`)),
        prs:prog(vsh,sh(gl.FRAGMENT_SHADER,`precision mediump float;precision mediump sampler2D;varying vec2 U,L,Rv,T,B;uniform sampler2D uP,uD;void main(){float l=texture2D(uP,L).x,r=texture2D(uP,Rv).x,t=texture2D(uP,T).x,b=texture2D(uP,B).x;gl_FragColor=vec4((l+r+b+t-texture2D(uD,U).x)*.25,0.,0.,1.);}`)),
        grd:prog(vsh,sh(gl.FRAGMENT_SHADER,`precision mediump float;precision mediump sampler2D;varying vec2 U,L,Rv,T,B;uniform sampler2D uP,uV;void main(){float l=texture2D(uP,L).x,r=texture2D(uP,Rv).x,t=texture2D(uP,T).x,b=texture2D(uP,B).x;vec2 v=texture2D(uV,U).xy-vec2(r-l,t-b);gl_FragColor=vec4(v,0.,1.);}`)),
        spl:prog(vsh,sh(gl.FRAGMENT_SHADER,`precision highp float;precision highp sampler2D;varying vec2 U;uniform sampler2D uT;uniform float aR;uniform vec3 C;uniform vec2 pt;uniform float r;void main(){vec2 p=U-pt;p.x*=aR;vec3 s=exp(-dot(p,p)/r)*C;gl_FragColor=vec4(texture2D(uT,U).xyz+s,1.);}`)),
        clr:prog(vsh,sh(gl.FRAGMENT_SHADER,`precision mediump float;precision mediump sampler2D;varying vec2 U;uniform sampler2D uT;uniform float v;void main(){gl_FragColor=v*texture2D(uT,U);}`)),
        dsp:prog(vsh,sh(gl.FRAGMENT_SHADER,`precision highp float;precision highp sampler2D;varying vec2 U;uniform sampler2D uT;void main(){vec3 c=texture2D(uT,U).rgb;gl_FragColor=vec4(c,max(c.r,max(c.g,c.b))*.7);}`)),
    };
    gl.bindBuffer(gl.ARRAY_BUFFER,gl.createBuffer());gl.bufferData(gl.ARRAY_BUFFER,new Float32Array([-1,-1,-1,1,1,1,1,-1]),gl.STATIC_DRAW);
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,gl.createBuffer());gl.bufferData(gl.ELEMENT_ARRAY_BUFFER,new Uint16Array([0,1,2,0,2,3]),gl.STATIC_DRAW);
    gl.vertexAttribPointer(0,2,gl.FLOAT,false,0,0);gl.enableVertexAttribArray(0);
    function fbo(w,h,ii,ff,fl){gl.activeTexture(gl.TEXTURE0);const tx=gl.createTexture();gl.bindTexture(gl.TEXTURE_2D,tx);gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MIN_FILTER,fl);gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_MAG_FILTER,fl);gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_S,gl.CLAMP_TO_EDGE);gl.texParameteri(gl.TEXTURE_2D,gl.TEXTURE_WRAP_T,gl.CLAMP_TO_EDGE);gl.texImage2D(gl.TEXTURE_2D,0,ii,w,h,0,ff,HFT,null);const fb=gl.createFramebuffer();gl.bindFramebuffer(gl.FRAMEBUFFER,fb);gl.framebufferTexture2D(gl.FRAMEBUFFER,gl.COLOR_ATTACHMENT0,gl.TEXTURE_2D,tx,0);gl.viewport(0,0,w,h);gl.clear(gl.COLOR_BUFFER_BIT);return{fb,tx,w,h,sx:1/w,sy:1/h,at(id){gl.activeTexture(gl.TEXTURE0+id);gl.bindTexture(gl.TEXTURE_2D,tx);return id;}};}
    function dbl(w,h,ii,ff,fl){let a=fbo(w,h,ii,ff,fl),b=fbo(w,h,ii,ff,fl);return{w,h,sx:a.sx,sy:a.sy,get r(){return a;},get w2(){return b;},swap(){[a,b]=[b,a];}};}
    function blit(t){if(!t){gl.viewport(0,0,gl.drawingBufferWidth,gl.drawingBufferHeight);gl.bindFramebuffer(gl.FRAMEBUFFER,null);}else{gl.viewport(0,0,t.w,t.h);gl.bindFramebuffer(gl.FRAMEBUFFER,t.fb);}gl.drawElements(gl.TRIANGLES,6,gl.UNSIGNED_SHORT,0);}
    function res(r){const ar=gl.drawingBufferWidth/gl.drawingBufferHeight,mn=Math.round(r),mx=Math.round(r*(ar>1?ar:1));return gl.drawingBufferWidth>gl.drawingBufferHeight?{w:mx,h:mn}:{w:mn,h:mx};}
    let dy,vl,dv,pr2;
    function init(){const s=res(128),d=res(512);dy=dbl(d.w,d.h,RGBA.i,RGBA.f,FL);vl=dbl(s.w,s.h,RG.i,RG.f,FL);dv=fbo(s.w,s.h,R.i,R.f,gl.NEAREST);pr2=dbl(s.w,s.h,R.i,R.f,gl.NEAREST);}
    function rsz(){const w=Math.floor(canvas.clientWidth*pr),h=Math.floor(canvas.clientHeight*pr);if(canvas.width!==w||canvas.height!==h){canvas.width=w;canvas.height=h;return true;}return false;}
    function hsv(h,s,v){const i=Math.floor(h*6),f=h*6-i,p=v*(1-s),q=v*(1-f*s),t2=v*(1-(1-f)*s);const c=[[v,t2,p],[q,v,p],[p,v,t2],[p,q,v],[t2,p,v],[v,p,q]][i%6];return{r:c[0]*.12,g:c[1]*.12,b:c[2]*.12};}
    init();
    let mx=.5,my=.5,pmx=.5,pmy=.5,moved=false,col={r:.0,g:.07,b:.03},lt=Date.now();
    function splat(x,y,dx,dy2,c){const sp=P.spl;sp.bind();gl.uniform1i(sp.u.uT,vl.r.at(0));gl.uniform1f(sp.u.aR,canvas.width/canvas.height);gl.uniform2f(sp.u.pt,x,y);gl.uniform3f(sp.u.C,dx,dy2,0);gl.uniform1f(sp.u.r,.002);blit(vl.w2);vl.swap();gl.uniform1i(sp.u.uT,dy.r.at(0));gl.uniform3f(sp.u.C,c.r,c.g,c.b);blit(dy.w2);dy.swap();}
    function step(dt){gl.disable(gl.BLEND);const ap=P.adv;ap.bind();gl.uniform2f(ap.u.s,vl.sx,vl.sy);const vi=vl.r.at(0);gl.uniform1i(ap.u.uV,vi);gl.uniform1i(ap.u.uS,vi);gl.uniform1f(ap.u.dt,dt);gl.uniform1f(ap.u.d,2);blit(vl.w2);vl.swap();gl.uniform1i(ap.u.uV,vl.r.at(0));gl.uniform1i(ap.u.uS,dy.r.at(1));gl.uniform1f(ap.u.d,3.5);blit(dy.w2);dy.swap();const dv2=P.div;dv2.bind();gl.uniform2f(dv2.u.s,vl.sx,vl.sy);gl.uniform1i(dv2.u.uV,vl.r.at(0));blit(dv);const cp=P.clr;cp.bind();gl.uniform1i(cp.u.uT,pr2.r.at(0));gl.uniform1f(cp.u.v,.1);blit(pr2.w2);pr2.swap();const pp=P.prs;pp.bind();gl.uniform2f(pp.u.s,vl.sx,vl.sy);gl.uniform1i(pp.u.uD,dv.at(0));for(let i=0;i<20;i++){gl.uniform1i(pp.u.uP,pr2.r.at(1));blit(pr2.w2);pr2.swap();}const gp=P.grd;gp.bind();gl.uniform2f(gp.u.s,vl.sx,vl.sy);gl.uniform1i(gp.u.uP,pr2.r.at(0));gl.uniform1i(gp.u.uV,vl.r.at(1));blit(vl.w2);vl.swap();}
    function loop(){const now=Date.now(),dt=Math.min((now-lt)/1000,.016);lt=now;if(rsz())init();if(moved){moved=false;splat(mx,my,(mx-pmx)*6000,(my-pmy)*6000,col);}step(dt);gl.blendFunc(gl.ONE,gl.ONE_MINUS_SRC_ALPHA);gl.enable(gl.BLEND);const dp=P.dsp;dp.bind();gl.uniform1i(dp.u.uT,dy.r.at(0));blit(null);requestAnimationFrame(loop);}
    window.addEventListener('mousemove',e=>{pmx=mx;pmy=my;mx=e.clientX/window.innerWidth;my=1-e.clientY/window.innerHeight;col=hsv(Math.random(),1,1);moved=true;});
    /* Touch support */
    window.addEventListener('touchmove',e=>{const t=e.touches[0];pmx=mx;pmy=my;mx=t.clientX/window.innerWidth;my=1-t.clientY/window.innerHeight;col=hsv(Math.random(),1,1);moved=true;},{passive:true});
    loop();
})();
</script>

{{-- PWA Service Worker --}}
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(() => {
        // Service worker registration failed, app will work in normal mode
    });
}

// iOS install prompt
if (navigator.standalone === undefined) {
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    if (isIOS) {
        document.addEventListener('DOMContentLoaded', () => {
            // User can see the banner or we can add a custom prompt
            console.log('PWA is ready on iOS. Use Share → Add to Home Screen');
        });
    }
}
</script>

@stack('scripts')
</body>
</html>

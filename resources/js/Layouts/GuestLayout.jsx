export default function GuestLayout({ children }) {
    return (
        <>
            <div className="ether-bg" />
            <div className="min-h-screen flex items-center justify-center p-4 antialiased relative z-10">
                <div className="w-full max-w-md">
                    <div className="text-center mb-8 animate-slide-up">
                        <div className="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-4 animate-float"
                             style={{ background: 'linear-gradient(135deg,#1a1200,#0a0f1e)', border: '1px solid rgba(245,158,11,0.3)', boxShadow: '0 0 40px rgba(245,158,11,0.15),0 20px 40px rgba(0,0,0,0.5)' }}>
                            <svg className="w-10 h-10" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9H4.5a2.5 2.5 0 000 5H6" stroke="#F59E0B" strokeWidth="1.5" strokeLinecap="round" />
                                <path d="M18 9h1.5a2.5 2.5 0 010 5H18" stroke="#F59E0B" strokeWidth="1.5" strokeLinecap="round" />
                                <path d="M4 22h16" stroke="#F59E0B" strokeWidth="1.5" strokeLinecap="round" />
                                <path d="M12 20v2" stroke="#F59E0B" strokeWidth="1.5" strokeLinecap="round" />
                                <path d="M6 4h12v10a6 6 0 01-12 0V4z" stroke="#F59E0B" strokeWidth="1.5" strokeLinejoin="round" />
                                <circle cx="9" cy="9" r="1" fill="#FCD34D" />
                                <circle cx="12" cy="7" r="1" fill="#FCD34D" />
                                <circle cx="15" cy="9" r="1" fill="#FCD34D" />
                            </svg>
                        </div>
                        <h1 className="text-3xl font-black font-heading tracking-tight gradient-text-gold">WorldCup Predictor</h1>
                        <p className="mt-1 text-brand-muted text-sm">جام جهانی ۲۰۲۶ — پیش‌بینی کن، امتیاز بگیر، برنده شو</p>
                    </div>
                    <div className="rounded-2xl p-8" style={{ background: 'rgba(10,15,30,0.95)', border: '1px solid #1E2D45', backdropFilter: 'blur(20px)', boxShadow: '0 0 0 1px rgba(245,158,11,0.05),0 40px 80px rgba(0,0,0,0.6)' }}>
                        {children}
                    </div>
                    <p className="text-center text-xs text-brand-subtle mt-6">سیستم داخلی پیش‌بینی جام جهانی ۲۰۲۶</p>
                </div>
            </div>
        </>
    );
}

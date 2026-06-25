import { useForm, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Login({ errors: serverErrors }) {
    const { data, setData, post, processing, errors } = useForm({ email: '', password: '', remember: false });

    const submit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <GuestLayout>
            <div className="mb-7">
                <h2 className="text-2xl font-black font-heading text-brand-text mb-1">خوش آمدید</h2>
                <p className="text-sm text-brand-muted">با حساب کاربری خود وارد شوید</p>
            </div>

            {(errors.email || serverErrors?.email) && (
                <div className="flex items-center gap-2 rounded-xl px-4 py-3 mb-5 badge badge-red text-sm w-full">
                    {errors.email || serverErrors?.email}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                <div className="space-y-1.5">
                    <label className="block text-xs font-bold text-brand-muted uppercase tracking-widest">ایمیل</label>
                    <div className="relative">
                        <svg className="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2"><path strokeLinecap="round" strokeLinejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        <input type="email" value={data.email} onChange={e => setData('email', e.target.value)} required autoFocus
                               placeholder="name@company.com"
                               className="w-full pr-10 pl-4 py-3 rounded-xl text-sm text-brand-text placeholder:text-brand-subtle outline-none transition-all duration-200"
                               style={{ background: 'rgba(255,255,255,0.04)', border: '1px solid #1E2D45' }} />
                    </div>
                </div>
                <div className="space-y-1.5">
                    <label className="block text-xs font-bold text-brand-muted uppercase tracking-widest">رمز عبور</label>
                    <div className="relative">
                        <svg className="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2"><path strokeLinecap="round" strokeLinejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <input type="password" value={data.password} onChange={e => setData('password', e.target.value)} required placeholder="••••••••"
                               className="w-full pr-10 pl-4 py-3 rounded-xl text-sm text-brand-text placeholder:text-brand-subtle outline-none transition-all duration-200"
                               style={{ background: 'rgba(255,255,255,0.04)', border: '1px solid #1E2D45' }} />
                    </div>
                </div>
                <div className="flex items-center gap-2.5">
                    <input id="remember" type="checkbox" checked={data.remember} onChange={e => setData('remember', e.target.checked)}
                           className="w-4 h-4 rounded cursor-pointer" style={{ accentColor: '#F5A623' }} />
                    <label htmlFor="remember" className="text-sm text-brand-muted cursor-pointer select-none">مرا به خاطر بسپار</label>
                </div>
                <button type="submit" disabled={processing}
                        className="w-full py-3.5 rounded-xl text-sm font-black font-heading tracking-wide cursor-pointer transition-all duration-200"
                        style={{ background: 'linear-gradient(135deg,#D97706,#F59E0B,#FCD34D)', color: '#0a0a0a', boxShadow: '0 0 30px rgba(245,158,11,0.3)' }}>
                    {processing ? 'در حال ورود...' : 'ورود به سیستم'}
                </button>
            </form>
            <div className="relative my-6">
                <div className="absolute inset-0 flex items-center"><div className="w-full" style={{ borderTop: '1px solid #1E2D45' }}></div></div>
                <div className="relative flex justify-center"><span className="px-3 text-xs text-brand-subtle" style={{ background: 'rgba(10,15,30,0.95)' }}>یا</span></div>
            </div>
            <p className="text-center text-sm text-brand-muted">
                حساب کاربری ندارید؟{' '}
                <Link href="/register" className="font-bold" style={{ color: '#F59E0B' }}>ثبت‌نام کنید</Link>
            </p>
        </GuestLayout>
    );
}

import { useForm, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({ name: '', email: '', department: '', password: '', password_confirmation: '' });

    const submit = (e) => {
        e.preventDefault();
        post('/register');
    };

    const Field = ({ label, name, type = 'text', placeholder }) => (
        <div className="space-y-1.5">
            <label className="block text-xs font-bold text-brand-muted uppercase tracking-widest">{label}</label>
            <input type={type} value={data[name]} onChange={e => setData(name, e.target.value)}
                   placeholder={placeholder} required
                   className="w-full px-4 py-3 rounded-xl text-sm text-brand-text placeholder:text-brand-subtle outline-none transition-all duration-200"
                   style={{ background: 'rgba(255,255,255,0.04)', border: `1px solid ${errors[name] ? 'rgba(255,90,90,0.5)' : '#1E2D45'}` }} />
            {errors[name] && <p className="text-xs text-red-400">{errors[name]}</p>}
        </div>
    );

    return (
        <GuestLayout>
            <div className="mb-6">
                <h2 className="text-2xl font-black font-heading text-brand-text mb-1">ثبت‌نام</h2>
                <p className="text-sm text-brand-muted">حساب کاربری جدید بسازید</p>
            </div>
            <form onSubmit={submit} className="space-y-4">
                <Field label="نام و نام خانوادگی" name="name" placeholder="علی رضایی" />
                <Field label="ایمیل" name="email" type="email" placeholder="name@company.com" />
                <Field label="دپارتمان" name="department" placeholder="فناوری اطلاعات" />
                <Field label="رمز عبور" name="password" type="password" placeholder="••••••••" />
                <Field label="تکرار رمز عبور" name="password_confirmation" type="password" placeholder="••••••••" />
                <button type="submit" disabled={processing}
                        className="w-full py-3.5 rounded-xl text-sm font-black font-heading cursor-pointer transition-all duration-200 mt-2"
                        style={{ background: 'linear-gradient(135deg,#D97706,#F59E0B,#FCD34D)', color: '#0a0a0a' }}>
                    {processing ? 'در حال ثبت‌نام...' : 'ثبت‌نام'}
                </button>
            </form>
            <p className="text-center text-sm text-brand-muted mt-6">
                قبلاً ثبت‌نام کرده‌اید؟{' '}
                <Link href="/login" className="font-bold" style={{ color: '#F59E0B' }}>وارد شوید</Link>
            </p>
        </GuestLayout>
    );
}

import { useForm } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function Profile({ user }) {
    const { data, setData, put, processing, errors, recentlySuccessful } = useForm({
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
    });

    function submit(e) {
        e.preventDefault();
        put('/profile');
    }

    return (
        <AppLayout title="پروفایل">
            <div className="max-w-lg mx-auto py-10 px-4">
                <h1 className="text-2xl font-black font-heading mb-6" style={{ color: '#00e476' }}>ویرایش پروفایل</h1>

                {recentlySuccessful && (
                    <div className="mb-5 px-4 py-3 rounded-xl text-sm font-bold"
                         style={{ background: 'rgba(0,228,118,0.12)', border: '1px solid rgba(0,228,118,0.3)', color: '#00e476' }}>
                        پروفایل با موفقیت به‌روز شد.
                    </div>
                )}

                <form onSubmit={submit} className="glass-card rounded-2xl p-6 space-y-5">
                    <div>
                        <label className="block text-xs font-bold mb-1.5" style={{ color: 'rgba(185,203,185,0.7)' }}>نام</label>
                        <input
                            type="text"
                            value={data.name}
                            onChange={e => setData('name', e.target.value)}
                            className="w-full rounded-xl px-4 py-2.5 text-sm outline-none"
                            style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}
                        />
                        {errors.name && <p className="text-xs mt-1" style={{ color: '#FF8A8A' }}>{errors.name}</p>}
                    </div>

                    <div>
                        <label className="block text-xs font-bold mb-1.5" style={{ color: 'rgba(185,203,185,0.7)' }}>ایمیل</label>
                        <input
                            type="email"
                            value={data.email}
                            onChange={e => setData('email', e.target.value)}
                            className="w-full rounded-xl px-4 py-2.5 text-sm outline-none"
                            style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}
                        />
                        {errors.email && <p className="text-xs mt-1" style={{ color: '#FF8A8A' }}>{errors.email}</p>}
                    </div>

                    <div>
                        <label className="block text-xs font-bold mb-1.5" style={{ color: 'rgba(185,203,185,0.7)' }}>رمز جدید <span style={{ color: 'rgba(185,203,185,0.4)' }}>(اختیاری)</span></label>
                        <input
                            type="password"
                            value={data.password}
                            onChange={e => setData('password', e.target.value)}
                            className="w-full rounded-xl px-4 py-2.5 text-sm outline-none"
                            style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}
                            placeholder="خالی بگذارید تا تغییر نکند"
                        />
                        {errors.password && <p className="text-xs mt-1" style={{ color: '#FF8A8A' }}>{errors.password}</p>}
                    </div>

                    <div>
                        <label className="block text-xs font-bold mb-1.5" style={{ color: 'rgba(185,203,185,0.7)' }}>تکرار رمز جدید</label>
                        <input
                            type="password"
                            value={data.password_confirmation}
                            onChange={e => setData('password_confirmation', e.target.value)}
                            className="w-full rounded-xl px-4 py-2.5 text-sm outline-none"
                            style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full py-2.5 rounded-xl text-sm font-bold cursor-pointer transition-all"
                        style={{ background: '#00e476', color: '#003919', opacity: processing ? 0.7 : 1 }}
                    >
                        {processing ? 'در حال ذخیره...' : 'ذخیره تغییرات'}
                    </button>
                </form>
            </div>
        </AppLayout>
    );
}

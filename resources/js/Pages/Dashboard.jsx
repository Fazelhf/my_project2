import { Link } from '@inertiajs/react';
import { useRef } from 'react';
import AppLayout, { useBentoGlow } from '@/Layouts/AppLayout';

function StatCard({ val, label, color, icon }) {
    const ref = useRef(null);
    useBentoGlow(ref);
    return (
        <div ref={ref} className="glass-card rounded-2xl p-4 bento-card text-center cursor-default">
            <div className="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center"
                 style={{ background: `linear-gradient(135deg,${color}33,${color}0a)`, border: `1px solid ${color}55` }}>
                <svg className="w-5 h-5" style={{ color }} fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" d={icon} />
                </svg>
            </div>
            <p className="text-2xl font-black font-heading" style={{ color }}>{val}</p>
            <p className="text-xs text-brand-muted mt-0.5">{label}</p>
        </div>
    );
}

function UpcomingCard({ game }) {
    const ref = useRef(null);
    useBentoGlow(ref);
    return (
        <Link href="/games" ref={ref} className="glass-card rounded-2xl p-4 bento-card block" style={{ borderColor: 'rgba(245,166,35,0.18)' }}>
            <div className="flex items-center justify-between mb-3">
                <span className="text-xs text-brand-muted">{game.scheduled_at_formatted}</span>
                <span className="badge badge-gold text-xs">باز</span>
            </div>
            <div className="flex items-center justify-between gap-2">
                <div className="flex-1 text-center">
                    <div className="w-10 h-10 rounded-xl mx-auto mb-1 flex items-center justify-center text-xs font-black font-heading"
                         style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}>
                        {game.home_code}
                    </div>
                    <p className="text-xs font-bold text-brand-text truncate">{game.home_name}</p>
                </div>
                <span className="text-brand-subtle text-xs font-bold flex-shrink-0">vs</span>
                <div className="flex-1 text-center">
                    <div className="w-10 h-10 rounded-xl mx-auto mb-1 flex items-center justify-center text-xs font-black font-heading"
                         style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}>
                        {game.away_code}
                    </div>
                    <p className="text-xs font-bold text-brand-text truncate">{game.away_name}</p>
                </div>
            </div>
        </Link>
    );
}

export default function Dashboard({ user, rank, accuracy, totalPredictions, upcomingGames, recentPredictions }) {
    const heroRef = useRef(null);
    useBentoGlow(heroRef);

    const stats = [
        { val: user.total_score ?? 0, label: 'امتیاز کل', color: '#F5A623', icon: 'M13 10V3L4 14h7v7l9-11h-7z' },
        { val: rank, label: 'رتبه', color: '#A78BFA', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' },
        { val: totalPredictions, label: 'پیش‌بینی', color: '#00E5A0', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2' },
        { val: `${accuracy}%`, label: 'دقت', color: '#4D9FFF', icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' },
    ];

    const ptBadge = (pts) => pts >= 10 ? 'badge-green' : pts >= 7 ? 'badge-blue' : pts >= 5 ? 'badge-gray' : 'badge-red';

    return (
        <AppLayout title="داشبورد">
            {/* Hero */}
            <div className="mb-6 animate-slide-up">
                <div ref={heroRef} className="glass-card rounded-2xl p-6 bento-card relative overflow-hidden"
                     style={{ background: 'linear-gradient(135deg,rgba(245,166,35,0.05),rgba(167,139,250,0.05))', borderColor: 'rgba(245,166,35,0.15)' }}>
                    <div className="absolute top-0 left-0 w-64 h-64 rounded-full pointer-events-none"
                         style={{ background: 'radial-gradient(circle,rgba(245,166,35,0.07),transparent 70%)', transform: 'translate(-30%,-30%)' }} />
                    <div className="relative flex items-center justify-between">
                        <div>
                            <p className="text-brand-muted text-xs mb-1">خوش آمدی</p>
                            <h1 className="text-xl font-black font-heading text-brand-text mb-2">{user.name}</h1>
                            {user.department && <span className="badge badge-purple text-xs">{user.department}</span>}
                        </div>
                        <div className="text-left">
                            <p className="text-brand-muted text-xs mb-1">امتیاز</p>
                            <p className="text-4xl font-black font-heading gradient-text-gold">{user.total_score ?? 0}</p>
                        </div>
                    </div>
                    <div className="mt-4 pt-4" style={{ borderTop: '1px solid rgba(255,255,255,0.05)' }}>
                        <p className="text-xs text-brand-subtle">
                            <span style={{ color: '#F5A623' }} className="font-semibold">FIFA World Cup 2026</span>
                            {' '}— کانادا، آمریکا، مکزیک · ۱۱ ژوئن – ۱۹ ژوئیه ۲۰۲۶
                        </p>
                    </div>
                </div>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6" style={{ animation: 'slide-up .5s .05s cubic-bezier(.16,1,.3,1) both' }}>
                {stats.map((s, i) => <StatCard key={i} {...s} />)}
            </div>

            {/* Upcoming */}
            {upcomingGames?.length > 0 && (
                <div className="mb-6" style={{ animation: 'slide-up .5s .1s cubic-bezier(.16,1,.3,1) both' }}>
                    <div className="flex items-center gap-3 mb-4">
                        <div className="w-1 h-5 rounded-full" style={{ background: 'linear-gradient(180deg,#F5A623,#A78BFA)' }} />
                        <h2 className="font-black text-sm font-heading text-brand-text">بازی‌های پیش رو بدون پیش‌بینی</h2>
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        {upcomingGames.slice(0, 6).map(g => <UpcomingCard key={g.id} game={g} />)}
                    </div>
                </div>
            )}

            {/* Recent predictions */}
            {recentPredictions?.length > 0 && (
                <div style={{ animation: 'slide-up .5s .15s cubic-bezier(.16,1,.3,1) both' }}>
                    <div className="flex items-center gap-3 mb-4">
                        <div className="w-1 h-5 rounded-full" style={{ background: 'linear-gradient(180deg,#00E5A0,#4D9FFF)' }} />
                        <h2 className="font-black text-sm font-heading text-brand-text">پیش‌بینی‌های اخیر</h2>
                    </div>
                    <div className="glass rounded-2xl overflow-hidden">
                        <table className="w-full text-sm">
                            <thead>
                                <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.06)', background: 'rgba(255,255,255,0.02)' }}>
                                    <th className="px-4 py-3 text-right text-xs font-bold text-brand-subtle">بازی</th>
                                    <th className="px-4 py-3 text-center text-xs font-bold text-brand-subtle">پیش‌بینی</th>
                                    <th className="px-4 py-3 text-center text-xs font-bold text-brand-subtle">نتیجه</th>
                                    <th className="px-4 py-3 text-center text-xs font-bold text-brand-subtle">امتیاز</th>
                                </tr>
                            </thead>
                            <tbody>
                                {recentPredictions.map(p => (
                                    <tr key={p.id} style={{ borderBottom: '1px solid rgba(255,255,255,0.04)' }}
                                        onMouseOver={e => e.currentTarget.style.background = 'rgba(255,255,255,0.03)'}
                                        onMouseOut={e => e.currentTarget.style.background = ''}>
                                        <td className="px-4 py-3 font-semibold text-brand-text text-xs">
                                            {p.home_code} <span className="text-brand-subtle">vs</span> {p.away_code}
                                        </td>
                                        <td className="px-4 py-3 text-center font-heading font-black text-brand-text">
                                            {p.home_score}–{p.away_score}
                                        </td>
                                        <td className="px-4 py-3 text-center font-heading font-black text-brand-muted">
                                            {p.finished ? `${p.real_home}–${p.real_away}` : <span className="text-brand-subtle">—</span>}
                                        </td>
                                        <td className="px-4 py-3 text-center">
                                            {p.points_earned != null
                                                ? <span className={`badge ${ptBadge(p.points_earned)}`}>+{p.points_earned}</span>
                                                : <span className="text-brand-subtle text-xs">—</span>}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            )}

            {!upcomingGames?.length && !recentPredictions?.length && (
                <div className="glass-card rounded-2xl p-16 text-center bento-card">
                    <p className="text-brand-muted text-sm mb-4">هنوز پیش‌بینی‌ای ثبت نشده</p>
                    <Link href="/games" className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold cursor-pointer transition-all"
                          style={{ background: 'linear-gradient(135deg,#D4890A,#F5A623)', color: '#0a0a0a' }}>
                        شروع پیش‌بینی
                    </Link>
                </div>
            )}
        </AppLayout>
    );
}

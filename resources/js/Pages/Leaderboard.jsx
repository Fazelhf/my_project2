import { usePage } from '@inertiajs/react';
import { useRef, useState } from 'react';
import AppLayout, { useBentoGlow } from '@/Layouts/AppLayout';

function PodiumCard({ user, rank }) {
    const ref = useRef(null);
    useBentoGlow(ref);
    const colors = { 1: '#F5A623', 2: '#A78BFA', 3: '#00E5A0' };
    const color = colors[rank] ?? '#8BA0C4';
    const sizes = { 1: 'h-24', 2: 'h-16', 3: 'h-12' };

    return (
        <div ref={ref} className={`glass-card rounded-2xl p-4 bento-card text-center flex flex-col justify-end ${rank === 1 ? 'order-2' : rank === 2 ? 'order-1' : 'order-3'}`}
             style={{ borderColor: `${color}33` }}>
            <div className="w-12 h-12 rounded-2xl mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
                 style={{ background: `${color}22`, border: `1px solid ${color}44`, color }}>
                {rank}
            </div>
            <p className="text-sm font-black font-heading text-brand-text truncate">{user.name}</p>
            {user.department && <p className="text-xs text-brand-muted truncate">{user.department}</p>}
            <p className="text-2xl font-black font-heading mt-1" style={{ color }}>{user.total_score}</p>
            <div className={`mt-2 rounded-t-xl w-full ${sizes[rank]}`} style={{ background: `${color}15`, border: `1px solid ${color}22` }} />
        </div>
    );
}

function H2HModal({ userA, userB, finishedGames, predictions, onClose }) {
    const predsA = predictions[userA.id] ?? {};
    const predsB = predictions[userB.id] ?? {};

    let scoreA = 0, scoreB = 0;
    const rows = finishedGames.map(g => {
        const pA = predsA[g.id];
        const pB = predsB[g.id];
        if (!pA && !pB) return null;
        const ptA = pA?.points_earned ?? 0;
        const ptB = pB?.points_earned ?? 0;
        if (ptA > ptB) scoreA++;
        else if (ptB > ptA) scoreB++;
        return { g, pA, pB, ptA, ptB };
    }).filter(Boolean);

    const badgeClass = (pts) => pts >= 10 ? 'badge-green' : pts >= 7 ? 'badge-blue' : pts >= 5 ? 'badge-gray' : 'badge-red';

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4"
             style={{ background: 'rgba(0,0,0,0.75)', backdropFilter: 'blur(8px)' }}
             onClick={e => e.target === e.currentTarget && onClose()}>
            <div className="glass-strong rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden"
                 style={{ borderColor: 'rgba(77,159,255,0.2)' }}>
                <div className="flex items-center justify-between px-6 py-4"
                     style={{ borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
                    <div className="flex items-center gap-4">
                        <span className="font-black text-sm font-heading text-brand-text">{userA.name}</span>
                        <div className="px-3 py-1 rounded-xl text-xs font-black font-heading"
                             style={{ background: 'rgba(77,159,255,0.1)', border: '1px solid rgba(77,159,255,0.2)', color: '#4D9FFF' }}>
                            {scoreA} – {scoreB}
                        </div>
                        <span className="font-black text-sm font-heading text-brand-text">{userB.name}</span>
                    </div>
                    <button onClick={onClose} className="w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer"
                            style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }}>
                        <svg className="w-4 h-4 text-brand-muted" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div className="overflow-y-auto flex-1 p-4">
                    {rows.length === 0 ? (
                        <p className="text-center text-brand-muted text-sm py-8">پیش‌بینی مشترکی یافت نشد</p>
                    ) : (
                        <table className="w-full text-xs">
                            <thead>
                                <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.06)' }}>
                                    <th className="px-3 py-2 text-right text-brand-subtle font-bold">بازی</th>
                                    <th className="px-3 py-2 text-center text-brand-subtle font-bold">نتیجه</th>
                                    <th className="px-3 py-2 text-center font-bold" style={{ color: '#4D9FFF' }}>{userA.name}</th>
                                    <th className="px-3 py-2 text-center font-bold" style={{ color: '#A78BFA' }}>{userB.name}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {rows.map(({ g, pA, pB, ptA, ptB }) => (
                                    <tr key={g.id} style={{ borderBottom: '1px solid rgba(255,255,255,0.04)' }}>
                                        <td className="px-3 py-2 text-brand-muted">
                                            {g.home_code} <span className="text-brand-subtle">vs</span> {g.away_code}
                                        </td>
                                        <td className="px-3 py-2 text-center font-heading font-black text-brand-text">
                                            {g.home_score}–{g.away_score}
                                        </td>
                                        <td className="px-3 py-2 text-center">
                                            {pA ? (
                                                <div className="flex flex-col items-center gap-0.5">
                                                    <span className="font-heading font-black text-brand-text">{pA.home_score}–{pA.away_score}</span>
                                                    <span className={`badge ${badgeClass(ptA)} text-[10px]`}>+{ptA}</span>
                                                </div>
                                            ) : <span className="text-brand-subtle">—</span>}
                                        </td>
                                        <td className="px-3 py-2 text-center">
                                            {pB ? (
                                                <div className="flex flex-col items-center gap-0.5">
                                                    <span className="font-heading font-black text-brand-text">{pB.home_score}–{pB.away_score}</span>
                                                    <span className={`badge ${badgeClass(ptB)} text-[10px]`}>+{ptB}</span>
                                                </div>
                                            ) : <span className="text-brand-subtle">—</span>}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </div>
            </div>
        </div>
    );
}

export default function Leaderboard({ users, finishedGames, predictions }) {
    const { auth } = usePage().props;
    const [h2h, setH2H] = useState(null);
    const currentUser = auth?.user;

    const top3 = users.slice(0, 3);
    const rest  = users.slice(3);

    const handleRowClick = (user) => {
        if (!currentUser || user.id === currentUser.id) return;
        const me = users.find(u => u.id === currentUser.id);
        if (me) setH2H({ userA: me, userB: user });
    };

    return (
        <AppLayout title="جدول امتیازات">
            {/* Podium */}
            {top3.length >= 3 && (
                <div className="mb-8 animate-slide-up">
                    <div className="flex items-end justify-center gap-3 max-w-md mx-auto">
                        {top3.map((u, i) => <PodiumCard key={u.id} user={u} rank={i + 1} />)}
                    </div>
                </div>
            )}

            {/* Full table */}
            <div className="glass rounded-2xl overflow-hidden animate-slide-up" style={{ animation: 'slide-up .5s .1s cubic-bezier(.16,1,.3,1) both' }}>
                <table className="w-full text-sm">
                    <thead>
                        <tr style={{ borderBottom: '1px solid rgba(255,255,255,0.06)', background: 'rgba(255,255,255,0.02)' }}>
                            <th className="px-4 py-3 text-right text-xs font-bold text-brand-subtle w-10">#</th>
                            <th className="px-4 py-3 text-right text-xs font-bold text-brand-subtle">نام</th>
                            <th className="px-4 py-3 text-center text-xs font-bold text-brand-subtle hidden sm:table-cell">دپارتمان</th>
                            <th className="px-4 py-3 text-center text-xs font-bold text-brand-subtle">امتیاز</th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.map((u, i) => {
                            const isMe = currentUser?.id === u.id;
                            const rankColors = ['#F5A623', '#A78BFA', '#00E5A0'];
                            const rankColor = rankColors[i] ?? null;
                            return (
                                <tr key={u.id}
                                    onClick={() => handleRowClick(u)}
                                    style={{
                                        borderBottom: '1px solid rgba(255,255,255,0.04)',
                                        background: isMe ? 'rgba(77,159,255,0.06)' : '',
                                        cursor: (!isMe && currentUser) ? 'pointer' : 'default',
                                    }}
                                    onMouseOver={e => { if (!isMe && currentUser) e.currentTarget.style.background = 'rgba(255,255,255,0.04)'; }}
                                    onMouseOut={e => { e.currentTarget.style.background = isMe ? 'rgba(77,159,255,0.06)' : ''; }}>
                                    <td className="px-4 py-3 text-center">
                                        <span className="text-xs font-black font-heading" style={{ color: rankColor ?? '#8BA0C4' }}>{i + 1}</span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <span className="font-semibold text-brand-text text-sm">{u.name}</span>
                                        {isMe && <span className="badge badge-blue text-[10px] mr-2">شما</span>}
                                    </td>
                                    <td className="px-4 py-3 text-center text-xs text-brand-muted hidden sm:table-cell">{u.department ?? '—'}</td>
                                    <td className="px-4 py-3 text-center">
                                        <span className="font-black text-base font-heading" style={{ color: rankColor ?? '#F0F4FF' }}>{u.total_score}</span>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
            </div>

            {currentUser && (
                <p className="text-center text-xs text-brand-subtle mt-4">
                    روی نام هر کاربر کلیک کنید تا مقایسه مستقیم با شما نمایش داده شود
                </p>
            )}

            {h2h && (
                <H2HModal
                    userA={h2h.userA}
                    userB={h2h.userB}
                    finishedGames={finishedGames}
                    predictions={predictions}
                    onClose={() => setH2H(null)}
                />
            )}
        </AppLayout>
    );
}

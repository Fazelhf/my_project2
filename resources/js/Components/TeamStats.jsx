import { useEffect, useState } from 'react';
import { router } from '@inertiajs/react';
import axios from 'axios';

function StatBar({ label, val, max, color }) {
    const pct = max > 0 ? Math.round((val / max) * 100) : 0;
    return (
        <div>
            <div className="flex justify-between text-xs mb-1">
                <span className="text-brand-muted">{label}</span>
                <span className="font-bold" style={{ color }}>{val}</span>
            </div>
            <div className="h-1.5 rounded-full" style={{ background: 'rgba(255,255,255,0.08)' }}>
                <div className="h-full rounded-full transition-all duration-700" style={{ width: `${pct}%`, background: color }} />
            </div>
        </div>
    );
}

export default function TeamStats({ teamId, teamName, onClose }) {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        axios.get(`/api/teams/${teamId}/stats`)
            .then(r => setData(r.data))
            .catch(() => setData(null))
            .finally(() => setLoading(false));
    }, [teamId]);

    const team = data?.team;
    const stats = data?.stats;
    const recent = data?.recent_matches;
    const winPct = stats ? Math.round((stats.wins / Math.max(stats.played, 1)) * 100) : 0;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4"
             style={{ background: 'rgba(0,0,0,0.7)', backdropFilter: 'blur(8px)' }}
             onClick={e => e.target === e.currentTarget && onClose()}>
            <div className="glass-strong rounded-3xl w-full max-w-lg max-h-[85vh] flex flex-col overflow-hidden"
                 style={{ borderColor: 'rgba(77,159,255,0.2)' }}>
                {/* Header */}
                <div className="flex items-center justify-between px-6 py-4" style={{ borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
                    <div className="flex items-center gap-3">
                        <div className="w-12 h-12 rounded-2xl flex items-center justify-center text-lg font-black font-heading"
                             style={{ background: 'linear-gradient(135deg,rgba(77,159,255,0.2),rgba(167,139,250,0.2))', border: '1px solid rgba(77,159,255,0.3)', color: '#F0F4FF' }}>
                            {team?.code ?? teamName?.slice(0, 3).toUpperCase()}
                        </div>
                        <div>
                            <h2 className="font-black text-base font-heading text-brand-text">{teamName}</h2>
                            {team?.group_name && <p className="text-xs text-brand-muted">گروه {team.group_name}</p>}
                        </div>
                    </div>
                    <button onClick={onClose} className="w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer transition-all"
                            style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }}
                            onMouseOver={e => e.currentTarget.style.background = 'rgba(255,90,90,0.15)'}
                            onMouseOut={e => e.currentTarget.style.background = 'rgba(255,255,255,0.06)'}>
                        <svg className="w-4 h-4 text-brand-muted" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div className="overflow-y-auto flex-1 p-5 space-y-5">
                    {loading && (
                        <div className="flex items-center justify-center py-12">
                            <div className="w-8 h-8 rounded-full border-2 border-t-transparent animate-spin" style={{ borderColor: 'rgba(77,159,255,0.3)', borderTopColor: '#4D9FFF' }} />
                        </div>
                    )}

                    {!loading && stats && (
                        <>
                            {/* Win probability */}
                            <div className="glass-card rounded-2xl p-4">
                                <p className="text-xs text-brand-subtle mb-3 font-bold uppercase tracking-widest">آمار کلی</p>
                                <div className="grid grid-cols-4 gap-2 mb-4 text-center">
                                    {[
                                        { val: stats.played, label: 'بازی', color: '#8BA0C4' },
                                        { val: stats.wins, label: 'برد', color: '#00E5A0' },
                                        { val: stats.draws, label: 'مساوی', color: '#F5A623' },
                                        { val: stats.losses, label: 'باخت', color: '#FF5A5A' },
                                    ].map(s => (
                                        <div key={s.label} className="rounded-xl py-2" style={{ background: 'rgba(255,255,255,0.04)' }}>
                                            <p className="text-xl font-black font-heading" style={{ color: s.color }}>{s.val}</p>
                                            <p className="text-[10px] text-brand-muted">{s.label}</p>
                                        </div>
                                    ))}
                                </div>
                                <div className="space-y-2.5">
                                    <StatBar label="درصد برد" val={`${winPct}%`} max={100} color="#00E5A0" />
                                    <StatBar label="گل زده" val={stats.goals_for} max={Math.max(stats.goals_for, 20)} color="#4D9FFF" />
                                    <StatBar label="گل خورده" val={stats.goals_against} max={Math.max(stats.goals_for, 20)} color="#FF5A5A" />
                                </div>
                            </div>

                            {/* Win probability gauge */}
                            <div className="glass-card rounded-2xl p-4 text-center">
                                <p className="text-xs text-brand-subtle mb-2 font-bold uppercase tracking-widest">شانس پیروزی</p>
                                <div className="relative inline-flex items-center justify-center w-24 h-24">
                                    <svg className="w-24 h-24 -rotate-90" viewBox="0 0 36 36">
                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,0.08)" strokeWidth="3" />
                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#00E5A0" strokeWidth="3"
                                                strokeDasharray={`${winPct} ${100 - winPct}`} strokeLinecap="round" />
                                    </svg>
                                    <span className="absolute text-2xl font-black font-heading" style={{ color: '#00E5A0' }}>{winPct}%</span>
                                </div>
                                <p className="text-xs text-brand-muted mt-1">بر اساس {stats.played} بازی اخیر</p>
                            </div>

                            {/* Recent matches */}
                            {recent?.length > 0 && (
                                <div>
                                    <p className="text-xs text-brand-subtle mb-3 font-bold uppercase tracking-widest">بازی‌های اخیر</p>
                                    <div className="space-y-2">
                                        {recent.map(m => {
                                            const isHome = m.home_team_id === teamId;
                                            const myGoals = isHome ? m.home_score : m.away_score;
                                            const oppGoals = isHome ? m.away_score : m.home_score;
                                            const oppName = isHome ? m.away_name : m.home_name;
                                            const result = myGoals > oppGoals ? 'W' : myGoals < oppGoals ? 'L' : 'D';
                                            const rColor = result === 'W' ? '#00E5A0' : result === 'L' ? '#FF5A5A' : '#F5A623';
                                            return (
                                                <div key={m.id} className="flex items-center justify-between rounded-xl px-3 py-2"
                                                     style={{ background: 'rgba(255,255,255,0.03)', border: '1px solid rgba(255,255,255,0.06)' }}>
                                                    <div className="flex items-center gap-2">
                                                        <div className="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black"
                                                             style={{ background: `${rColor}20`, color: rColor }}>{result}</div>
                                                        <span className="text-xs text-brand-muted">{oppName}</span>
                                                    </div>
                                                    <span className="text-sm font-black font-heading text-brand-text">{myGoals}–{oppGoals}</span>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}
                        </>
                    )}

                    {!loading && !stats && (
                        <p className="text-center text-brand-muted text-sm py-8">آمار بازی‌های این تیم موجود نیست</p>
                    )}
                </div>
            </div>
        </div>
    );
}

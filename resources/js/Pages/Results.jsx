import { useRef, useState } from 'react';
import AppLayout, { useBentoGlow } from '@/Layouts/AppLayout';

const STAGE_LABELS = {
    group: 'گروهی',
    round_of_32: 'یک‌شانزدهم',
    round_of_16: 'یک‌هشتم',
    quarter_final: 'ربع نهایی',
    semi_final: 'نیمه نهایی',
    third_place: 'رده‌بندی',
    final: 'فینال',
};

function ResultCard({ game }) {
    const ref = useRef(null);
    useBentoGlow(ref);
    const [expanded, setExpanded] = useState(false);

    const homeWin = game.home_score > game.away_score;
    const awayWin = game.away_score > game.home_score;

    return (
        <div ref={ref} className="glass-card rounded-2xl overflow-hidden bento-card"
             style={{ borderColor: 'rgba(77,159,255,0.15)' }}>
            <div className="px-4 py-2.5 flex items-center justify-between"
                 style={{ background: 'rgba(255,255,255,0.02)', borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                <span className="text-xs text-brand-muted">{game.scheduled_at_formatted}</span>
                <div className="flex items-center gap-2">
                    {game.stage && (
                        <span className="badge badge-gray text-xs">{STAGE_LABELS[game.stage] ?? game.stage}</span>
                    )}
                    <span className="badge badge-gray text-xs">پایان یافته</span>
                </div>
            </div>
            <div className="px-5 py-4">
                <div className="flex items-center justify-between gap-3 mb-3">
                    <div className="flex-1 text-center">
                        <div className="w-11 h-11 rounded-xl mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading"
                             style={{ background: homeWin ? 'rgba(0,229,160,0.15)' : 'rgba(255,255,255,0.06)', border: `1px solid ${homeWin ? 'rgba(0,229,160,0.3)' : 'rgba(255,255,255,0.1)'}`, color: '#F0F4FF' }}>
                            {game.home_code}
                        </div>
                        <p className="text-xs font-bold text-brand-text leading-tight">{game.home_name}</p>
                    </div>
                    <div className="px-4 py-2 rounded-xl" style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }}>
                        <span className="font-black text-xl font-heading text-brand-text">
                            {game.home_score}<span className="text-brand-subtle mx-1.5 text-base">–</span>{game.away_score}
                        </span>
                    </div>
                    <div className="flex-1 text-center">
                        <div className="w-11 h-11 rounded-xl mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading"
                             style={{ background: awayWin ? 'rgba(0,229,160,0.15)' : 'rgba(255,255,255,0.06)', border: `1px solid ${awayWin ? 'rgba(0,229,160,0.3)' : 'rgba(255,255,255,0.1)'}`, color: '#F0F4FF' }}>
                            {game.away_code}
                        </div>
                        <p className="text-xs font-bold text-brand-text leading-tight">{game.away_name}</p>
                    </div>
                </div>

                {game.predictions?.length > 0 && (
                    <button onClick={() => setExpanded(v => !v)}
                            className="w-full text-xs text-brand-muted py-1.5 rounded-xl transition-all cursor-pointer"
                            style={{ background: 'rgba(255,255,255,0.03)', border: '1px solid rgba(255,255,255,0.06)' }}>
                        {expanded ? 'پنهان کردن' : `${game.predictions.length} پیش‌بینی`} ←
                    </button>
                )}

                {expanded && (
                    <div className="mt-2 space-y-1.5">
                        {game.predictions.map((p, i) => {
                            const pHome = p.home_score, pAway = p.away_score;
                            const pts = p.points_earned;
                            const badge = pts >= 10 ? 'badge-green' : pts >= 7 ? 'badge-blue' : pts >= 5 ? 'badge-gray' : pts >= 2 ? 'badge-gray' : 'badge-red';
                            return (
                                <div key={i} className="flex items-center justify-between px-3 py-1.5 rounded-lg"
                                     style={{ background: 'rgba(255,255,255,0.02)' }}>
                                    <span className="text-xs text-brand-muted">{p.user_name}</span>
                                    <div className="flex items-center gap-2">
                                        <span className="text-xs font-black font-heading text-brand-text">{pHome}–{pAway}</span>
                                        {pts != null && <span className={`badge ${badge} text-xs`}>+{pts}</span>}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </div>
    );
}

export default function Results({ games }) {
    return (
        <AppLayout title="نتایج بازی‌ها">
            {games?.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 animate-slide-up">
                    {games.map(g => <ResultCard key={g.id} game={g} />)}
                </div>
            ) : (
                <div className="glass-card rounded-2xl p-16 text-center">
                    <p className="text-brand-muted text-sm">هنوز بازی‌ای پایان نیافته است</p>
                </div>
            )}
        </AppLayout>
    );
}

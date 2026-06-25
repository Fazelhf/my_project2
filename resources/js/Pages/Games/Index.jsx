import { useForm, router } from '@inertiajs/react';
import { useRef, useState } from 'react';
import AppLayout, { useBentoGlow } from '@/Layouts/AppLayout';
import TeamStats from '@/Components/TeamStats';

const STAGE_LABELS = {
    group: { label: 'مرحله گروهی', icon: 'G', color: '#4D9FFF' },
    round_of_32: { label: 'یک‌شانزدهم نهایی', icon: '32', color: '#A78BFA' },
    round_of_16: { label: 'یک‌هشتم نهایی', icon: '16', color: '#A78BFA' },
    quarter_final: { label: 'ربع نهایی', icon: 'QF', color: '#00E5A0' },
    semi_final: { label: 'نیمه نهایی', icon: 'SF', color: '#F5A623' },
    third_place: { label: 'رده‌بندی سوم', icon: '3P', color: '#FF8A8A' },
    final: { label: 'فینال', icon: 'F', color: '#F5A623' },
};

function PredictForm({ game, prediction }) {
    const { data, setData, post, put, processing } = useForm({
        home_score: prediction?.home_score ?? 0,
        away_score: prediction?.away_score ?? 0,
    });

    const submit = (e) => {
        e.preventDefault();
        if (prediction) {
            put(`/games/${game.id}/predict`);
        } else {
            post(`/games/${game.id}/predict`);
        }
    };

    if (prediction) {
        return (
            <div className="flex items-center gap-2">
                <div className="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-xl"
                     style={{ background: 'rgba(255,255,255,0.04)', border: '1px solid rgba(255,255,255,0.08)' }}>
                    <span className="text-xs text-brand-muted">پیش‌بینی:</span>
                    <span className="font-black text-sm font-heading text-brand-text">{prediction.home_score}–{prediction.away_score}</span>
                    {prediction.points_earned != null && (
                        <span className={`badge ${prediction.points_earned >= 7 ? 'badge-green' : prediction.points_earned >= 5 ? 'badge-blue' : prediction.points_earned >= 2 ? 'badge-gray' : 'badge-red'} text-xs`}>
                            +{prediction.points_earned}
                        </span>
                    )}
                </div>
                {!game.locked && !game.finished && (
                    <form onSubmit={submit} className="flex items-center gap-1 flex-shrink-0">
                        <input type="number" value={data.home_score} onChange={e => setData('home_score', +e.target.value)} min="0" max="99"
                               className="w-10 py-2 rounded-lg text-center text-sm font-black font-heading text-brand-text"
                               style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }} />
                        <span className="text-brand-subtle text-xs">–</span>
                        <input type="number" value={data.away_score} onChange={e => setData('away_score', +e.target.value)} min="0" max="99"
                               className="w-10 py-2 rounded-lg text-center text-sm font-black font-heading text-brand-text"
                               style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }} />
                        <button type="submit" disabled={processing}
                                className="px-2.5 py-2 rounded-lg text-xs font-black cursor-pointer transition-all"
                                style={{ background: 'rgba(245,166,35,0.15)', border: '1px solid rgba(245,166,35,0.3)', color: '#F5A623' }}>
                            ویرایش
                        </button>
                    </form>
                )}
            </div>
        );
    }

    if (!game.locked && !game.finished) {
        return (
            <form onSubmit={submit} className="flex items-center gap-2">
                <input type="number" value={data.home_score} onChange={e => setData('home_score', +e.target.value)} min="0" max="99"
                       className="flex-1 py-2.5 rounded-xl text-center text-sm font-black font-heading text-brand-text"
                       style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }} />
                <span className="font-bold text-brand-subtle">–</span>
                <input type="number" value={data.away_score} onChange={e => setData('away_score', +e.target.value)} min="0" max="99"
                       className="flex-1 py-2.5 rounded-xl text-center text-sm font-black font-heading text-brand-text"
                       style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }} />
                <button type="submit" disabled={processing}
                        className="px-4 py-2.5 rounded-xl text-sm font-black cursor-pointer transition-all flex-shrink-0"
                        style={{ background: 'linear-gradient(135deg,#D4890A,#F5A623)', color: '#0a0a0a' }}>
                    ثبت
                </button>
            </form>
        );
    }

    return (
        <p className="text-xs text-center py-2 text-brand-subtle font-semibold">
            {game.locked ? 'زمان پیش‌بینی پایان یافته' : 'بدون پیش‌بینی'}
        </p>
    );
}

function GameCard({ game, onTeamClick }) {
    const ref = useRef(null);
    useBentoGlow(ref);

    const bc = game.finished ? 'rgba(100,116,139,0.3)' : game.prediction ? 'rgba(0,229,160,0.3)' : game.locked ? 'rgba(255,90,90,0.25)' : 'rgba(245,166,35,0.25)';

    return (
        <div ref={ref} className="glass-card rounded-2xl overflow-hidden bento-card" style={{ borderColor: bc }}>
            <div className="px-4 py-2.5 flex items-center justify-between"
                 style={{ background: game.finished ? 'rgba(100,116,139,0.04)' : game.prediction ? 'rgba(0,229,160,0.04)' : 'transparent', borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                <span className="text-xs text-brand-muted">{game.scheduled_at_formatted}</span>
                {game.finished
                    ? <span className="badge badge-gray text-xs">پایان یافته</span>
                    : game.locked ? <span className="badge badge-red text-xs">قفل شده</span>
                    : game.prediction ? <span className="badge badge-green text-xs">ثبت شده</span>
                    : <span className="badge badge-gold text-xs">باز</span>}
            </div>
            <div className="px-5 py-4">
                <div className="flex items-center justify-between gap-2 mb-4">
                    <div className="flex-1 text-center">
                        <button onClick={() => onTeamClick(game.home_team_id, game.home_name)}
                                className="w-11 h-11 rounded-xl mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading cursor-pointer transition-all hover:scale-110"
                                style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}>
                            {game.home_code}
                        </button>
                        <p className="text-xs font-bold text-brand-text leading-tight">{game.home_name}</p>
                    </div>
                    <div className="flex flex-col items-center gap-1 px-2">
                        {game.finished
                            ? <div className="px-3 py-1.5 rounded-xl" style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)' }}>
                                <span className="font-black text-lg font-heading text-brand-text">{game.home_score}<span className="text-brand-subtle mx-1 text-sm">–</span>{game.away_score}</span>
                              </div>
                            : <div className="w-9 h-9 rounded-xl flex items-center justify-center" style={{ background: 'rgba(255,255,255,0.04)', border: '1px solid rgba(255,255,255,0.08)' }}>
                                <span className="text-xs font-bold text-brand-subtle">vs</span>
                              </div>}
                    </div>
                    <div className="flex-1 text-center">
                        <button onClick={() => onTeamClick(game.away_team_id, game.away_name)}
                                className="w-11 h-11 rounded-xl mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading cursor-pointer transition-all hover:scale-110"
                                style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', color: '#F0F4FF' }}>
                            {game.away_code}
                        </button>
                        <p className="text-xs font-bold text-brand-text leading-tight">{game.away_name}</p>
                    </div>
                </div>
                {game.venue && <p className="text-xs text-brand-subtle text-center mb-3 truncate">{game.venue}</p>}
                <div style={{ borderTop: '1px solid rgba(255,255,255,0.06)', paddingTop: 12 }}>
                    <PredictForm game={game} prediction={game.prediction} />
                </div>
            </div>
        </div>
    );
}

export default function GamesIndex({ gamesByStage }) {
    const [selectedTeam, setSelectedTeam] = useState(null);

    const stages = Object.keys(STAGE_LABELS).filter(s => gamesByStage[s]?.length);

    return (
        <AppLayout title="پیش‌بینی بازی‌ها">
            {stages.map(stage => {
                const info = STAGE_LABELS[stage];
                const games = gamesByStage[stage];
                return (
                    <div key={stage} className="mb-10 animate-slide-up">
                        <div className="flex items-center gap-3 mb-4">
                            <div className="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black font-heading flex-shrink-0"
                                 style={{ background: `linear-gradient(135deg,${info.color}22,${info.color}0a)`, border: `1px solid ${info.color}40`, color: info.color }}>
                                {info.icon}
                            </div>
                            <h2 className="text-base font-black font-heading text-brand-text">{info.label}</h2>
                            <div className="flex-1 h-px" style={{ background: 'linear-gradient(90deg,rgba(255,255,255,0.08),transparent)' }} />
                            <span className="text-xs text-brand-subtle">{games.length} بازی</span>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                            {games.map(g => <GameCard key={g.id} game={g} onTeamClick={(id, name) => setSelectedTeam({ id, name })} />)}
                        </div>
                    </div>
                );
            })}

            {selectedTeam && (
                <TeamStats teamId={selectedTeam.id} teamName={selectedTeam.name} onClose={() => setSelectedTeam(null)} />
            )}
        </AppLayout>
    );
}

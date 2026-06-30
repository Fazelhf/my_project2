@php
    $isMe = $comment->user_id === auth()->id();
    $likeCount = $comment->likes->count();
    $iLiked = $comment->isLikedBy(auth()->id());
@endphp
<div class="group" id="comment-{{ $comment->id }}">
    <div class="flex items-start gap-3">
        {{-- Avatar --}}
        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black flex-shrink-0 mt-0.5"
             style="{{ $isMe ? 'background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;' : 'background:rgba(255,255,255,0.08);color:#F0F4FF;' }}">
            {{ mb_strtoupper(mb_substr($comment->user->name, 0, 1, 'UTF-8')) }}
        </div>

        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="flex items-center gap-2 flex-wrap mb-1">
                <span class="text-xs font-bold" style="color:{{ $isMe ? '#00e476' : 'rgba(185,203,185,0.8)' }};">
                    {{ $isMe ? 'شما' : $comment->user->name }}
                </span>
                @if($comment->user->username)
                    <span class="text-[10px]" style="color:rgba(185,203,185,0.3);">@{{ $comment->user->username }}</span>
                @endif
                <span class="text-[10px]" style="color:rgba(185,203,185,0.3);">·</span>
                <span class="text-[10px]" style="color:rgba(185,203,185,0.3);">{{ $comment->created_at->diffForHumans() }}</span>
            </div>

            {{-- Body --}}
            <div class="text-sm leading-relaxed mb-2" style="color:rgba(185,203,185,0.85);">
                {{ $comment->body }}
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                {{-- Like --}}
                <button onclick="likeComment({{ $comment->id }}, this)"
                        class="flex items-center gap-1 text-xs cursor-pointer transition-colors"
                        style="background:none;border:none;padding:0;">
                    <span class="material-symbols-outlined like-icon text-sm"
                          style="color:{{ $iLiked ? '#ff5a8a' : 'rgba(185,203,185,0.4)' }};font-variation-settings:{{ $iLiked ? "'FILL' 1" : "'FILL' 0" }},'wght' 400,'GRAD' 0,'opsz' 20;">favorite</span>
                    <span class="like-count text-xs" style="color:rgba(185,203,185,0.5);">{{ $likeCount > 0 ? $likeCount : '' }}</span>
                </button>

                {{-- Reply toggle --}}
                <button onclick="toggleReplyBox({{ $comment->id }})"
                        class="text-xs cursor-pointer transition-colors"
                        style="background:none;border:none;padding:0;color:rgba(185,203,185,0.4);"
                        onmouseover="this.style.color='#00e476'" onmouseout="this.style.color='rgba(185,203,185,0.4)'">
                    پاسخ
                </button>

                {{-- Delete --}}
                @if($isMe || auth()->user()->is_admin)
                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline" onsubmit="return confirm('این نظر حذف شود؟');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs cursor-pointer transition-colors"
                            style="background:none;border:none;padding:0;color:rgba(255,90,90,0.4);"
                            onmouseover="this.style.color='#ff5a5a'" onmouseout="this.style.color='rgba(255,90,90,0.4)'">
                        حذف
                    </button>
                </form>
                @endif
            </div>

            {{-- Reply form (hidden by default) --}}
            <div id="reply-box-{{ $comment->id }}" class="mt-3" style="display:none;">
                <form action="{{ route('games.comments.store', $game) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black flex-shrink-0"
                         style="background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1, 'UTF-8')) }}
                    </div>
                    <div class="flex-1 flex gap-2">
                        <input type="text" name="body" required maxlength="500"
                               placeholder="پاسخ..."
                               class="stitch-input flex-1 text-sm" style="padding:8px 12px;">
                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold cursor-pointer"
                                style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">
                            ارسال
                        </button>
                    </div>
                </form>
            </div>

            {{-- Replies --}}
            @if($comment->replies->isNotEmpty())
            <div class="mt-3 space-y-3 pr-4" style="border-right:2px solid rgba(0,228,118,0.15);">
                @foreach($comment->replies->where('is_deleted', false) as $reply)
                @php
                    $rIsMe = $reply->user_id === auth()->id();
                    $rLiked = $reply->isLikedBy(auth()->id());
                @endphp
                <div class="flex items-start gap-2" id="comment-{{ $reply->id }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black flex-shrink-0"
                         style="{{ $rIsMe ? 'background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;' : 'background:rgba(255,255,255,0.08);color:#F0F4FF;' }}">
                        {{ mb_strtoupper(mb_substr($reply->user->name, 0, 1, 'UTF-8')) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="text-[10px] font-bold" style="color:{{ $rIsMe ? '#00e476' : 'rgba(185,203,185,0.7)' }};">{{ $rIsMe ? 'شما' : $reply->user->name }}</span>
                            <span class="text-[9px]" style="color:rgba(185,203,185,0.3);">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs leading-relaxed" style="color:rgba(185,203,185,0.75);">{{ $reply->body }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <button onclick="likeComment({{ $reply->id }}, this)"
                                    class="flex items-center gap-1 cursor-pointer"
                                    style="background:none;border:none;padding:0;">
                                <span class="material-symbols-outlined like-icon text-sm"
                                      style="color:{{ $rLiked ? '#ff5a8a' : 'rgba(185,203,185,0.4)' }};font-variation-settings:{{ $rLiked ? "'FILL' 1" : "'FILL' 0" }},'wght' 400,'GRAD' 0,'opsz' 20;">favorite</span>
                                <span class="like-count text-[10px]" style="color:rgba(185,203,185,0.4);">{{ $reply->likes->count() > 0 ? $reply->likes->count() : '' }}</span>
                            </button>
                            @if($rIsMe || auth()->user()->is_admin)
                            <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline" onsubmit="return confirm('حذف شود؟');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] cursor-pointer"
                                        style="background:none;border:none;padding:0;color:rgba(255,90,90,0.4);">حذف</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleReplyBox(id) {
    const box = document.getElementById('reply-box-' + id);
    if (box) {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (box.style.display === 'block') box.querySelector('input[name="body"]')?.focus();
    }
}
</script>

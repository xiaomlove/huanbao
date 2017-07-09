
<div class="comment-comment">
	@foreach($list as $comment)
	<div class="media">
      <img class="d-flex mr-3" data-src="/dashboard/img/avatar-3.jpg" alt="32x32" src="/dashboard/img/avatar-3.jpg" data-holder-rendered="true" style="width: 32px; height: 32px;">
      <div class="media-body">
        <div class="comment-content"><span class="name">{{ $comment->user->name }}</span>: {{ $comment->detail->content }}</div>
      	<div class="text-right">{{ $comment->created_at->format('Y-m-d H:s')}}<a href="javascript:;" class="reply-to-someone" data-pid={{ $comment->id }}>回复</a></div>
      </div>
    </div>
    @endforeach
    {!! $paginator->links() !!}
    <p><a href="javascript:;" class="reply-to-main-comment">我也说一句</a></p>
</div>
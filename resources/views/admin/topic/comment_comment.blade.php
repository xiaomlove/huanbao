@if(count($list))
@inject('commentPresenter', 'App\Presenters\CommentPresenter')
<div class="comment-comment">
	@foreach($list as $comment)
	<div class="media">
      <div class="media-left">
          <a href="#">
              <img class="media-object" data-src="/dashboard/img/avatar-3.jpg" alt="32x32" src="/dashboard/img/avatar-3.jpg" data-holder-rendered="true" style="width: 32px; height: 32px;">
          </a>
      </div>
      <div class="media-body">
        <div class="comment-content">{!! $commentPresenter->renderDetail($comment, ['include_user' => true]) !!}</div>
      	<div class="text-right">{{ $comment->created_at->format('Y-m-d H:s')}}<a href="javascript:;" class="reply-to-someone" data-pid={{ $comment->id }}>回复</a></div>
      </div>
    </div>
    @endforeach
    {!! $list->appends(['tid' => $comment->tid, 'root_id' => $comment->root_id])->links('vendor.pagination.bootstrap-4-comment-comment') !!}
    <p><a href="javascript:;" class="reply-to-main-comment">我也说一句</a></p>
</div>
@endif
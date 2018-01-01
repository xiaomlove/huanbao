@extends('layouts.admin')

@section('title', '帖子详情-' . $topic->title)

@inject('commentPresenter', 'App\Presenters\CommentPresenter')

@section('content')
    @include('admin.common.message')

    <div id="topic-detail" class="container-fluid topic-show">
        <header>
            <table class="table">
                <tbody>
                <tr>
                    <td class="left-part"><h4>查看: {{ $topic->view_count }} | 回复: {{ $topic->comment_count }}</h4></td>
                    <td><h1>{{ $topic->title }}</h1></td>
                </tr>
                </tbody>
            </table>
        </header>
        <div class="comment-list">
            @foreach($list as $comment)
                <table class="table" data-comment-id="{{ $comment->id }}">
                    <tbody>
                    <tr>
                        <td class="left-part">
                            <div>
                                <div class="name"><strong>{{ $comment->user->name }}</strong></div>
                                <p><img alt="" src="/dashboard/img/avatar-1.jpg"></p>
                                <table class="table stat-box">
                                    <tbody>
                                    <tr>
                                        <th><p>12</p>话题</th>
                                        <th><p>12</p>回复</th>
                                        <th><p>12</p>经验</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td class="right-part">
                            <div class="comment-content">
                                {!! $commentPresenter->renderDetail($comment->detail) !!}
                            </div>
                        </td>
                    </tr>
                    <tr class="tr-action">
                        <td class="left-part"></td>
                        <td class="text-right">
                            <div class="action-wrap">
                                <span>{{ $commentPresenter->getFloorNumHuman($comment) }}</span>
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                                <span class="show-comment-comment" data-expanded="0"
                                      data-pid="{{ $comment->id }}"><a
                                            href="javascript:;">回复@if($comment->comment_count > 0)
                                            ({{ $comment->comment_count }})@endif</a></span>
                                <span><a href="{{ $commentPresenter->getEditLink($comment) }}">编辑</a></span>
                                <span title="赞" class="pointer"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></span>
                            </div>
                            <div class="comment-comment-wrap"></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            @endforeach
        </div>
        {!! $list->links() !!}
        <div class="add-comment">
            <table class="table">
                <tbody>
                <tr>
                    <td class="left-part">
                        <div>
                            <h2>张三</h2>
                            <p><img alt="" src="/dashboard/img/avatar-1.jpg"></p>
                        </div>
                    </td>
                    <td id="comment-form-wrap">
                        <form id="comment-form" method="post" action="{{ route('admin.comment.store') }}"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="tid" value="{{ $topic->id }}"/>
                            <input type="hidden" name="pid" value="0"/>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    @include('admin.common.error')
                                </div>
                            </div>
                            <div class="form-group row{{$errors->has('content') ? ' has-danger' : ''}}">
                                <div class="col-sm-6">
                                    <textarea rows="4" class="form-control" name="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 text-center">
                                    <input type="submit" class="btn btn-success" value="快速回复">
                                    <a href="{{ route('admin.comment.create', ['tid' => $topic->id]) }}" class="btn pull-right">高级</a>
                                    <a class="btn cancel pointer" style="display: none">取消回复</a>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('js')
    <script>
        var $topicDetail = $('#topic-detail');
        var $commentFormWrap = $('#comment-form-wrap');
        var $commentForm = $('#comment-form');
        var $commentFormCancel = $commentForm.find('.cancel');
        var $commentFormTextarea = $commentForm.find('textarea');
        var $pid = $commentForm.find("input[name=pid]");

        $commentFormCancel.click(function (e) {
            $commentFormCancel.hide();
            $commentFormWrap.append($commentForm);
            $pid.val("0");
        });


        $topicDetail.on("click", ".show-comment-comment", function (e) {
            var $this = $(this);
            var $td = $this.closest("td");
            var pid = $this.attr("data-pid");
            $pid.val(pid);
            var expanded = $this.attr("data-expanded");
            if (expanded == "0") {
                $.when(getComments($td.closest("table").attr("data-comment-id"), 1)).done(function (response) {
                    if (response.ret == 0) {
                        $this.attr("data-expanded", 1);
                        $commentFormTextarea.removeAttr("placeholder");
                        $td.find(".comment-comment-wrap").append(response.data.html);
                        showFormTo($this);
                    }
                }).fail(function () {
                    console.log(arguments);
                })
            } else {
                showFormTo($this);
            }
        });

        $topicDetail.on("click", ".reply-to-someone", function (e) {
            showFormTo($(this));
        });

        $topicDetail.on("click", ".reply-to-main-comment", function (e) {
            showFormTo($(this));
        });

        function showFormTo($elem) {
            $elem.closest("td").find(".comment-comment-wrap").append($commentForm);
            $commentFormTextarea.removeAttr("placeholder");
            $commentFormCancel.show();
            if ($elem.hasClass("reply-to-main-comment")) {
                var pid = $elem.closest(".table").attr("data-comment-id");
                $pid.val(pid);
            } else if ($elem.hasClass("reply-to-someone")) {
                var name = $elem.closest(".media-body").find(".name").text();
                var pid = $elem.attr("data-pid");
                $commentFormTextarea.attr("placeholder", "回复：" + name).val("");
                $pid.val(pid);
            }
        }

        function getComments(rootId, page) {
            var url = "{{ route('admin.comment.index')}}";
            var data = {
                tid: "{{ $topic->id }}",
                root_id: rootId,
                page: page
            }
            return $.ajax({
                url: url,
                data: data,
                method: "get",
                dataType: "json",
            });
        }


        //图片预览
        $topicDetail.on("click", ".attachment-image", function (e) {
            var $this = $(this);
            if (!$this.data("expand")) {
                $this.css({maxWidth: 800, maxHeight: 800}).data("expand", 1);
            } else {
                $this.removeAttr("style").data("expand", 0);
            }
        })


    </script>
@stop
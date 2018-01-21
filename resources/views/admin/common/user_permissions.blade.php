<div class="permissions-form-group form-group{{$errors->has('permissions') ? ' has-error' : ''}}">
    <label for="" class="col-sm-2 control-label">权限</label>
    <div class="col-sm-10">
        @foreach($permissions as $key => $group)
            <table class="table">
                <caption class="text-center"><h2>{{ $displayNames[$key] }}</h2></caption>
                <tbody>
                @if(count($group) == count($group, true))
                    <tr>
                        <th scope="row">{{ $displayNames[$key] }}</th>
                        <td>
                            @foreach($group as $item)
                                <label><input type="checkbox" value="{{ $item->name }}" name="permissions[]"{{ $user->hasDirectPermission($item->name) ? " checked" : "" }}>{{ $item->display_name }}</label>
                            @endforeach
                        </td>
                    </tr>
                @else
                    @foreach($group as $_key => $items)
                        @if(is_array($items))
                            <tr>
                                <th scope="row">{{ $displayNames[$_key] ?? "" }}</th>
                                <td>
                                    @foreach($items as $item)
                                        <label><input type="checkbox" value="{{ $item->name }}" name="permissions[]"{{ $user->hasDirectPermission($item->name) ? " checked" : "" }}>{{ $item->display_name }}</label>
                                    @endforeach
                                </td>
                            </tr>
                        @else
                            <tr>
                                <th scope="row">{{ $displayNames[$key] ?? "" }}</th>
                                <td><label><input type="checkbox" value="{{ $items->name }}" name="permissions[]"{{ $user->hasDirectPermission($items->name) ? " checked" : "" }}>{{ $items->display_name }}</label></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
        @endforeach
        @if($errors->has('permissions'))
            <small class="help-block">{{ $errors->first('permissions') }}</small>
        @endif
    </div>
</div>
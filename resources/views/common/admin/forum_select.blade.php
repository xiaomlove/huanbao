<select name="{{ $forumOptions->name}}" class="form-control">
  <option value="0">--无--</option>
  @foreach($forums as $forum)
  <option value="{{ $forum->id }}"{{ $forum->id == old($forumOptions->name, $forumOptions->selected) ? " selected" : "" }}>{{ str_repeat('——', $forum->depth) . $forum->name }}</option>
  @endforeach
</select>

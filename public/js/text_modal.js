;(function(window, $) {
	var TextModal = function(options) {
    var modalId = '__content_editor_text_modal__';
		var html = `
      <div class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">添加文本</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <textarea rows="8" style="width: 100%;padding: 15px"></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
              <button type="button" class="btn btn-primary submit">确定</button>
            </div>
          </div>
        </div>
      </div>`;
    var $modal = $(html);
    $modal.appendTo($("body")).modal({
      keyboard: false,
      backdrop: "static",
      show: false
    });
    var $textarea = $modal.find("textarea");
    var self = this;

    this.show = function(data) {
      $modal.modal("show");
      $textarea.val(data && data.text || "");
    }	   
    this.hide = function() {
      $modal.modal("hide");
    }
    this.createBlock = function(data) {
      return $('<p class="' + (data && data.classList && data.classList.join(" ") || "") + '">' + (data && data.text || "默认内容") + '</p>');
    }
    $modal.on("click", ".submit", function() {
      self.hide();
      if (options && options.onSubmit && $.isFunction(options.onSubmit)) {
        options.onSubmit.call(self, $textarea.val());
      }
    })
   

	}
  window.TextModal = TextModal;
})(window, jQuery);
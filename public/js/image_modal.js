;(function(window, $) {
    var ImageModal = function(options) {
    var defaults = {
      uploadUrl: "",
      uploadName: "image",
      afterUpload: function(response) {}
    }
    var options = $.extend(true, defaults, options);
    var modalId = '__content_editor_image_modal__';
        var html = `
      <div class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">添加图片</h5>
              <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="text-center"><img src="" class="preview" style="max-width: 100%;max-height: 600px"/></p>
              <p><input type="file" class="select-image" accept="image/jpeg, image/jpg, image/png, image/gif"/></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary cancel">取消</button>
              <button type="button" class="btn btn-primary submit">确定</button>
            </div>
          </div>
        </div>
      </div>`;
    var $modal = $(html);
    var $preview = $modal.find(".preview");
    var $selectImage = $modal.find(".select-image");
    var $submitBtn = $modal.find(".submit");
    var self = this, file, result, isUploading = false;

    $modal.appendTo($("body")).modal({
      keyboard: false,
      backdrop: "static",
      show: false
    }).on("hide.bs.modal", function(e) {
      $selectImage.val("");
      $preview.attr("src", "");
      $submitBtn.removeAttr("disabled").attr("确定");
    }).on("click", ".close,.cancel", function(e) {
      if (!isUploading) {
        $modal.modal("hide");
      }
    })
    
    $selectImage.on("change", function(e) {
      if (e.target.files.length == 0) {
        return;
      }
      file = e.target.files[0];
      var fr = new FileReader();
      fr.onload = function(e) {
        $preview.attr("src", e.target.result);
      }
      fr.readAsDataURL(file);
    })

    this.show = function(data) {
      $modal.modal("show");
      $preview.attr("src", data && data.uri || "");
    }      
    this.hide = function() {
      $modal.modal("hide");
    }
    this.createBlock = function(data) {
      return $('<p class="' + (data && data.classList && data.classList.join(" ") || "") + '"><img src="' + (data && data.uri || "") + '" style="max-width: 100%;max-height: 600px" /></p>');
    }
    $modal.on("click", ".submit", function() {
      if (options && options.uploadUrl) {
        //进行上传操作
        var formData = new FormData();
        formData.append(options.uploadName, file);
        $.ajax({
          url: options.uploadUrl,
          type: "post",
          dataType: "json",
          processData: false,
          contentType: false,
          data: formData,
          beforeSend: function() {
            $submitBtn.attr("disabled", "disabled").text("上传中...");
            isUploading = true;
          },

        }).done(function(response) {
          console.log(response);
          if (response.ret == 0) {
            result = response;
            if (options.afterUpload && $.isFunction(options.afterUpload)) {
              options.afterUpload.call(self, response);
            }
            self.hide();
          } else {
            alert(response.msg);
          }
        }).error(function(xhr, errstr) {
          alert(errstr);
        }).always(function() {
          $submitBtn.removeAttr("disabled").text("确定");
          isUploading = false;
        })
      } else {
        self.hide();
      }
    })
   

    }
  window.ImageModal = ImageModal;
})(window, jQuery);
<!-- Cropper Modal -->
<div class="modal fade bs-modal-lg" id="cropper_modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="img-container">
          <img id="cropper_image" src="" alt="Picture" style="max-width: 100%;">
        </div>
      </div>
      <div class="modal-footer">
        <img id="cropper_loader_img" style="display: none; width: 35px;" src="{{ asset('assets/images/media/page-loader.gif') }}">
        <button type="button" class="btn btn-primary crop_btn">Crop Image</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Cropper Modal -->

@push('style')
    <link href="{{ asset('assets/cropper/cropper.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/cropper/cropper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/cropper/cropper_init.min.js') }}"></script>
    <script type="text/javascript">var profile_img = []</script>
    {{ $slot }}
    <script type="text/javascript">
      if (profile_img) {profile_img.forEach(function(a,b){$("body").on("change","#"+a.id+"_input",function(b){initiate_cropper(a.modal_title,this,a.ratio,a.width,a.height,"#"+a.id+"_preview","#"+a.id),$(this).val("")}),$("#"+a.id+"_link").on("click",function(b){b.preventDefault(),$("#"+a.id+"_input:hidden").trigger("click")})})}
    </script>
@endpush
{{-- @if($errors->any())
    @foreach($errors->all() as $err )
        <div class="alert alert-danger d-flex align-items-center alert-dismissible" role="alert">
            {{ $err }}
           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endforeach
@endif

<div class="col-md-12">
    <div style="border: 2px dotted #aaa;" class="alert alert-danger d-flex align-items-center rounded-3" role="alert">
      <i class="icon ion-information-circled io-30 ps-1 pe-3"></i>
      <div class="d-flex flex-column">
        <h5 class="mb-0"><strong>Alert Title</strong></h5>
        <p class="p-0 m-0">An example warning alert with an icon</p>
      </div>
    </div>
</div> --}}
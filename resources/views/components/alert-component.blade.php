@if(Session::get('message'))
<script type="text/javascript">
    var message="{{ Session::get('message')['message'] }}";var message_type="{{ Session::get('message')['type'] }}";var title="{{ Session::get('message')['title'] }}";toast_message(message,message_type,title)
</script>
@endif

@if($errors->any())
@foreach($errors->all() as $error)
    <script type="text/javascript">
        var message="{{ $error }}";var message_type="danger";var title="Warning";toast_message(message,message_type,title)
    </script>
@endforeach
@endif

<script type="text/javascript">
jQuery("body").on('click','.delete-confirm-perm',function(event){event.preventDefault();const url=jQuery(this).attr('href');Swal.fire({title:'Are you sure?',text:"You can not revert this later!",icon:'warning',showCancelButton:!0,confirmButtonColor:'#3085d6',cancelButtonColor:'#d33',confirmButtonText:'Delete'}).then((result)=>{if(result.isConfirmed){window.location.href=url}})})

jQuery("body").on('click','.delete-confirm',function(event){event.preventDefault();const url=jQuery(this).attr('href');Swal.fire({title:'Are you sure?',text:"You can restore deleted items from trashed records!",icon:'warning',showCancelButton:!0,confirmButtonColor:'#3085d6',cancelButtonColor:'#d33',confirmButtonText:'Delete'}).then((result)=>{if(result.isConfirmed){window.location.href=url}})})

jQuery("body").on('click','.restore-confirm',function(event){event.preventDefault();const url=jQuery(this).attr('href');Swal.fire({title:'Are you sure?',text:"Want to restore this item?",icon:'question',showCancelButton:!0,confirmButtonColor:'#3085d6',cancelButtonColor:'#d33',confirmButtonText:'Restore'}).then((result)=>{if(result.isConfirmed){window.location.href=url}})})
</script>
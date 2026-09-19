@section('style')
<link rel="stylesheet" href="{{asset('assets/vendors/sweetalert2/sweetalert2.min.css')}}">
@stop
@props([
    'canEdit' => false,
    'canDelete' => false,
    'canShow' => false,
    'canDownload' => false,
    'canApprove' => false,
    'approveRoute' => false,
    'editRoute',
    'deleteRoute',
    'showRoute',
    'downloadRoute',
])
<div class="d-flex align-item-center">
    @if ($canShow)
        <a href="{{ $showRoute }}" class="btn btn-secondary btn-sm me-2" title="View"><span class="bi bi-eye"></span></a>
    @endif
    @if ($canApprove)
        <a href="{{ $approveRoute }}" class="btn btn-dark btn-sm me-2">Approve</a>
    @endif
    @if ($canDownload)
        <a href="{{ $downloadRoute }}" class="btn btn-dark btn-sm me-2" title="Delete" target="_blank"><em
                class="icon ni ni-download"></em></a>
    @endif
    @if ($canEdit)
        <a href="{{ $editRoute }}" class="btn btn-success btn-sm me-2" title="Edit"><span class="bi bi-pencil"></span></a>
    @endif
    @if ($canDelete)
        <form method="POST" action="{{ $deleteRoute }}" class="delete-form d-inline">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger btn-sm btn-delete">
                    <span class="bi bi-trash"></span>
            </button>
        </form>
    @endif
</div>
@section('scripts')
    <script src="{{asset('assets/js/extensions/sweetalert2.js')}}"></script>
    <script src="{{asset('assets/vendors/sweetalert2/sweetalert2.all.min.js')}}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                let form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This record will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
  </script>
   @if(session('success'))
   <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: '{{ session('success_title', 'Success') }}',
            text: '{{ session('success') }}',
            timer: 5000,
            showConfirmButton: true
        });
    });
   </script>
   @endif
   @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: '{{ session('error_title', 'Error') }}',
                text: '{{ session('error') }}',
                timer: 5000,
                showConfirmButton: true
            });
        });
   </script>
   @endif

@stop
